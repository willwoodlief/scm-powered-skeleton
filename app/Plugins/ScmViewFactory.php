<?php

namespace App\Plugins;

use TorMorten\Eventy\Facades\Eventy;

/**
 * This overridden view factory allows some plugin view hooks and actions to be run
 *
 * @see Plugin::FILTER_SET_VIEW which allows the name of any blade to be changed by a plugin
 * @see Plugin::ACTION_SET_VIEW which calls the action for plugins to see, after a view is chosen
 */
class ScmViewFactory extends \Illuminate\View\Factory {
    /**
     * Create a new view instance from the given arguments. Here we return our own view class
     *
     * @param  string  $view
     * @param  string  $path
     * @param  \Illuminate\Contracts\Support\Arrayable|array  $data
     * @return ScmView
     */
    protected function viewInstance($view, $path, $data)
    {
        return new ScmView($this, $this->getEngineFromPath($path), $view, $path, $data);
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * @see Plugin::FILTER_SET_VIEW which allows the name of any blade to be changed by a plugin
     * @see Plugin::ACTION_SET_VIEW which calls the action for plugins to see, after a view is chosen
     *
     * @param  string  $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array  $data
     * @param  array  $mergeData
     * @return \Illuminate\Contracts\View\View
     */
    public function make($view, $data = [], $mergeData = []) {

        $view = Eventy::filter(Plugin::FILTER_SET_VIEW, $view);

        $what =  parent::make($view,$data,$mergeData);

        if ($what->getName()) {
            Eventy::action(Plugin::ACTION_SET_VIEW, $what);
        }

        return $what;
    }

    /**
     * Create a new view factory instance. Here, we do nothing extra
     *
     * @param  \Illuminate\View\Engines\EngineResolver  $engines
     * @param  \Illuminate\View\ViewFinderInterface  $finder
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function __construct(\Illuminate\View\Engines\EngineResolver $engines, \Illuminate\View\ViewFinderInterface $finder, \Illuminate\Contracts\Events\Dispatcher $events)
    {
        parent::__construct($engines,$finder,$events);
    }
}
