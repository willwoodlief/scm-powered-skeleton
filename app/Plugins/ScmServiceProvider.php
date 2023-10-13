<?php

namespace App\Plugins;

use Illuminate\View\ViewServiceProvider;

/**
 * This service provider replaces the laravel service provider. We run our view factory class instead of the framework one. This allows some view hooks and actions to be called. We can do it by extending the normal class and rewriting one method
 *
 *
 *
 * @see ScmViewFactory
 *
 * @class ScmServiceProvider
 * @description Only purpose is to pass the overridden view factory to the laravel system
 */
class ScmServiceProvider extends ViewServiceProvider {
    /**
     * Create a new Factory Instance.
     *
     * @param  \Illuminate\View\Engines\EngineResolver  $resolver
     * @param  \Illuminate\View\ViewFinderInterface  $finder
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return ScmViewFactory
     */
    protected function createFactory($resolver, $finder, $events)
    {
        return new ScmViewFactory($resolver, $finder, $events);
    }
}
