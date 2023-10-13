<?php

namespace App\Plugins\EventConstants;



use App\Helpers\Projects\ProjectFile;
use App\Models\ConstructionExpense;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Project;
use App\Models\User;

/**
 * Table filters have two different types: The first sets extra columns in a table. The second fills in the content for that new column
 *
 * Extra columns filter is passed an array of headers, the filter function can remove or add new things to this.
 *
 * Each array has a key for some id that is used in the other filter, and a value that is the column name which is shown on the page
 *
 * example for adding in a new column to the invoices table:
 *
 * ```php
 * Eventy::addFilter(Plugin::FILTER_INVOICE_TABLE_HEADERS, function(array $column_array)  {
 *      $column_array['invoice-importance'] = "Sample Plugin Demo";
 *      return $column_array;
 * }, 5, 1);
 * ```
 *
 *
 * The corresponding filter has three arguments: the html for the table cell content for that column already set, the column id set above,
 * and the object the table row is rendering. Here in the example, this is an invoice, and we are filling in a cell for some settings
 * for the column made above
 *
 * ```php
 *  Eventy::addFilter(Plugin::FILTER_INVOICE_TABLE_HEADERS . Plugin::TABLE_COLUMN_SUFFIX,
 *      function(string $html, string $column, Invoice $invoice)
 *      {
 *          $item = '';
 *          if ($column === 'invoice-importance') {
 *              $invoice_attributes = ScmPluginSampleInventory::where('invoice_id',$invoice->id)->first();
 *              if (!$invoice_attributes) {
 *                  $invoice_attributes = new ScmPluginSampleInventory();
 *                  $invoice_attributes->invoice_id = $invoice->id;
 *                  $invoice_attributes->invoice_importance = ScmPluginSampleInventory::STATUS_NORMAL;
 *                  $invoice_attributes->invoice_color_code = ScmPluginSampleInventory::COLOR_CODES[ScmPluginSampleInventory::STATUS_NORMAL];
 *                  $invoice_attributes->save();
 *              }
 *              $item = view(ScmSampleTheming::getBladeRoot().'::content-for-filters.invoice-attribute-cell')
 *                  ->with('setting',$invoice_attributes)
 *                  ->render();
 *          }
 *          return $html. "\n".$item;
 *      },
 *      5, 3);
 * ```
 *
 * Each table in the main project code has a pair of these filters
 */
interface Tables {


    /**
     * The root part of the filter name for all the table filters. Helps to be able to loop through all filters and organizes this better too
     */
    const FILTER_ROOT_NAME_TABLES = 'scm-view-filter-table-';

    /**
     * Naming convention for the table cell filters, helps to organize the code
     */
    const TABLE_COLUMN_SUFFIX = '//';

    /**
     * For tables about employees, the filters use this in the name
     */
    const EMPLOYEE_TABLE = 'employees';

    /**
     * Extra columns for the employees table. The format is the same as the example above for the columns.
     */
    const FILTER_EMPLOYEE_TABLE_HEADERS = self::FILTER_ROOT_NAME_TABLES.self::EMPLOYEE_TABLE;

    /**
     * Fills in cells for the extra headers for the employees
     *
     *  ```php
     *   Eventy::addFilter(Plugin::FILTER_EMPLOYEE_TABLE_COLUMN,
     *       function(string $html, string $column, \App\Models\Employee $model)
     *       {
     *           $item = '';
     *           if ($column === 'new-column') {
     *               return "my cool cell content"
     *           }
     *           return $html. "\n".$item;
     *       },
     *       5, 3);
     *  ```
     */

    const FILTER_EMPLOYEE_TABLE_COLUMN = self::FILTER_EMPLOYEE_TABLE_HEADERS.self::TABLE_COLUMN_SUFFIX;

    /**
     * For tables about active projects, the filters use this in the name
     */
    const ACTIVE_PROJECT_TABLE = 'active-project';
    /**
     * Extra columns for the active projects table. The format is the same as the example above for the columns.
     */
    const FILTER_ACTIVE_PROJECT_TABLE_HEADERS = self::FILTER_ROOT_NAME_TABLES.self::ACTIVE_PROJECT_TABLE;

    /**
     * Fills in cells for the extra headers for the active projects table
     *
     *  ```php
     *   Eventy::addFilter(Plugin::FILTER_ACTIVE_PROJECT_TABLE_COLUMN,
     *       function(string $html, string $column, \App\Models\Project $model)
     *       {
     *           $item = '';
     *           if ($column === 'new-column') {
     *               return "my cool cell content"
     *           }
     *           return $html. "\n".$item;
     *       },
     *       5, 3);
     *  ```
     */
    const FILTER_ACTIVE_PROJECT_TABLE_COLUMN = self::FILTER_ACTIVE_PROJECT_TABLE_HEADERS.self::TABLE_COLUMN_SUFFIX;


