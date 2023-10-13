<?php

namespace App\Plugins\EventConstants;

use App\Models\ConstructionExpense;
use App\Models\Contractor;
use App\Models\DailyLog;
use App\Models\Employee;
use App\Models\Project;
use App\Models\User;

/**
 * Filters for Queries allow the plugins to change the results and data showing on all the pages. Each filter takes as first arg a Illuminate\Database\Eloquent\Builder which it can modify, sometimes it has a second arg which helps given more context to the query. Each of these filters must return a Builder
 *
 * Some work flows would enable extra data for plugin pages, that show in the page this query is for
 *
 * Other work flow would be to trigger side work, unrelated to the query
 *
 * Note: these are for displaying data, or doing something when certain data is displayed to a page. If you need to act on data changes, then use the ModelActions hooks
 *
 *
 * example:
 *
 *  ```php
 *  Eventy::addFilter(Plugin::FILTER_QUERY_DAILY_LOG_WRITER, function( Illuminate\Database\Eloquent\Builder $query, \App\Models\DailyLog $daily_log)  {
 *       Utilities::markUnusedVar($relative_image_path);
 *       return ScmSampleTheming::getPluginRef()->getResourceUrl('images/loading-coffee.gif');
 *
 *  }, 20, 1);
 *  ```
 */
