<?php

namespace App\Providers;

use App\Http\Middleware\TrustProxies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as BaseServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends BaseServiceProvider
{
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot()
    {
        $this->registerPolicies();

        // API-189: Gate gets called before middleware initializes!
        $temp = new TrustProxies(config());
        $temp->handle(request(), function () {
            // We don't need to pass the request to anything
        });

        Gate::define('restricted-access', function ($user = null) {
            // If we're not applying restriction, you shall pass
            if (!config('aic.auth.restricted')) {
                return true;
            }

            // If your token is valid, you shall pass
            if (Auth::check()) {
                return true;
            }

            // If your IP is within a whitelisted range, you shall pass
            $whitelistedRanges = config('aic.auth.access_whitelist_ips');
            $matchingRanges = array_filter(array_map(function ($range) {
                // API-189: Without intervention, TrustProxies hasn't run
                if (ipInRange(request()->ip(), $range)) {
                    return $range;
                }
            }, $whitelistedRanges));

            if (count($matchingRanges) > 0) {
                return true;
            }

            // Otherwise, you shall not pass
            return false;
        });
    }
}
