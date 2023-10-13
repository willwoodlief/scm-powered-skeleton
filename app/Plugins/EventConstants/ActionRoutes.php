<?php

namespace App\Plugins\EventConstants;

/**
 * There is only one action for the route chosen, fired after the action is determined.
 *
 * This action is helpful to know which page is being displayed, so if the plugin only wants to display to the dashboard, for example.
 * Then, it can set a flag to itself to fill in the bottom panel when the filter for that is called, and then only the dashboard bottom panel will be filled.
 *
 *  Another use would be to get form data for some form not under the plugins control
 *
 *  ```php
 * Eventy::addAction(Plugin::ACTION_ROUTE_STARTED,function (\Illuminate\Http\Request $request) {
 *      $route_name = Route::getCurrentRoute()->getName();
 *      if ($route_name === 'dashboard') {
 *          $this->onDashboard = true;
 *      } else {
 *          $this->onDashboard = false;
 *      }
 * });
 *  ```
 */
interface ActionRoutes {
    /**
     * Fired for each route used. Will be passed the request object
     *
     * Can be used for telling the plugin which page we are on, or if needing the request object for form data
     *
     * ```php
     *  Eventy::addAction(Plugin::ACTION_ROUTE_STARTED,function (\Illuminate\Http\Request $request) {
     *       $route_name = Route::getCurrentRoute()->getName();
     *       if ($route_name === 'dashboard') {
     *           $this->onDashboard = true;
     *       } else {
     *           $this->onDashboard = false;
     *       }
     *  });
     *   ```
     */
    const ACTION_ROUTE_STARTED = 'action-route-started';

    const ALL_ROUTE_ACTIONS = [
        self::ACTION_ROUTE_STARTED
    ];

}
