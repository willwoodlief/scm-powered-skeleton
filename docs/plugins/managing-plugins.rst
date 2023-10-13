Managing Plugins
########

Adding and Removing
********************

Plugins have a standard way of being added and removed

To add and turn on a plugin
----------------

* Add plugin folder to the /plugins directory
* On the command line, cd to the root of the project, and run `composer update`
* Any valid plugin that is not turned off (see "plugin_active" in composer.json) will be installed into the laravel, and its dependencies added, if any, to the /vendor folder.

To turn off a plugin
----------------
* Either delete the plugin's folder, or keep the plugin but set the "plugin_active" to "off"
* Run `composer update` , this will also remove any libraries the plugin is using that is not used by anything else.


.. hint::

   use `composer update` when installing or uninstalling plugins. Because this command can possibly also update the core laravel libraries,and other dependencies to the next minor version. Its good to lock down those specific versions in the composer.json file in the project root. That way there are no unexpected new library changes, and the lock file does not change that much, unless the plugins have installed new libraries. This means, whenever possible put the exact version of the requirents. IE ` "laravel/framework": "^10.21.0" and not "laravel/framework": "^10.10"`. When ready to update, there are commands in composer for that, and the process is still automated.

And next
--------

- :doc:`requirements` - what does a plugin need?