interface FilterQuery {
    /**
     * Getting the writer for a daily log
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_DAILY_LOG_WRITER, function(Illuminate\Database\Eloquent\Builder $query,\App\Models\DailyLog $daily_log) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_DAILY_LOG_WRITER,['$query->sql'=>$query->toSql(),'$daily_log->id'=>$daily_log->id]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_DAILY_LOG_WRITER = "scm-get-daily-log-writer:query";

    /**
     * Getting the users to show in the admin panel to edit them
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_USERS_IN_ADMIN_LIST, function(Illuminate\Database\Eloquent\Builder $query) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_USERS_IN_ADMIN_LIST,['$query->sql'=>$query->toSql(),]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_USERS_IN_ADMIN_LIST = "scm-get-users-in-admin-list:query";

    /**
     * Getting the user for an admin to edit it
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_USER_FOR_EDIT, function(Illuminate\Database\Eloquent\Builder $query,\App\Models\User $selected_user) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_USER_FOR_EDIT,['$query->sql'=>$query->toSql(),'$selected_user->id'=>$selected_user->id]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_USER_FOR_EDIT = "scm-get-user-for-edit:query";

    /**
     * Getting the to-do list for the user
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_TODO_FOR_USER, function(Illuminate\Database\Eloquent\Builder $query,\App\Models\User $selected_user) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_TODO_FOR_USER,['$query->sql'=>$query->toSql(),'$selected_user->id'=>$selected_user->id]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_TODO_FOR_USER = "scm-get-todo-for-user:query";

    /**
     * Getting the projects that start this month
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_PROJECTS_STARTING_THIS_MONTH, function(Illuminate\Database\Eloquent\Builder $query) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_PROJECTS_STARTING_THIS_MONTH,['$query->sql'=>$query->toSql(),]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_PROJECTS_STARTING_THIS_MONTH = "scm-get-projects-starting-this-month:query";

    /**
     * Getting the assigns for a project
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_PROJECT_ASSIGNS, function(Illuminate\Database\Eloquent\Builder $query,\App\Models\Project $selected_project) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_PROJECT_ASSIGNS,['$query->sql'=>$query->toSql(),'$selected_project->id'=>$selected_project->id]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_PROJECT_ASSIGNS = "scm-get-project-assigns:query";


    /**
     * Getting all the projects
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_PROJECTS, function(Illuminate\Database\Eloquent\Builder $query) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_PROJECTS,['$query->sql'=>$query->toSql(),]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_PROJECTS = "scm-get-projects:query";

    /**
     * Getting all the active projects
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_ACTIVE_PROJECTS, function(Illuminate\Database\Eloquent\Builder $query) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_ACTIVE_PROJECTS,['$query->sql'=>$query->toSql(),]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_ACTIVE_PROJECTS = "scm-get-active-projects:query";

    /**
     * Getting the project for an edit
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_PROJECT_FOR_EDIT, function(Illuminate\Database\Eloquent\Builder $query,\App\Models\Project $selected_project) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_PROJECT_FOR_EDIT,['$query->sql'=>$query->toSql(),'$selected_project->id'=>$selected_project->id]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_PROJECT_FOR_EDIT = "scm-get-project-for-edit:query";



    /**
     * Getting all the foremen for a project
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_PROJECT_FOREMEN, function(Illuminate\Database\Eloquent\Builder $query,\App\Models\Project $selected_project) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_PROJECT_FOREMEN,['$query->sql'=>$query->toSql(),'$selected_project->id'=>$selected_project->id]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_PROJECT_FOREMEN = "scm-get-project-foremen:query";

    /**
     * Getting the project for a view
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_PROJECT_FOR_VIEW, function(Illuminate\Database\Eloquent\Builder $query,\App\Models\Project $selected_project) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_PROJECT_FOR_VIEW,['$query->sql'=>$query->toSql(),'$selected_project->id'=>$selected_project->id]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_PROJECT_FOR_VIEW = "scm-get-project-for-view:query";


    /**
     * Getting the expense data for a project
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_EXPENSE_SUMS, function(Illuminate\Database\Eloquent\Builder $query,\App\Models\Project $selected_project) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_EXPENSE_SUMS,['$query->sql'=>$query->toSql(),'$selected_project->id'=>$selected_project->id]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_EXPENSE_SUMS = "scm-get-expense-sums:query";

    /**
     * Getting all the notifications
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_NOTIFICATIONS, function(Illuminate\Database\Eloquent\Builder $query) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_NOTIFICATIONS,['$query->sql'=>$query->toSql(),]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_NOTIFICATIONS = "scm-get-notifications:query";

    /**
     * Getting all the invoices
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_INVOICES, function(Illuminate\Database\Eloquent\Builder $query) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_INVOICES,['$query->sql'=>$query->toSql(),]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_INVOICES = "scm-get-invoices:query";

    /**
     * Getting all the employee birthdays
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_EMPLOYEE_BIRTHDAYS, function(Illuminate\Database\Eloquent\Builder $query) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_EMPLOYEE_BIRTHDAYS,['$query->sql'=>$query->toSql(),]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_EMPLOYEE_BIRTHDAYS = "scm-employee-get-birthdays:query";



    /**
     * Getting all the employees
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_EMPLOYEES, function(Illuminate\Database\Eloquent\Builder $query) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_EMPLOYEES,['$query->sql'=>$query->toSql(),]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_EMPLOYEES = "scm-get-employees:query";

    /**
     * Getting all the foremen
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_FOREMEN, function(Illuminate\Database\Eloquent\Builder $query) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_FOREMEN,['$query->sql'=>$query->toSql(),]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_FOREMEN = "scm-get-foremen:query";

    /**
     * Getting all the project managers
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_PROJECT_MANAGERS, function(Illuminate\Database\Eloquent\Builder $query) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_PROJECT_MANAGERS,['$query->sql'=>$query->toSql(),]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_PROJECT_MANAGERS = "scm-get-project-managers:query";

    /**
     * Getting an employee to edit
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_EMPLOYEE_FOR_EDIT, function(Illuminate\Database\Eloquent\Builder $query,\App\Models\Employee $selected_employee) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_EMPLOYEE_FOR_EDIT,['$query->sql'=>$query->toSql(),'$selected_employee->id'=>$selected_employee->id]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_EMPLOYEE_FOR_EDIT = "scm-get-employee-for-edit:query";

    /**
     * Getting an employee to edit
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_EMPLOYEE_FOR_VIEW, function(Illuminate\Database\Eloquent\Builder $query,\App\Models\Employee $selected_employee) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_EMPLOYEE_FOR_VIEW,['$query->sql'=>$query->toSql(),'$selected_employee->id'=>$selected_employee->id]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_EMPLOYEE_FOR_VIEW = "scm-get-employee-for-view:query";

    /**
     * Getting the last N project logs
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_LAST_USER_LOGS, function(Illuminate\Database\Eloquent\Builder $query) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_LAST_USER_LOGS,['$query->sql'=>$query->toSql(),]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_LAST_USER_LOGS = "scm-get-last-user-logs:query";

    /**
     * Getting all the contractors
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_CONTRACTORS, function(Illuminate\Database\Eloquent\Builder $query) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_CONTRACTORS,['$query->sql'=>$query->toSql(),]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_CONTRACTORS = "scm-get-contractors:query";

    /**
     * Getting an employee to view
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_CONTRACTOR_FOR_VIEW, function(Illuminate\Database\Eloquent\Builder $query,\App\Models\Contractor $selected_contractor) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_CONTRACTOR_FOR_VIEW,['$query->sql'=>$query->toSql(),'$selected_contractor->id'=>$selected_contractor->id]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_CONTRACTOR_FOR_VIEW = "scm-get-contractor-for-view:query";

    /**
     * Getting an employee to edit
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_CONTRACTOR_FOR_EDIT, function(Illuminate\Database\Eloquent\Builder $query,\App\Models\Contractor $selected_contractor) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_CONTRACTOR_FOR_EDIT,['$query->sql'=>$query->toSql(),'$selected_contractor->id'=>$selected_contractor->id]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_CONTRACTOR_FOR_EDIT = "scm-get-contractor-for-edit:query";



    /**
     * Getting all the expenses
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_EXPENSES, function(Illuminate\Database\Eloquent\Builder $query) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_EXPENSES,['$query->sql'=>$query->toSql(),]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_EXPENSES = "scm-get-construction-expenses:query";

    /**
     * Getting a construction expense to edit
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_QUERY_EXPENSE_FOR_EDIT, function(Illuminate\Database\Eloquent\Builder $query,\App\Models\ConstructionExpense $selected_expense) : Builder {
     *      Log::debug(Plugin::FILTER_QUERY_EXPENSE_FOR_EDIT,['$query->sql'=>$query->toSql(),'$selected_expense->id'=>$selected_expense->id]);
     *      //change, add to, or replace the query here
     *      return $query;
     * }, 10, 2);
     * ```
     */
    const FILTER_QUERY_EXPENSE_FOR_EDIT = "scm-get-expense-for-edit:query";

