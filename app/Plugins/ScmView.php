<?php

namespace App\Plugins;


/**
 * This view does nothing extra, and is a placeholder for future hooks. It is called via the factory class
 * @see ScmViewFactory::viewInstance
 */
class ScmView extends \Illuminate\View\View {


    /** @noinspection PhpRedundantMethodOverrideInspection */
    public function setPath($path)
    {
        parent::setPath($path);
    }

}
