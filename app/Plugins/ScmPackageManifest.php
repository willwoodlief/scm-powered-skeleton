<?php

namespace App\Plugins;

use Illuminate\Foundation\PackageManifest;
use Illuminate\Support\Facades\Log;

/**
 * This package manifest is used by it replacing the laravel one, that way we can read in the plugin services too, in addition to the normal laravel stuff. We can do it by extending the normal class and rewriting one method
 * @see ScmPackageManifest::build() for more details
 */
class ScmPackageManifest extends PackageManifest{

    /**
     * The build method does the normal laravel work, but also goes down through the plugin folder and finds any services to add that way
     *
     * This build method is called via a composer hook, made by the laravel framework, that is run automatically when the composer tool rebuilds its list.
     * So, this function is called by laravel when you run composer update, composer dump-autoload, composer install, etc
     *
     * Works by adding in the plugin packages to the list being managed by laravel.
     *
     *   * Then for each active plugin, making sure that its shortcut, to its media, is added to the public folder.
     *   * Inactive or deleted plugins have their shortcut deleted
     *
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Exception
     *
     * @uses PluginRef::createSymLinkInPublic
     * @uses PluginRef::findPlugins
     * @uses PluginRef::maybeCreatePublicPluginDirectory
     * @uses PluginRef::clearAllLinksInPublic
     */
    public function build()
    {
        $packages = [];

        if ($this->files->exists($path = $this->vendorPath.'/composer/installed.json')) {
            $installed = json_decode($this->files->get($path), true);

            $packages = $installed['packages'] ?? $installed;
        }



        $plugin_refs = PluginRef::findPlugins();
        $discoverPackages = [];
        foreach ($plugin_refs as $found_plugin) {
            if ($found_plugin->isActive()) {
                $discoverPackages[$found_plugin->getFullPluginName()] = $found_plugin->getComposer();
            }

        }

        foreach ($discoverPackages as $package_name => $dis) {
            if (!array_key_exists($package_name,$packages)) {
                $packages[] = $dis;
                Log::debug("Added extra package $package_name ");
            }

        }

        $ignoreAll = in_array('*', $ignore = $this->packagesToIgnore());

        $output = collect($packages)->mapWithKeys(function ($package) {
            return [$this->format($package['name']) => $package['extra']['laravel'] ?? []];
        })->each(function ($configuration) use (&$ignore) {
            $ignore = array_merge($ignore, $configuration['dont-discover'] ?? []);
        })->reject(function ($configuration, $package) use ($ignore, $ignoreAll) {
            return $ignoreAll || in_array($package, $ignore);
        })->filter()->all();

        $this->write($output);

        Log::debug("Updated cached packages to ".app()->getCachedPackagesPath());


        //remove all public/plugins symlinks and recreate them

        //make sure plugin public directory exists
        PluginRef::maybeCreatePublicPluginDirectory($this->files);

        //remove all symlinks found here in the directory
        PluginRef::clearAllLinksInPublic($this->files);

        // add in sym link if the plugin has assets they want auto published
        foreach ($plugin_refs as $plugin_ref) {
            $plugin_ref->createSymLinkInPublic($this->files);
        }

    }//end function

}
