Plugin Logic
########

This section describes what happens behind the scenes when a plugin is loaded. And gives a high level overview

How a plugin is loaded
********************

The framework's plugin logic is seen in the - :php:namespace:`App\Plugins` directory.

When the composer commands are run, laravel has a hook which eventually calls the :php:class:`App\Plugins\ScmPackageManifest` class.

Normally, laravel will build the auto disovered packages by looking at the composer.json at the root of the project.

Our extended class will also look at all the plugin directories, and read from the composer json there.

Most of the work disovering them is done in the :php:class:`App\Plugins\PluginRef` class.

Once these new services are found, the plugin services are added to the ones the laravel disovered.

Then, for any resource folders that the plugins declare, the build method makes a soft link shortcut in the project public directory, so these resources can be seen on the web


How a plugin ineracts with the framework
********************

After a plugin is discovered and loaded by laravel, laravel calls the package boot method.

The boot method will have eventy listeners for the filters and actions called by the framework.

When those filters and actions are called, the plugin code will be activated in the hook code set up by the plugin in the boot method.


Plugins can be deactivated or removed at any time
********************

The plugin will continue to be sent the eventy messages until the plugin is removed from the plugins folder or has itself turned off, see :doc:`managing-plugins`

And next
--------

- :doc:`how-to-write-plugin` - How to write a plugin
