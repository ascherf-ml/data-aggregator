<?php

namespace App\Console\Commands\Dump;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Exception;
use Throwable;

class DumpExport extends AbstractDumpCommand
{

    protected $signature = 'dump:export
                            {endpoint? : Only export specific endpoint`}
                            {--path= : Directory where to save dump, with `json` subdir }';

    protected $description = 'Create JSON dumps of all public endpoints';

    public function handle()
    {
        $this->call('dump:config');

        $this->call('dump:info');

        $resources = $this->getResources();

        $resources->each(function($resource) {
            $resource['model']::addRestrictContentScopes();

            $relativeDumpPath = 'local/json/' . $resource['endpoint'];
            $absoluteDumpPath = $this->getDumpPath($relativeDumpPath);

            if (!file_exists($absoluteDumpPath)) {
                mkdir($absoluteDumpPath, 0755, true);
                chmod($absoluteDumpPath, 0755);
            }

            $bar = $this->output->createProgressBar($resource['model']::count());

            foreach ($resource['model']::cursor() as $item) {
                $filename = $relativeDumpPath . '/' . $item->getKey() . '.json';
                $content = $resource['transformer']->transform($item);

                $this->saveToJson($filename, $content);

                $bar->advance();
            }

            $bar->finish();
            $this->output->newLine(1);

        });
    }
}