    /**
     * For tables about projects, the filters use this in the name
     */
    const PROJECT_TABLE = 'projects';

    /**
     * Extra columns for the projects table. The format is the same as the example above for the columns.
     */
    const FILTER_PROJECT_TABLE_HEADERS = self::FILTER_ROOT_NAME_TABLES.self::PROJECT_TABLE;


    /**
     * Fills in cells for the extra headers for the projects table
     *
     *  ```php
     *   Eventy::addFilter(Plugin::FILTER_PROJECT_TABLE_COLUMN,
     *       function(string $html, string $column, \App\Models\Project $model)
     *       {
     *           $item = '';
     *           if ($column === 'new-column') {
     *               return "my cool cell content"
     *           }
     *           return $html. "\n".$item;
     *       },
     *       5, 3);
     *  ```
     */
    const FILTER_PROJECT_TABLE_COLUMN = self::FILTER_PROJECT_TABLE_HEADERS.self::TABLE_COLUMN_SUFFIX;

    /**
     * For tables about expenses, the filters use this in the name
     */
    const EXPENSES_TABLE = 'expenses';
    /**
     * Extra columns for the expenses table. The format is the same as the example above for the columns.
     */
    const FILTER_EXPENSES_TABLE_HEADERS = self::FILTER_ROOT_NAME_TABLES.self::EXPENSES_TABLE;


    /**
     * Fills in cells for the extra headers for the expenses table
     *
     *  ```php
     *   Eventy::addFilter(Plugin::FILTER_EXPENSES_TABLE_COLUMN,
     *       function(string $html, string $column, \App\Models\ConstructionExpense $model)
     *       {
     *           $item = '';
     *           if ($column === 'new-column') {
     *               return "my cool cell content"
     *           }
     *           return $html. "\n".$item;
     *       },
     *       5, 3);
     *  ```
     */
    const FILTER_EXPENSES_TABLE_COLUMN = self::FILTER_EXPENSES_TABLE_HEADERS.self::TABLE_COLUMN_SUFFIX;

    /**
     * For tables about project transactions, the filters use this in the name
     */
    const PROJECT_TRANSACTIONS_TABLE = 'project-transactions';
    /**
     * Extra columns for the expenses table. The format is the same as the example above for the columns.
     */
    const FILTER_PROJECT_TRANSACTION_TABLE_HEADERS = self::FILTER_ROOT_NAME_TABLES.self::PROJECT_TRANSACTIONS_TABLE;


    /**
     * Fills in cells for the extra headers for the project transactions table
     *
     *  ```php
     *   Eventy::addFilter(Plugin::FILTER_PROJECT_TRANSACTION_TABLE_COLUMN,
     *       function(string $html, string $column, \App\Models\ConstructionExpense $model)
     *       {
     *           $item = '';
     *           if ($column === 'new-column') {
     *               return "my cool cell content"
     *           }
     *           return $html. "\n".$item;
     *       },
     *       5, 3);
     *  ```
     */

    const FILTER_PROJECT_TRANSACTION_TABLE_COLUMN = self::FILTER_PROJECT_TRANSACTION_TABLE_HEADERS.self::TABLE_COLUMN_SUFFIX;

    /**
     * For tables about invoices, the filters use this in the name
     */
    const INVOICE_TABLE = 'invoices';
    /**
     * Extra columns for the invoices table. The format is the same as the example above for the columns.
     */
    const FILTER_INVOICE_TABLE_HEADERS = self::FILTER_ROOT_NAME_TABLES.self::INVOICE_TABLE;



    /**
     * Fills in cells for the extra headers for the invoices table
     *
     *  ```php
     *   Eventy::addFilter(Plugin::FILTER_INVOICE_TABLE_COLUMN,
     *       function(string $html, string $column, \App\Models\Invoice $model)
     *       {
     *           $item = '';
     *           if ($column === 'new-column') {
     *               return "my cool cell content"
     *           }
     *           return $html. "\n".$item;
     *       },
     *       5, 3);
     *  ```
     */
    const FILTER_INVOICE_TABLE_COLUMN = self::FILTER_INVOICE_TABLE_HEADERS.self::TABLE_COLUMN_SUFFIX;

    /**
     * For tables about notifications, the filters use this in the name
     */
    const NOTIFICATION_TABLE = 'notifications';
    /**
     * Extra columns for the notification table. The format is the same as the example above for the columns.
     */
    const FILTER_NOTIFICATION_TABLE_HEADERS = self::FILTER_ROOT_NAME_TABLES.self::NOTIFICATION_TABLE;

