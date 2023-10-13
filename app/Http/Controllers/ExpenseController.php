<?php

namespace App\Http\Controllers;

use App\Exceptions\UserException;
use App\Http\Requests\ProjectExpenseSaveRequest;
use App\Models\ConstructionExpense;
use App\Models\Project;
use App\Models\UserLog;
use App\Plugins\Plugin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use TorMorten\Eventy\Facades\Eventy;

class ExpenseController extends Controller {
    public function index()
    {
        $expenses = ConstructionExpense::getAllConstructionExpenses();

        $all_projects = Project::getAllProjects();

        return view('expenses.expense_index')->with('expenses',$expenses)->with('all_projects',$all_projects);
    }


    public function new_expense()
    {
        $expense = new ConstructionExpense();

        $all_projects = Project::getAllProjects();
        return view('expenses.expense_new',compact('expense','all_projects'));
    }

    public function create_expense(ProjectExpenseSaveRequest $request) {
        $expense = new ConstructionExpense();
        $expense->fill($request->validated());
        $expense->save();
        UserLog::addLog(sprintf("Added transaction # %s ",$expense->id));
        if ($request->ajax()) {
            return response()->json(['success'=>true,'expense'=>$expense]);
        } else {
            return redirect()->route('transactions');
        }
    }


    public function edit_expense(int $expense_id)
    {

        $start_expense = ConstructionExpense::find($expense_id);

        $query = function() use($expense_id,$start_expense) : Builder {
            $query = ConstructionExpense::where("id",$expense_id)
                /**  @uses ConstructionExpense::expense_project()*/
                ->with('expense_project');
            return Eventy::filter(Plugin::FILTER_QUERY_EXPENSE_FOR_EDIT, $query,$start_expense);
        };

        $expense = $query()->first();

        if (!$expense) {
            throw new UserException(__("Expense not found: $expense_id"));
        }

        $all_projects = Project::getAllProjects();
        return view('expenses.expense_edit',compact('expense','all_projects'));
    }

    public function update_expense(int $expense_id,ProjectExpenseSaveRequest $request) {
        /**
         * @var ConstructionExpense $expense
         */
        $expense =  ConstructionExpense::where("id",$expense_id)
            /**  @uses ConstructionExpense::expense_project()*/
            ->with('expense_project')
            ->first();
        if (!$expense) {
            throw new UserException(__("Expense not found: $expense_id"));
        }
        $expense->fill($request->validated());
        $expense->save();
        UserLog::addLog(sprintf("Edited transaction # %s ",$expense->id));
        if ($request->ajax()) {
            return response()->json(['success'=>true,'expense'=>$expense]);
        } else {
            return redirect()->route('transactions');
        }
    }

    public function delete_expense(int $expense_id, Request $request) {
        $expense =  ConstructionExpense::where("id",$expense_id)
            /**  @uses ConstructionExpense::expense_project()*/
            ->with('expense_project')
            ->first();
        if (!$expense) {
            throw new UserException(__("Expense not found: $expense_id"));
        }
        $expense->delete();

        if ($request->ajax()) {
            return response()->json(['success'=>true,'message'=>"Deleted expense",'expense'=>$expense]);
        } else {
            return redirect()->back();
        }
    }

}
