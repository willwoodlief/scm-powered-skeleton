<?php

namespace App\Plugins\EventConstants;

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

}
