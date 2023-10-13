.. toctree::
   :maxdepth: 2

   intro <index>
   managing-plugins
   requirements
   composer
   git
   plugin-logic
   how-to-write-plugin
   eventy

Plugins
########

Plugins allow for flexible development and deployment of the scm csm system. They can change how the website works, and add new features and content, alter existing content, and add extra business logic; without changing the code of the main project.

Once a plugin is made, it can be added or removed as needed


Overview
*********************

A plugin here is simply a type of laravel provider that is lazy loaded.
I combined a few technologies to allow the plugins to load fully without having to adjust anything in the framework code.

I also added in an ability for the plugins to be able to expose to the web their resources, if they wanted


And next
--------

- :doc:`managing-plugins` - how to add and remove plugins.
- :doc:`how-to-write-plugin` - how to write a plugin.