    /**
     * Lists all the filters for queries: the values are the expected extra argument types for that fitler
     */
    const ALL_FILTER_QUERIES = [
      self::FILTER_QUERY_DAILY_LOG_WRITER => [DailyLog::class],
      self::FILTER_QUERY_USERS_IN_ADMIN_LIST => [] ,
      self::FILTER_QUERY_USER_FOR_EDIT  => [User::class],
      self::FILTER_QUERY_TODO_FOR_USER => [User::class],
      self::FILTER_QUERY_PROJECTS_STARTING_THIS_MONTH =>[],
      self::FILTER_QUERY_PROJECT_ASSIGNS => [Project::class],
      self::FILTER_QUERY_PROJECTS => [],
      self::FILTER_QUERY_ACTIVE_PROJECTS => [],
      self::FILTER_QUERY_PROJECT_FOR_EDIT => [Project::class],
      self::FILTER_QUERY_NOTIFICATIONS => [],
      self::FILTER_QUERY_PROJECT_FOR_VIEW => [Project::class],
      self::FILTER_QUERY_INVOICES => [],
      self::FILTER_QUERY_EMPLOYEE_BIRTHDAYS => [],
      self::FILTER_QUERY_PROJECT_FOREMEN => [Project::class],
      self::FILTER_QUERY_EMPLOYEES => [],
      self::FILTER_QUERY_FOREMEN => [],
      self::FILTER_QUERY_PROJECT_MANAGERS => [],
      self::FILTER_QUERY_EMPLOYEE_FOR_EDIT => [Employee::class],
      self::FILTER_QUERY_EMPLOYEE_FOR_VIEW => [Employee::class],
      self::FILTER_QUERY_LAST_USER_LOGS => [],
      self::FILTER_QUERY_CONTRACTORS => [],
      self::FILTER_QUERY_CONTRACTOR_FOR_VIEW => [Contractor::class],
      self::FILTER_QUERY_CONTRACTOR_FOR_EDIT => [Contractor::class],
      self::FILTER_QUERY_EXPENSE_SUMS => [Project::class],
      self::FILTER_QUERY_EXPENSES => [],
      self::FILTER_QUERY_EXPENSE_FOR_EDIT => [ConstructionExpense::class],
    ];
}

