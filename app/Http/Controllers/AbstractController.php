<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request as RequestFacade;
use Closure;

use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use Aic\Hub\Foundation\ResourceSerializer;
use Aic\Hub\Foundation\Exceptions\BigLimitException;
use Aic\Hub\Foundation\Exceptions\InvalidSyntaxException;
use Aic\Hub\Foundation\Exceptions\ItemNotFoundException;
use Aic\Hub\Foundation\Exceptions\TooManyIdsException;

use Aic\Hub\Foundation\AbstractController as BaseController;

abstract class AbstractController extends BaseController
{

    const LIMIT_MAX = 100;

    /**
     * @var \League\Fractal\Manager
     */
    private $fractal;

    public function __construct()
    {
        $this->fractal = app()->make('League\Fractal\Manager');
        $this->fractal->setSerializer(new ResourceSerializer());
    }

    /**
     * Return a response with a single resource, given an Eloquent Model.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getItemResponse(Model $item)
    {
        return response()->json(
            $this->getGenericResponse($item, Item::class)
        );
    }

    /**
     * Return a response with multiple resources, given an arrayable object.
     * For multiple ids, this is a an Eloquent Collection.
     * For pagination, this is LengthAwarePaginator.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getCollectionResponse(Arrayable $collection, Request $request)
    {
        $response = $this->getGenericResponse($collection, Collection::class);

        if ($collection instanceof LengthAwarePaginator) {
            $paginator = [
                'total' => $collection->total(),
                'limit' => (int) $collection->perPage(),
                'offset' => (int) $collection->perPage() * ($collection->currentPage() - 1),
                'total_pages' => $collection->lastPage(),
                'current_page' => $collection->currentPage(),
            ];

            $params = http_build_query(collect($request->input())->except('page')->all());
            if ($collection->previousPageUrl()) {
                $paginator['prev_url'] = $collection->previousPageUrl() . ($params ? '&' . $params : '');
            }

            if ($collection->hasMorePages()) {
                $paginator['next_url'] = $collection->nextPageUrl() . ($params ? '&' . $params : '');
            }

            $response = ['pagination' => $paginator] + $response;
        }

        return response()->json($response);
    }

    /**
     * Helper to fill data and attach metadata for items and collections.
     * @param  string $resourceClass  Must implement \League\Fractal\Resource\ResourceAbstract
     * @return array
     */
    protected function getGenericResponse(Arrayable $inputData, string $resourceClass)
    {
        // Parse fractal includes and excludes
        $this->parseFractalParam('include', 'parseIncludes');
        $this->parseFractalParam('exclude', 'parseExcludes');

        $fields = RequestFacade::input('fields');
        $transformer = new $this->transformer($fields);
        $resource = new $resourceClass($inputData, $transformer);
        $data = $this->fractal->createData($resource)->toArray();
        $response = isset($data['data']) ? $data : ['data' => $data];

        $response['info'] = $transformer->getInfoFields();

        $config = config('aic.config_documentation');

        if ($config) {
            $response['config'] = $config;
        }

        return $response;
    }

    /**
     * Return a single resource. Not meant to be called directly in routes.
     * `$callback` should return an Eloquent Model.
     *
     * @return \Illuminate\Http\Response
     */
    protected function select(Request $request, Closure $callback)
    {
        $this->validateMethod($request);

        $id = $request->route('id');

        if (!$this->validateId($id)) {
            throw new InvalidSyntaxException();
        }

        $item = $callback($id);

        if (!$item) {
            throw new ItemNotFoundException();
        }

        return $this->getItemResponse($item);
    }

    /**
     * Return a list of resources. Not meant to be called directly in routes.
     * `$callback` should return LengthAwarePaginator.
     *
     * @return \Illuminate\Http\Response
     */
    protected function collect(Request $request, Closure $callback)
    {
        $this->validateMethod($request);

        // Process ?ids= query param
        $ids = $request->input('ids');

        if ($ids) {
            return $this->showMultiple($ids, $request);
        }

        // Check if the ?limit= is too big
        $limit = $request->input('limit') ?: 12;

        if (Gate::denies('restricted-access') && $limit > static::LIMIT_MAX) {
            throw new BigLimitException();
        }

        // This would happen for subresources
        $id = $request->route('id');

        // Assumes the inheriting class set model and transformer
        // \Illuminate\Contracts\Pagination\LengthAwarePaginator
        $all = $callback($limit, $id);

        return $this->getCollectionResponse($all, $request);
    }

    /**
     * Display multiple resources.
     *
     * @param string $ids
     * @return \Illuminate\Http\Response
     */
    protected function showMultiple($ids = '', $request = null)
    {
        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }

        if (Gate::denies('restricted-access') && count($ids) > static::LIMIT_MAX) {
            throw new TooManyIdsException();
        }

        // Validate the syntax for each $id
        foreach ($ids as $id) {
            if (!$this->validateId($id)) {
                throw new InvalidSyntaxException();
            }
        }

        // Illuminate\Database\Eloquent\Collection
        $all = $this->find($ids);

        return $this->getCollectionResponse($all, $request);
    }

    /**
     * Helper to parse Fractal includes or excludes.
     *
     * @link http://fractal.thephpleague.com/transformers/
     *
     * @param string $param  Name of query string param to parse
     * @param string $method  Either `parseIncludes` or `parseExcludes`
     */
    private function parseFractalParam($param, $method)
    {
        $values = RequestFacade::input($param);

        if (!isset($values)) {
            return;
        }

        // Fractal handles this internally, but we do it early for preprocessing
        if (is_string($values)) {
            $values = explode(',', $values);
        }

        // Allows for camel, snake, and kebab cases
        foreach ($values as &$value) {
            $value = Str::snake(Str::camel($value));
        }

        $this->fractal->{$method}($values);
    }
}
