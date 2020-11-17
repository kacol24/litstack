<?php

namespace Ignite\Routing;

use Closure;
use Illuminate\Support\Facades\Route;

class Router
{
    /**
     * Public middlewares.
     *
     * @var array
     */
    protected $publicMiddlewares = [
        'web',
    ];

    /**
     * Initialize defaults for a Lit route.
     * Lit Routes should always be created
     * with \Ignite\Support\Facades\Route.
     *
     * @return \Illuminate\Support\Facades\Route
     */
    public function __call($method, $parameters)
    {
        $route = $this->getRoutePreset();

        return $route->$method(...$parameters);
    }

    /**
     * Get middlewares.
     *
     * @return array
     */
    protected function getMiddelwares()
    {
        return app(\Ignite\Application\Kernel::class)->getMiddlewares();
    }

    /**
     * Get route preset.
     *
     * @return \Illuminate\Support\Facades\Route
     */
    protected function getRoutePreset()
    {
        return Route::prefix(config('lit.route_prefix'))
            ->as('lit.')
            ->middleware($this->getMiddelwares());
    }

    /**
     * Create a route group with shared attributes.
     *
     * @param  Closure|array|string $attributes
     * @param  Closure              $closure
     * @return void
     */
    public function group($attributes, Closure $closure = null)
    {
        if (is_callable($attributes) || is_string($attributes)) {
            return $this->getRoutePreset()->group($attributes);
        }
        $attributes['prefix'] = config('lit.route_prefix').'/'.($attributes['prefix'] ?? '');
        $attributes['as'] = 'lit.'.($attributes['as'] ?? '');
        $attributes['middleware'] = array_merge($attributes['middlewares'] ?? [], $this->getMiddelwares());
        Route::group($attributes, $closure);
    }

    /**
     * Public route using Lit route prefix.
     *
     * @return Route
     */
    public function public()
    {
        return Route::prefix(config('lit.route_prefix'))
            ->as('lit.')
            ->middleware($this->publicMiddlewares);
    }
}
