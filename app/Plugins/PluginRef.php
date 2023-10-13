<?php

namespace App\Plugins;

use App\Console\Commands\ListPlugins;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;

/**
 * the PluginRef class finds and lists plugins, and manages their resource shortcuts
 */
class PluginRef
{

    /**
     * The plugin folder path in the filesystem
     * @var string the path to the plugin, this is absolute and not relative
     *
     * for example: /home/will/awork/scm-cms/plugins/scm-default-images
     */
    protected string $plugin_folder;

    /**
     * The name of the plugin, found in the composer.json name property
     * @var string The name of the plugin, includes the Author or org part: for example Scm/TestPlugin
     *
     * This string holds the full name
     *
     *  ```json
     * {
     *      "name": "scm/default-images"
     * }
     *  ```
     */
    protected string $full_plugin_name;

    /**
     * If a plugin has resources it wants to publish, this holds the value of that found in the composer.json in the extra field
     *
     * For example, this may be in the json, then the auto_publish_paths will hold the values. Both the expected values need to be in the json or this will be empty
     * ```json
     *  "extra": {
     *      "laravel": {
     *
     *          "auto_publish_assets" : {
     *              "alias": "resources",
     *              "relative_path":"resources/dist"
     *          }
     *      }
     * }
     * ```
     *
     * @var array{alias:string, relative_path:string}
     */
    protected array $auto_publish_paths;

    /**
     * Holds the alias found in the publish_paths
     *
     *
     * If the auto_publish_paths does not have that, or is empty, then this will be null
     *
     * @see PluginRef::$auto_publish_paths for the source of that
     *
     * @var string|null
     */
    protected ?string $resource_alias;

    /**
     * Holds the resource path, relative to the root of the plugin, found in the publish_paths
     *
     *
     *
     * if the auto_publish_paths does not have that, or is empty, then this will be null
     * so the full path would be the PluginRef::$plugin_folder / $resource_relative_path value
     *
     * @see PluginRef::$plugin_folder
     *
     * @see PluginRef::$auto_publish_paths for the source of that
     *
     * @var string|null
     */
    protected ?string $resource_relative_path;

    /**
     * true if this plugin is installed by laravel
     *
     * Its possible for a plugin to be there in the plugin folder, but not installed yet by laravel because nobody ran the composer command.
     * A plugin can be installed but ignored
     *
     * @var bool
     */
    protected bool $installed;

    /**
     * Shows if this plugin is active and installed
     *
     * A plugin can be ignored after the installation process. If the plugin cannot be called by laravel because of the
     * a missing value means its active, a value of off, no, 0 is going to have this ignored
     *
     * ```json
     * {
     *  "extra": {
     *      "laravel": {
     *          "plugin_active" : "Yes"
     *      }
     *  }
     * }
     * ```
     *
     * @var bool true if this plugin is active
     *
     */
    protected bool $active;

    /**
     * The version of the plugin, found in the composer.json name property
     * @var string The version of the plugin
     *
     * The version is only used to report the plugin in the console command
     * @see ListPlugins
     *
     * ```json
     * {
     *  "version": "0.9-alpha"
     * }
     * ```
     */
    protected string $version;

    /**
     * a comma delimited list of the authors for the plugin
     *
     * Looks at the name part only, and makes a list of any names found. Ignores the other author info.
     * The author name(s) is only used in the console plugin report
     * @see ListPlugins
     *
     * ```json
     * {
     *  "authors": [
     *      {
     *          "name": "Will Woodlief",
     *          "email": "willwoodlief@gmail.com",
     *          "role": "Developer"
     *      }
     *  ]
     * }
     * ```
     *
     * @var string
     */
    protected string $authors;

    /**
     * the description found from the composer.json "description" field
     *
     * Looks at the name part only, and makes a list of any names found. Ignores the other author info.
     * The author name(s) is only used in the console plugin report
     * @see ListPlugins
     *
     * ```json
     * {
     *      "description": "I have a really cool plugin here. It does A, B and C stuff ... "
     * }
     * ```
     *
     * @var string
     */
    protected string $description;

