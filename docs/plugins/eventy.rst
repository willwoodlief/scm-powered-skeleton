Plugin Events
########

The events are all defined in interfaces

  * :php:interface:`App\Plugins\EventConstants\ActionRoutes`
  * :php:interface:`App\Plugins\EventConstants\FilterQuery`
  * :php:interface:`App\Plugins\EventConstants\ModelActions`
  * :php:interface:`App\Plugins\EventConstants\PageFrames`
  * :php:interface:`App\Plugins\EventConstants\Tables`
  * :php:interface:`App\Plugins\EventConstants\Views`

When a plugin service uses the events, you probably want to put the listeners in the booted section.

When using the https://github.com/spatie/laravel-package-tools helper, then that will be the packageBooted() function

Example usage in a plugin
------------------------


Here all the listeners are anonymous functions, but for more complicated stuff could easily be functions or classes to contain the logic of the event

Registers a listener to change the logo used in the menu

        .. code-block:: php

          Eventy::addFilter(Plugin::FILTER_LOGO_IMAGE, function( string $what)  {
               Utilities::markUnusedVar($what);
               return ScmSampleTheming::getPluginRef()->getResourceUrl('images/loading-coffee.gif');

          }, 20, 1);


Registers a listener to change the blade that has the page loading image, and to change the birthday blade

        .. code-block:: php

           Eventy::addFilter(Plugin::FILTER_SET_VIEW, function( string $bade_name) {
               if ($bade_name === 'layouts.app.preloader') {
                   return ScmSampleTheming::getBladeRoot().'::override/preloader';
               }

               if ($bade_name === 'employee.parts.employee_birthdays') {
                   return ScmSampleTheming::getBladeRoot().'::override/birthdays';
               }
               return $bade_name;
          }, 20, 1);


Registers a listener to add something to the top menu

        .. code-block:: php

           Eventy::addFilter(Plugin::FILTER_FRAME_END_TOP_MENU, function( string $extra_menu_stuff) {
               $item = view(ScmSampleTheming::getBladeRoot().'::content-for-filters.top-menu-item')->render();
               return $extra_menu_stuff."\n". $item;
          });


Registers a listener to add a new stylesheet

        .. code-block:: php

            Eventy::addFilter(Plugin::FILTER_FRAME_EXTRA_HEAD, function( string $stuff) {

               $link = ScmSampleTheming::getPluginRef()->getResourceUrl('css/sample-theming-plugin.css')."?".time();
               return $stuff.
               "<link href='$link' rel='stylesheet'>";
           }, 20, 1);


Register a listener to see when we are on the dashboard page, and set the bool value if we are

        .. code-block:: php

              Eventy::addAction(Plugin::ACTION_ROUTE_STARTED,function (\Illuminate\Http\Request $request) {
                   Utilities::markUnusedVar($request);
                   $route_name = Route::getCurrentRoute()->getName();
                   if ($route_name === 'dashboard') {
                       $this->onDashboard = true;
                   } else {
                       $this->onDashboard = false;
                   }
              });


Registers a listener to add a new blade to the dashboard at the bottom, to show output from a webservice (the quote service)
It is here we use the bool value for if we are on the dashboard

        .. code-block:: php

              Eventy::addFilter(Plugin::FILTER_FRAME_BOTTOM_PANEL, function( string $stuff) {
                   if ($this->onDashboard) {
                       $quote = ScmSampleTheming::getQuote();
                       $item = view(ScmSampleTheming::getBladeRoot().'::content-for-filters.quote')->with('quote',$quote)->render();
                       return $stuff. "\n".$item;
                   }
                   return $stuff;
              }, 20, 1);


Register an action to make something new in the database when a new invoice is created.
Of course, we could also update this new data when invoices are changed or deleted

        .. code-block:: php


            Eventy::addAction(Plugin::ACTION_MODEL_CREATED.Plugin::ACTION_MODEL_INVOICE , function (Invoice $invoice)
              {
                   $invoice_attributes = new ScmPluginSampleInventory();
                   $invoice_attributes->invoice_id = $invoice->id;
                   $invoice_attributes->invoice_importance = ScmPluginSampleInventory::STATUS_NORMAL;
                   $invoice_attributes->invoice_color_code = ScmPluginSampleInventory::COLOR_CODES[ScmPluginSampleInventory::STATUS_NORMAL];
                   $invoice_attributes->save();
              }, 10, 1);


Register listeners to add a new column to the invoice table based on the something new we created.


      .. code-block:: php


       Eventy::addFilter(Plugin::FILTER_INVOICE_TABLE_HEADERS, function(array $column_array)  {
           $column_array['invoice-importance'] = "Sample Plugin Demo";
           return $column_array;

       }, 5, 1);


      .. code-block:: php

          Eventy::addFilter(Plugin::FILTER_INVOICE_TABLE_HEADERS . Plugin::TABLE_COLUMN_SUFFIX,
          function(string $html, string $column, Invoice $invoice)
          {
               $item = '';
               if ($column === 'invoice-importance') {
                   $invoice_attributes = ScmPluginSampleInventory::where('invoice_id',$invoice->id)->first();
                   if (!$invoice_attributes) {
                       $invoice_attributes = new ScmPluginSampleInventory();
                       $invoice_attributes->invoice_id = $invoice->id;
                       $invoice_attributes->invoice_importance = ScmPluginSampleInventory::STATUS_NORMAL;
                       $invoice_attributes->invoice_color_code = ScmPluginSampleInventory::COLOR_CODES[ScmPluginSampleInventory::STATUS_NORMAL];
                       $invoice_attributes->save();
                   }
                   $item = view(ScmSampleTheming::getBladeRoot().'::content-for-filters.invoice-attribute-cell')
                   ->with('setting',$invoice_attributes)
                   ->render();
               }
               return $html. "\n".$item;
          },
          5, 3);





And next
--------

- :doc:`how-to-write-plugin` - How to write a plugin
