<?php

namespace App\Plugins\EventConstants;

use App\Helpers\Projects\ProjectFile;
use App\Models\Company;
use App\Models\ConstructionExpense;
use App\Models\Contractor;
use App\Models\DailyLog;
use App\Models\DailyLogPhoto;
use App\Models\DeviceDetail;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Project;
use App\Models\ProjectAssign;
use App\Models\ToDo;
use App\Models\User;
use App\Models\UserLog;
use App\Models\UserRole;

/**
 * Model actions have 84 different actions. However these are combinations for each model (13 models) and 6 events. There are easy patterns
 *
 * The 6 actions are fired before and after a model is created, updated and deleted
 *
 * Each of the action callbacks takes the model as the single parameter
 *
 * You can form the action name by concatenating the action name with the action model name here, using the constants defined in this interface
 *
 * One role in the plugins for using this, is to coordinate their data with the main project data.
 *
 * Below is an example of an action for when an invoice is created. The invoice is passed as the argument, and because this is an action, nothing is returned
 *
 * ```php
 * Eventy::addAction(Plugin::ACTION_MODEL_CREATED.Plugin::ACTION_MODEL_INVOICE , function (Invoice $invoice)
 * {
 *      $invoice_attributes = new ScmPluginSampleInventory();
 *      $invoice_attributes->invoice_id = $invoice->id;
 *      $invoice_attributes->invoice_importance = ScmPluginSampleInventory::STATUS_NORMAL;
 *      $invoice_attributes->invoice_color_code = ScmPluginSampleInventory::COLOR_CODES[ScmPluginSampleInventory::STATUS_NORMAL];
 *      $invoice_attributes->save();
 * }, 10, 1);
 * ```
 *
 * All of the 83 other actions are very similar
 *
 */
interface ModelActions {


    /**
     * fired after the model is deleted. The model is gone from the database.
     */
    const ACTION_MODEL_DELETED =  "scm-model-lifecycle-deleted-";

    /**
     * fired before the model is deleted. The model still exists in the database. Its resources have not been deleted yet.
     */
    const ACTION_MODEL_DELETING =  "scm-model-lifecycle-deleting-";

    /**
     * fire after the model is created, the db row is already created
     */
    const ACTION_MODEL_CREATED =  "scm-model-lifecycle-created-";


    /**
     * fired before the model is created, the model does not yet exist in the db
     */
    const ACTION_MODEL_CREATING =  "scm-model-lifecycle-creating-";

    /**
     * Fired before data in an already created model is changed
     */
    const ACTION_MODEL_UPDATED =  "scm-model-lifecycle-updated-";

    /**
     * Fired after the data in an already existing model is changed
     */
    const ACTION_MODEL_UPDATING =  "scm-model-lifecycle-updating-";

    /**
     * Lists all the different action types
     */
    const ALL_BASE_ACTIONS_FOR_MODELS = [
        self::ACTION_MODEL_DELETED,
        self::ACTION_MODEL_DELETING,
        self::ACTION_MODEL_CREATED,
        self::ACTION_MODEL_CREATING,
        self::ACTION_MODEL_UPDATED,
        self::ACTION_MODEL_UPDATING,
    ];


    /**
     * filter name for the user role model
     * @see UserRole
     */
    const ACTION_MODEL_NAME_USER_ROLE = "user_role";

    /**
     * filter name for the User model
     * @see User
     */
    const ACTION_MODEL_NAME_USER = "user";

    /**
     * filter name for the User Log model
     * @see UserLog
     */
    const ACTION_MODEL_NAME_USER_LOG = "user-log";

    /**
     * filter name for the To-do list model
     * @see ToDo
     */
    const ACTION_MODEL_NAME_TODO = "todo";

    /**
     * filter name for the project assign model
     * @see ProjectAssign
     */
    const ACTION_MODEL_PROJECT_ASSIGN = "project-assign";

    /**
     * filter name for the project model
     * @see Project
     */
    const ACTION_MODEL_PROJECT = "project";

    /**
     * filter name for the notification model
     * @see Notification
     */
    const ACTION_MODEL_NOTIFICATION = "notification";

    /**
     * filter name for the invoice model
     * @see Invoice
     */
    const ACTION_MODEL_INVOICE = "invoice";

    /**
     * filter name for the employee model
     * @see Employee
     */
    const ACTION_MODEL_EMPLOYEE = "employee";

    /**
     * filter name for the device detail model
     * @see DeviceDetail
     */
    const ACTION_MODEL_DEVICE_DETAIL = "device-detail";

