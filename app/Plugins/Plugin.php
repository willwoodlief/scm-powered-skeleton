<?php

namespace App\Plugins;

use App\Plugins\EventConstants\ActionRoutes;
use App\Plugins\EventConstants\FilterQuery;
use App\Plugins\EventConstants\Images;
use App\Plugins\EventConstants\ModelActions;
use App\Plugins\EventConstants\PageFrames;
use App\Plugins\EventConstants\Tables;
use App\Plugins\EventConstants\Views;

/**
 * The Plugin class can be used as a reference for all the actions and hooks. It implements the constants defined in the EventConstants namespace
 *
 * Can be used as a base class in a plugin.
 *
 * The different constant names of these hooks are defined in a few interfaces this class implements
 *
 */
abstract class Plugin
    implements Images,ActionRoutes,Views,PageFrames,Tables,ModelActions,FilterQuery
{
    /**
     * When this class is extended by a plugin, the listeners can be put in this class
     * @return mixed
     */
    abstract public function initialize();
}