    /**
     * the plugins folder name, relative to the top folder of the project
     */
    const PLUGINS_FOLDER = 'plugins';

    /**
     * The key used in the json extra to tell about assets to publish
     * @see PluginRef::$auto_publish_paths
     */
    const AUTO_PUBLISH_ASSET_COMPOSER_KEY = 'auto_publish_assets';

    /**
     * The key used in the json extra to tell if we should ignore this plugin
     * @see PluginRef::$active
     */
    const PLUGIN_ACTIVE_COMPOSER_KEY = 'plugin_active';

    /**
     * The key used in the json extra publish paths that has the alias value
     * @see PluginRef::$resource_alias
     */
    const RESOURCE_ALIAS_COMPOSE_KEY = 'alias';

    /**
     * The key used in the json extra publish paths that has the relative path to the resources value
     * @see PluginRef::$resource_relative_path
     */
    const RESOURCE_RELATIVE_PATH_COMPOSE_KEY = 'relative_path';


    /**
     * Makes a new plugin ref object, given the location of the folder. Will read in the composer file and fill in values from that
     * @param string $plugin_folder
     * @uses PluginRef::getComposer()
     */
    public function __construct(string $plugin_folder)
    {

        $this->plugin_folder = $plugin_folder;
        $this->auto_publish_paths = [];
        $this->resource_alias = null;
        $this->resource_relative_path = null;

        if (!is_dir($this->plugin_folder)) {
            throw new \LogicException("Not a valid folder");
        }

        $composer = $this->getComposer();


        if (!isset($composer['name'])) {
            throw new \LogicException("Plugin folder does not have a ['name'] in composer.json");
        }
        $this->full_plugin_name = $composer['name'];
        $this->version = $composer['version']??'';
        $this->description = $composer['description']??'none';

        $author_names = [];
        $authors = $composer['authors']??[];
        foreach ($authors as $author) {
            $author_name = $author['name']??null;
            if ($author_name) {$author_names[] = $author_name;}
        }
        $this->authors = implode(',',$author_names);

        if (!isset($composer['extra']['laravel'])) {
            Log::warning("$this->full_plugin_name at $this->plugin_folder does not have any ['extra']['laravel'] in composer.json");
            $this->active = false;
        } else {
            $this->active = true;
            if (isset($composer['extra']['laravel'][static::PLUGIN_ACTIVE_COMPOSER_KEY])) {
                if (empty($composer['extra']['laravel'][static::PLUGIN_ACTIVE_COMPOSER_KEY])) {
                    $this->active = false;
                }
                $active_value = $composer['extra']['laravel'][static::PLUGIN_ACTIVE_COMPOSER_KEY];
                switch (mb_strtolower($active_value) ) {
                    case 'no':
                    case 'false':
                    case 'off':
                    case '0': {
                    $this->active = false;
                    }
                }
            }
        }

        if ($this->active) {
            if (isset($composer['extra']['laravel'][static::AUTO_PUBLISH_ASSET_COMPOSER_KEY])) {
                $this->auto_publish_paths = $composer['extra']['laravel'][static::AUTO_PUBLISH_ASSET_COMPOSER_KEY];
                $this->resource_alias = ($this->auto_publish_paths[static::RESOURCE_ALIAS_COMPOSE_KEY] ?? null);
                $this->resource_relative_path = ($this->auto_publish_paths[static::RESOURCE_RELATIVE_PATH_COMPOSE_KEY] ?? null);

                if (!$this->resource_relative_path) {
                    Log::warning("Cannot find relative asset path in plugin $this->full_plugin_name ", [$this->auto_publish_paths]);
                }

                if (!$this->resource_alias) {
                    Log::warning("Cannot find alias of asset in plugin $this->full_plugin_name ", [$this->auto_publish_paths]);
                }
            }
        }
    }

    /**
     * if there is a composer file in the plugin directory, it will convert that to a php array
     *
     * Throws exception if the composer.json is missing
     * @return array
     */
    public function getComposer() : array  {
        $composerFile = $this->plugin_folder . DIRECTORY_SEPARATOR . 'composer.json';
        $composer = json_decode(file_get_contents($composerFile), true);
        if (empty($composer)) {
            throw new \LogicException("Plugin folder does not have readable composer.json");
        }
        return $composer;
    }