    /**
     * Fills in cells for the extra headers for the notifications table
     *
     *  ```php
     *   Eventy::addFilter(Plugin::FILTER_NOTIFICATION_TABLE_COLUMN,
     *       function(string $html, string $column, \App\Models\Notification $model)
     *       {
     *           $item = '';
     *           if ($column === 'new-column') {
     *               return "my cool cell content"
     *           }
     *           return $html. "\n".$item;
     *       },
     *       5, 3);
     *  ```
     */
    const FILTER_NOTIFICATION_TABLE_COLUMN = self::FILTER_NOTIFICATION_TABLE_HEADERS.self::TABLE_COLUMN_SUFFIX;


    /**
     * For tables about project docs, the filters use this in the name
     */
    const PROJECT_DOCS_TABLE = 'project-docs';
    /**
     * Extra columns for the project documents table. The format is the same as the example above for the columns.
     */
    const FILTER_PROJECT_DOC_TABLE_HEADERS = self::FILTER_ROOT_NAME_TABLES.self::PROJECT_DOCS_TABLE;

    /**
     * Eventy::addFilter(Plugin::FILTER_PROJECT_DOC_TABLE_COLUMN, function(string $html, string $column, ProjectFile $project_file) {
     */
    const FILTER_PROJECT_DOC_TABLE_COLUMN = self::FILTER_PROJECT_DOC_TABLE_HEADERS.self::TABLE_COLUMN_SUFFIX;

    /**
     * For tables about project invoices, the filters use this in the name. The format is the same as the example above for the columns.
     */
    const PROJECT_INVOICES_TABLE = 'project-invoice';
    /**
     * Extra columns for the project invoices table
     */
    const FILTER_PROJECT_INVOICE_TABLE_HEADERS = self::FILTER_ROOT_NAME_TABLES.self::PROJECT_INVOICES_TABLE;


    /**
     * Fills in cells for the extra headers for the project invoices table
     *
     *  ```php
     *   Eventy::addFilter(Plugin::FILTER_PROJECT_INVOICE_TABLE_COLUMN,
     *       function(string $html, string $column, \App\Models\Invoice $model)
     *       {
     *           $item = '';
     *           if ($column === 'new-column') {
     *               return "my cool cell content"
     *           }
     *           return $html. "\n".$item;
     *       },
     *       5, 3);
     *  ```
     */
    const FILTER_PROJECT_INVOICE_TABLE_COLUMN = self::FILTER_PROJECT_INVOICE_TABLE_HEADERS.self::TABLE_COLUMN_SUFFIX;

    /**
     * For tables about admin users, the filters use this in the name. The format is the same as the example above for the columns.
     */
    const ADMIN_USERS_TABLE = 'admin-users';
    /**
     * Extra columns for the admin users table
     */
    const FILTER_USER_TABLE_HEADERS = self::FILTER_ROOT_NAME_TABLES.self::ADMIN_USERS_TABLE;


    /**
     * Fills in cells for the extra headers for the users table
     *
     *  ```php
     *   Eventy::addFilter(Plugin::FILTER_USER_TABLE_COLUMN,
     *       function(string $html, string $column, \App\Models\User $model)
     *       {
     *           $item = '';
     *           if ($column === 'new-column') {
     *               return "my cool cell content"
     *           }
     *           return $html. "\n".$item;
     *       },
     *       5, 3);
     *  ```
     */
    const FILTER_USER_TABLE_COLUMN = self::FILTER_USER_TABLE_HEADERS.self::TABLE_COLUMN_SUFFIX;

    /**
     * Lists all the table filters
     */
    const ALL_FILTER_TABLE_HEADERS = [

        self::FILTER_ACTIVE_PROJECT_TABLE_HEADERS => Project::class,
        self::FILTER_EMPLOYEE_TABLE_HEADERS => Employee::class,
        self::FILTER_EXPENSES_TABLE_HEADERS => ConstructionExpense::class,
        self::FILTER_INVOICE_TABLE_HEADERS => Invoice::class ,
        self::FILTER_NOTIFICATION_TABLE_HEADERS => Notification::class,
        self::FILTER_PROJECT_DOC_TABLE_HEADERS => ProjectFile::class,
        self::FILTER_PROJECT_INVOICE_TABLE_HEADERS => Invoice::class ,
        self::FILTER_PROJECT_TABLE_HEADERS => Project::class,
        self::FILTER_PROJECT_TRANSACTION_TABLE_HEADERS => ConstructionExpense::class,
        self::FILTER_USER_TABLE_HEADERS => User::class

    ];

}
