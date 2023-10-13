<?php

namespace App\Plugins\EventConstants;


/**
 * The views has one filter and one action. The filter can swap out any blade used. The action broadcasts when a blade is being used.
 *
 *
 * These page frames events are called from the resources/views/layouts which frame the pages. There are two kinds of layouts here: guest and logged in.
 * The guest filters have the word guest in them.
 *
 * The filter returns a single blade, and only then the blade name without the blade.php part
 * (for example 'projects.project_parts.new_project_form')
 *
 * example:
 *
 * ```php
 * Eventy::addFilter(Plugin::FILTER_SET_VIEW, function( string $blade_name) {
 *          if ($blade_name === 'projects.project_parts.new_project_form') {
 *              return 'Scm::better_new_project_form'
 *          }
 *
 *          return $blade_name;
 *      }, 20, 1);
 * ```
 *
 * The action passes in the view class, the view class has many methods for figuring out what is going on
 *
 * Example
 *
 * ```php
 *      Eventy::addAction(Plugin::ACTION_SET_VIEW, function( \Illuminate\View\View $view) {
 *          Log::debug("starting the view",[$view->getPath()]);
 *      }, 20, 1);
 * ```
 */
interface Views {
    /**
     * Can change out the blade to be used. Has the name of the blade after it (without .blade.php)
     *
     * The filter must return a valid blade name, it can return the one sent to it
     *
     *
     * ```php
     *  Eventy::addFilter(Plugin::FILTER_SET_VIEW, function( string $blade_name) {
     *           if ($blade_name === 'projects.project_parts.new_project_form') {
     *               return 'Scm::better_new_project_form'
     *           }
     *
     *           return $blade_name;
     *       }, 20, 1);
     *  ```
     */
    const FILTER_SET_VIEW = "scm-view-filter";

    /**
     * When the view is first set/created (after the blade filter)
     * contains the view object \Illuminate\Contracts\View\View
     *
     * ```php
     * Eventy::addAction(Plugin::ACTION_SET_VIEW, function( \Illuminate\View\View $view) {
     *      Log::debug("starting the view",[$view->getPath()]);
     * }, 20, 1);
     * ```
     */
    const ACTION_SET_VIEW = "scm-view-action";

    const ALL_VIEW_ACTIONS = [
        self::ACTION_SET_VIEW
    ];

    const ALL_VIEW_FILTERS = [
        self::FILTER_SET_VIEW
    ];

}