    /**
     * Creates a soft filesystem link in the site's public folder, to the plugin resource directory
     *
     * The link allows web access to that resource directory. Otherwise the plugin is not visible on the web
     * If there is an issue creating the link, that is logged.
     * If there is no resources to publish this just returns false. The resource_alias and resource_relative_path are both defined to do this.
     *
     * @see PluginRef::$resource_alias
     * @see PluginRef::$resource_relative_path
     *
     * @param Filesystem|null $files the laravel files object
     * @return bool true if success, false if issue
     */
    public function createSymLinkInPublic(?Filesystem $files = null) : bool {

        if (!$this->resource_alias) {return false;}
        if (!$this->resource_relative_path) {return false;}
        if (!$this->isActive()) {return false;}

        if (empty($files)) {
            $files = new Filesystem;
        }

        $full_asset_path = $this->getPluginFolder() . DIRECTORY_SEPARATOR . $this->resource_relative_path;
        $real_asset_path = realpath($full_asset_path);
        if (!$real_asset_path || !is_readable($real_asset_path) || !is_dir($real_asset_path)) {
            throw new \RuntimeException("Cannot find readable directory in plugin asset ".$full_asset_path);
        }

        //make symlink
        $link_path = $this->getResourceRootPath();
        try {
            $files->link($real_asset_path, $link_path);
            Log::debug("Made plugin asset link of  $link_path from real path of $real_asset_path");
            return true;
        } catch (\Exception $e) {
            Log::warning("Cannot create asset link in public plugins directory ",[
                'error' => $e->getMessage(),
                'real_asset_path' => $real_asset_path,
                'link_path' => $real_asset_path,
            ]);
            return false;
        }
    }

    /**
     * returns the absolute(full) folder path to the public resource of this plugin, if no resource set, it will return null
     * @see PluginRef::getPublicPluginRoot()
     * @see PluginRef::getResourceRootPath()
     * @return string|null
     */
    public function getResourceRootPath() : ?string  {
        if (!$this->resource_alias) {return null;}
        return static::getPublicPluginRoot() . DIRECTORY_SEPARATOR . $this->getResourceRelativePath();
    }

    /**
     * Calculates the url of the resource given a relative resource file path
     *
     * For example: ScmSampleTheming::getPluginRef()->getResourceUrl('images/loading-coffee.gif');
     *
     * @param string $relative_path
     * @param bool|null $secure
     * @return string|null
     */
    public function getResourceUrl(string $relative_path,?bool $secure = null) : ?string  {
        if (!$this->resource_alias) {return null;}
        return asset(static::PLUGINS_FOLDER. DIRECTORY_SEPARATOR . $this->getResourceRelativePath($relative_path),$secure);
    }

    /**
     * Given a path relative to the resource folder for the plugin, returns the relative link to the plugin
     *
     * used in calculating the path for the resources: both in setting the soft link and later getting the url
     *
     * @param string|null $relative_path
     * @return string|null
     */
    public function getResourceRelativePath(?string $relative_path= null )  : ?string {
        if (!$this->resource_alias) {return null;}
        $friendly_name = str_replace('/','-',$this->full_plugin_name);
        $friendly_name = str_replace(' ','-',$friendly_name);
        $friendly_name = mb_strtolower($friendly_name);
        $root =   $friendly_name . '-' . $this->resource_alias;
        if ($relative_path) {
            return $root. DIRECTORY_SEPARATOR . $relative_path;
        }
        return $root;
    }

    /**
     * Gets the full file path of the plugin root
     *
     * @return string
     *
     */
    public static function getPublicPluginRoot() : string  {
        return app()->publicPath(static::PLUGINS_FOLDER);
    }