    /**
     * filter name for the Daily log photo model
     * @see DailyLogPhoto
     */
    const ACTION_MODEL_DAILY_LOG_PHOTO = "daily-log-photo";

    /**
     * filter name for the daily log model
     * @see DailyLog
     */
    const ACTION_MODEL_DAILY_LOG = "daily-log";

    /**
     * filter name for the contractor model
     * @see Contractor
     */
    const ACTION_MODEL_CONTRACTOR = "contractor";

    /**
     * filter name for the construction expense model
     * @see ConstructionExpense
     */
    const ACTION_MODEL_CONSTRUCTION_EXPENSE = "construction-expense";

    /**
     * filter name for the company model
     * @see Company
     */
    const ACTION_MODEL_COMPANY = "company";

    /**
     * Lists all the model names used in these actions
     */
    const ALL_MODEL_ACTION_NAMES = [
        UserRole::class => self::ACTION_MODEL_NAME_USER_ROLE,
        User::class => self::ACTION_MODEL_NAME_USER,
        UserLog::class => self::ACTION_MODEL_NAME_USER_LOG,
        ToDo::class => self::ACTION_MODEL_NAME_TODO,
        ProjectAssign::class => self::ACTION_MODEL_PROJECT_ASSIGN,
        Project::class => self::ACTION_MODEL_PROJECT,
        Notification::class => self::ACTION_MODEL_NOTIFICATION,
        Invoice::class => self::ACTION_MODEL_INVOICE,
        Employee::class => self::ACTION_MODEL_EMPLOYEE,
        DeviceDetail::class => self::ACTION_MODEL_DEVICE_DETAIL,
        DailyLogPhoto::class => self::ACTION_MODEL_DAILY_LOG_PHOTO,
        DailyLog::class => self::ACTION_MODEL_DAILY_LOG,
        Contractor::class => self::ACTION_MODEL_CONTRACTOR,
        ConstructionExpense::class => self::ACTION_MODEL_CONSTRUCTION_EXPENSE,
        Company::class => self::ACTION_MODEL_COMPANY,
    ];


    /**
     * Action called when an invoice has a payment applied to it in the webpage
     *
     * ```php
     * Eventy::addAction(Plugin::ACTION_INVOICE_PAYMENT, function( Invoice $invoice,float $amount_paid) {
     *      Log::debug("This was paid",['invoice'=>$invoice->toArray(),'amount_paid'=>$amount_paid]);
     * }, 20, 2);
     * ```
     */
    //individual but has invoice passed
    const ACTION_INVOICE_PAYMENT = "scm-invoice-payment";

    /**
     * Project files can have different sources and ways of storage and downloading and deleting
     *
     * When a file is first uploaded, the action of ACTION_ON_UPLOADED_PROJECT_FILE is called
     *
     * Before a file is deleted, the action of ACTION_BEFORE_DELETING_PROJECT_FILE is called
     *
     * When a list of project files will be displayed, there are a few filters and actions
     *
     * First an action of ACTION_START_DISCOVERY_PROJECT_FILES is called, in case there needs to be any resources or outside calls made to set up the views.
     *
     * Then, for each project file found by the core code, a FILTER_DISCOVER_PROJECT_FILE is called, if a plugin decides this should not be shown, it can return null, also the ProjectFile object can be replaced by a extended class of it to provide more control over the behavior.
     *
     * After the core has discovered all its files, the filter of FILTER_APPEND_PROJECT_FILES is called, this allows any plugin to provide extra files not added to already by the core
     *
     * Finally, after all the files have been added, the filter of FILTER_SORT_PROJECT_FILES is called, which allows the core and plugin project files to be sorted in the view
     *
     * Then, in the project file blade, the table for display can have extra columns added, using the table filters for the project files
     *
     * -----
     *
     * The project files can handle and display many sources of files, stored in different ways and managed by different plugins, at the same time
     *
     * The key to this is for files that are not managed by the core, to create a new class that inherits from the \App\Helpers\Projects\ProjectFile
     *
     *  When calling the parent constructor, Derived classes that are handling resources not on the file system,
     *  should pass in null for the first two params : but pass in the file extension for the third
     *
     *  Then then derived class can fill in the file_name, which is for display purposes, and should not include the extension (so cat and not cat.gif),
     *  the file size (in mb), the unix timestamp for the file date, a human readable date/time string
     *
     *  If the derived class is storing the file outside of the website's regular upload file directory, then it should also overload the function of getPublicFilePath()
     *
     *  A derived class should also overload the deleteProjectFile()
     *
     *  When its time for the file to be deleted, either by user action or when a project is deleted, then the class's deleteProjectFile will be called
     *
     *  When the derived classes are added to the project file list via the filter of FILTER_DISCOVER_PROJECT_FILE (replacing an original ProjectFile)
     *  Or when appending new classes that inherit from ProjectFile, then the files will be able to be managed and sorted by the core code,
     *  And the different file types will have their delete methods called when the project file is removed from the project
     *
     *
     *
     */
    const ALL_PROJECT_FILE_EVENTS = [
        self::FILTER_DISCOVER_PROJECT_FILE,
        self::ACTION_START_DISCOVERY_PROJECT_FILES,
        self::FILTER_APPEND_PROJECT_FILES,
        self::FILTER_SORT_PROJECT_FILES,

        self::ACTION_ON_UPLOADED_PROJECT_FILE,
        self::ACTION_BEFORE_DELETING_PROJECT_FILE,
    ];


