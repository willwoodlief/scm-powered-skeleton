Plugins and git
########

Plugins are not normally checked into the same repo as the main project unless its going to be used by most people.
For core plugins, they can either be part of the main repo, or brought in via the submodules git mechanism

Unless the .gitignore file is updated for the new core plugin, the repo will ignore anything put into the plugins folder -  unless the plugin is a sub-module. When cloning this framework use the git submodule commands to draw in the core plugins that are submodules.

Otherwise the plugins exist in their own repo, and then can be cloned in. Or the plugin can be a zip or tar.gz which is extracted to the plugins folder.


*********************
Adding in sub-modules
*********************
Submodules are one way of storing core plugins, that are used most anytime.
If the plugin is going to be used most of the time, in most of the installs - then it gets painful to have to manually add it all the time.

Having a plugin be a submodule allows for including them in one command using git.

Storing core plugins allow for development of them that is not connected to all the code in the main repo

For an overview of submodules see https://www.atlassian.com/git/tutorials/git-submodule

When cloning the main repository, to add in the plugins that are submodules, then

.. hint::

    git clone /url/to/repo/with/submodules

    git submodule init

    git submodule update


And next
--------

- :doc:`plugin-logic` - How a plugin is loaded
