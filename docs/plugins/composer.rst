Plugins and composer
########

The plugins use composer tool , that means they must have a composer.json file at the root of the plugin, even if they do not use any
third party libraries.



Composer.json fields
====================


The composer json needs to have the following fields set up. See https://getcomposer.org/doc/04-schema.md for valid fields

* name - this needs to have at least two parts to this name A/B where A is the author or organization and B is the code's name of the plugin
* description - can be anything
* authors - at least one author
* the extra block


Each plugin must have its laravel registration options inside the extra block

See https://laravel.com/docs/10.x/packages#package-discovery for how to configure the composer.json

Laravel uses the extra to register the service. There are four keys that are looked at by this code.
Any other information here is ignored by the plugin system.

The four keys are in the laravel section:

* **providers**, need at least one provider, can register more than one here. This is used by the laravel core code.
* **aliases**, optional, used when registering a facade or related resource. This is used by the the laravel core.
* **auto_publish_assets**, for when the plugin wants to make public any images, css, js or other assets.
    * *"alias"* can be the same in different plugins, and is required. Its used to help make a path to the resource
    * *"relative_path"* is the location of the resource folder inside the plugin. All resources can be organized in multiple files and folders but must be under this path
* **plugin_active**, default is yes, when its value is no, or off, then the plugin is unregistered when the composer runs.

An example of the extra section for a plugin that registers one service, one fascade, has media to show would be
    .. code-block:: json

        "extra": {
            "laravel": {
                "providers": [
                    "Scm\\PluginSampleTheming\\ScmSampleThemingProvider"
                ],
                "aliases": {
                    "ScmSampleTheming": "Scm\\PluginSampleTheming\\Facades\\ScmSampleTheming"
                },
                "auto_publish_assets" :
                {
                    "alias": "resources",
                    "relative_path":"resources/dist"
                },
                "plugin_active": "yes"
            }
        }

Composer downloaded library locations
====================

The plugin system uses the composer merge tool at https://github.com/wikimedia/composer-merge-plugin
This is already installed by the framework, which scans the plugin folder when composer commands are used in the main project.
During composer install or update all dependencies are installed and merged to the /vendor folder in the project root.
This means that any dependencies a plugin uses must be compatitble with the installed other packages from the framework
and all other installed plugins at composer.lock.

If the plugin needs to use an incompatible library, it can save that library in a new namespace and not download it via composer


And next
--------

- :doc:`git` - Managing plugins with git