    /**
     * Clears all the plugin soft links in the public folder
     *
     * The function will clear all the links when the laravel is redoing the auto-loaded providers,
     * this ensures that only the current plugins will have soft links later, when those are recreated or made for the first time
     * @param Filesystem|null $files
     * @return void
     */
    public static function clearAllLinksInPublic(?Filesystem $files = null) : void {
        if (empty($files)) {
            $files = new Filesystem;
        }

        $public_plugin_directory = static::getPublicPluginRoot();
        $current_pp_dir = opendir($public_plugin_directory);
        while (($filename = readdir($current_pp_dir)) !== false) {

            if ($filename == '.' || $filename == '..') {
                continue;
            }
            $maybe_link = $public_plugin_directory . DIRECTORY_SEPARATOR . $filename;
            if (is_link($maybe_link)) {
                $files->delete($maybe_link);
            }
        } //end going through the older symlinks
    }


    /**
     * Creates the plugin directory if it exists
     * @param Filesystem|null $files
     * @return void
     */
    public static function maybeCreatePublicPluginDirectory(?Filesystem $files = null) {
        if (empty($files)) {
            $files = new Filesystem;
        }

        $public_plugin_directory = app()->publicPath(static::PLUGINS_FOLDER);
        //make the dir if the first time
        if (!is_readable($public_plugin_directory)) {
            $files->makeDirectory($public_plugin_directory,true);
        }
    }

    /**
     * Gets the Laravel cached providers, this is an array of package names
     * @return  string[]
     */
    public static function getInstalledPackageNames() {
        if (!is_readable(app()->getCachedPackagesPath())) {return [];}
        $array = include( app()->getCachedPackagesPath());
        if (empty($array)) {return [];}
        if (!is_array($array)) {return [];}
        return array_keys($array);
    }

    /**
     * The function finds all the plugins in the Plugin Folder, whether they are installed or not
     *
     * Scans the files, for each folder with a composer file and laravel extra settings, makes a PluginRef class
     * compares against the plugins already installed to mark the plugin installed or not
     *
     * @see PluginRef::$installed
     * @uses PluginRef::getInstalledPackageNames to see if this plugin is already installed or not
     * @return PluginRef[]
     */
    public static function findPlugins(): array {


        $installed_packages = static::getInstalledPackageNames();

        /**
         * @var PluginRef[] $plugin_refs
         */
        $plugin_refs= [];
        $modulesPath = base_path() . DIRECTORY_SEPARATOR . static::PLUGINS_FOLDER;
        $currentDir = opendir($modulesPath);
        while (($filename = readdir($currentDir)) !== false) {
            $subDir = $modulesPath . DIRECTORY_SEPARATOR . $filename;
            if ($filename == '.' || $filename == '..') {
                continue;
            } else if (is_dir($subDir)) {
                $composerFile = $subDir . DIRECTORY_SEPARATOR . 'composer.json';

                if (!is_readable($composerFile)) {
                    continue;
                }

                $composer = json_decode(file_get_contents($composerFile), true);
                if (isset($composer['extra']['laravel'])) {
                    $plugin_refs[] = $node =  new PluginRef($subDir);
                    if (in_array($node->getFullPluginName(),$installed_packages)) {
                        $node->installed = true;
                    } else {
                        $node->installed = false;
                    }
                }
            }
        }
        return $plugin_refs;
    }

    /**
     * Gets the plugin's top folder
     *
     * @see PluginRef::getPluginFolder()
     *
     * @return string
     */
    public function getPluginFolder(): string
    {
        return $this->plugin_folder;
    }

    /**
     * Gets the plugin name
     *
     * @see PluginRef::$full_plugin_name
     * @return string
     */
    public function getFullPluginName(): string
    {
        return $this->full_plugin_name;
    }

    /**
     * has this plugin been installed?
     *
     * @see PluginRef::$installed
     * @return bool
     */
    public function isInstalled(): bool
    {
        return $this->installed;
    }

    /**
     * Gets the plugin's version
     *
     * @see PluginRef::$version
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * gets the plugin author(s)
     *
     * @see PluginRef::$authors
     * @return string
     */
    public function getAuthors(): string
    {
        return $this->authors;
    }

    /**
     * Gets the plugin description
     *
     * @see PluginRef::$description
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Tells if the plugin is active
     * @see PluginRef::$active
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }




}