    /**
     * This action is fired before any of the project files are discovered. It can help set up resources needed to edit the file list or augment the list
     *
     *  ```php
     *  Eventy::addAction(Plugin::ACTION_START_DISCOVERY_PROJECT_FILES, function( \App\Models\Project $project):void {
     *       Log::debug("Project starting file discovery",['project'=>$project->toArray()]);
     *  }, 20, 2);
     *  ```
     *
     */
    const ACTION_START_DISCOVERY_PROJECT_FILES = "scm-action-start-discovery-project-files";

    /**
     * Filter called when a file is discovered for the Project, before showing the list of files
     * To remove this file from being seen, return null in the filter
     * Alternatively replace the object
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_DISCOVER_PROJECT_FILE, function( \App\Helpers\Projects\ProjectFile $project_file): ?\App\Helpers\Projects\ProjectFile {
     *      Log::debug("Project file found",['project_file'=>$project_file->toArray()]);
     *      return $project_file
     * }, 20, 2);
     * ```
     */
    const FILTER_DISCOVER_PROJECT_FILE = "scm-filter-discover-project-file";


    /**
     * Filter called to add more project files than the core code finds at first
     * returns an array of project files (these can be sorted with the original list afterwards)
     * It can be one or many appended
     * ```php
     * Eventy::addFilter(Plugin::FILTER_APPEND_PROJECT_FILES, function( array $extra_project_files ): array {
     *      $extra_project_files[] = new ChildOfProjectFileClass($some_initializing_data);
     *      return $extra_project_files
     * }, 20, 2);
     * ```
     */
    const FILTER_APPEND_PROJECT_FILES = "scm-filter-append-project-files";

    /**
     * Filter called sort all the project files before they are displayed, in case these need to be put in a different order
     * returns an array of project files that are displayed in the order of the array
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_SORT_PROJECT_FILES, function( array $all_project_files ): array {
     *      //do some sorting on the array $all_project_files
     *      return $extra_project_files
     * }, 20, 2);
     * ```
     */
    const FILTER_SORT_PROJECT_FILES = "scm-filter-sort-project-files";


    /**
     * This action is fired after a new project file is uploaded and saved to disk. The parameter is the full file path of the already saved new file
     *
     *  ```php
     *  Eventy::addAction(Plugin::ACTION_ON_UPLOADED_PROJECT_FILE, function( string $full_file_path):void {
     *       Log::debug("new file for project added, here is the path",['full_file_path'=>$full_file_path]);
     *  }, 20, 2);
     *  ```
     *
     */
    const ACTION_ON_UPLOADED_PROJECT_FILE = "scm-action-on-uploaded-project-file";


    /**
     * This action is fired before a project file is deleted. The parameter is the ProjectFile class or derived class. Derived classes need to delete themselves if they do not store on the local disk, so should delete those resources here or when overriding the ProjectFile::deleteProjectFile method. Files are deleted by a user, or when a project is deleted
     *
     *  ```php
     *  Eventy::addAction(Plugin::ACTION_BEFORE_DELETING_PROJECT_FILE, function( \App\Helpers\Projects\ProjectFile $project_file):void {
     *       Log::debug("file for project about to be deleted",['project_file'=>$project_file->toArray()]);
     *  }, 20, 2);
     *  ```
     *
     */
    const ACTION_BEFORE_DELETING_PROJECT_FILE = "scm-action-before-deleting-project-file";

}
