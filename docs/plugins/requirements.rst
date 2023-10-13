Minimal requirements
########

Plugins can have a wide latitude of architecture, but there are some minimal requirements.

All plugins need to be in the plugins folder. a plugin must be in its own folder inside the plugins folder.

Requirements for a plugin:

* have no errors or warnings using php 8.1
* must have a composer.json at the root of the plugin's folder
* must register a new provider to the laravel 10 framework using package discovery in the composer.json
* listen to one or more filters or actions using the eventy library

  * see https://github.com/tormjens/eventy for an overview how these filters and actions are used
  * These filters and actions are defined in the :php:class:`<App\Plugins\Plugin>` class. See that section for more information
  * see the PluginSampleTheming section for demonstration


And next
--------

- :doc:`composer` - How does composer fit into all of this?
