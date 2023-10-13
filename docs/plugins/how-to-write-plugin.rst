Writing a plugin
########

how to write a plugin? Plugins can be very simple, and only a few lines of code in a handful of files. Or they can do many things in thousands of lines of code.

Included in the code is a sample plugin that does a little of every major thing.

Composer.json and you
----------------

However the code is made, the plugin needs to extend the Illuminate\Support\ServiceProvider class
and this service class needs to be pointed to accurately in the composer.json.

Below shows the extra part

    .. code-block:: json
        {
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
        }

In the composer.json

 * the autodiscover part of the composer.json needs to be setup
 * name of the package *should* match the composer package here
 * anything the plugin uses, via compose packages must be put into the require section


.. hint::

    If the autoloading section is not set up in the psr-4 section, then the plugin will not run, and there will be errors

      * note that on the left side is the top namespace in php for the plugin and on the right side is the relative file path the code starts at
      * here, the code starts in the src folder, and the files in that directory have the namespace.
      * the convention is that the class name must match the file name, and the namespace must match the folder structure
      * see https://www.php-fig.org/psr/psr-4/ and numerous tutorials and examples on the web. It can be tricky to set up the first time if never seen it





    .. code-block:: json

        {
            "name": "scm/scm-plugin-sample-theming",
            "require": {
                "php": "^8.1",
                "spatie/laravel-package-tools": "^1.14.0",
                "illuminate/contracts": "^10.0"
            },

            "autoload": {
                "psr-4": {
                    "Scm\\PluginSampleTheming\\": "src/"
                }
            }
        }


Anatomy of the example plugin
----------------

see - :php:namespace:`Scm\PluginSampleTheming` directory.

Service class
*********************

 This plugin uses the https://github.com/spatie/laravel-package-tools library to make the boilerplate laravel for a new service.
It is optional, but makes it simpler. It is not necessary.

Once the laravel has registered this plugin it calls each service defined by it. We only have one service here.

This service class is found by the framework in the composer.json extra.providers field

In this example class, :php:class:`Scm\PluginSampleTheming\ScmSampleThemingProvider`
I only need to override two methods configurePackage(), and packageBooted().

In the configurePackage() I use the laravel-package-tools to register new blade views, new routes,commands, facades, assets, migrations.
The routes will load in the controllers used for the routes

In a laravel package that uses its owns views, these views are always prefixed. See the constant I made called VIEW_BLADE_ROOT
and how its used in the other files

Note that we can organize the resources however we need in resources/dist and the blades however we see fit at resources/views


Facades
*********************

see :php:class:`Scm\PluginSampleTheming\Facades\ScmSampleTheming`

Plugins do not need any facades, but given this is laravel, this these are convenient to lump some logic together that is needed by the plugin

This class is found by the laravel framework via the composer.json extra field "aliases" (see above)

All this class does is hook up the Scm\PluginSampleTheming\ScmSampleTheming class to the framework


The logic for the Facade, and the methods, are defined at  :php:class:`Scm\PluginSampleTheming\ScmSampleTheming`


Routes, Controllers and Views
*********************

The views here are stored at the resources/views, they are called by the event listeners and the routes.

see controller example at  :php:class:`Scm\PluginSampleTheming\Controllers\SampleThemeController`, its called via the routes registered in the service

the routes are at routes/web.php


Databases and Models
*********************

Migrations are registered in the service class

When the plugin is registerd, this/these will show up as a new migration, to be run via the `php artisan migrate` command.

When the plugin is deactivated, the migration will go away, but the db is not changed in this example

The models are at src/Models


Events
*********************

The meat of the plugin is the events it listens too, its the only way to have the main code talk to it.

see :php:class:`Scm\PluginSampleTheming\PluginLogic` which has all the event listening code for the plugin


And next
--------

- :doc:`eventy` - How a plugin listens to events




