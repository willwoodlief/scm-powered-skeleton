<?php

namespace App\Http\Controllers;

use App\Exceptions\UserException;
use App\Http\Requests\InvoiceSaveRequest;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\UserLog;
use App\Plugins\Plugin;
use Illuminate\Http\Request;
use TorMorten\Eventy\Facades\Eventy;

class InvoiceController extends Controller {
    public function index()
    {
        $invoices = Invoice::getAllInvoices();

        $all_projects = Project::getAllProjects();

        return view('invoices.invoice_index')->with('invoices',$invoices)->with('all_projects',$all_projects);
    }

    public function new_invoice()
    {
        $invoice = new Invoice();

        $all_projects = Project::getAllProjects();
        return view('invoices.invoice_new',compact('invoice','all_projects'));
    }

    /**
     * @param InvoiceSaveRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function create_invoice(InvoiceSaveRequest $request) {
        $invoice = new Invoice();
        $invoice->fill($request->validated());
        $invoice->status = Invoice::STATUS_OPEN;
        $invoice->payment = $invoice->amount;
        $invoice->file = '';
        $invoice->amount = (int)round($invoice->amount);
        $invoice->save();

        if ($request->hasFile('file')) {
            $invoice->process_new_file($request->file('file'));
        }
        UserLog::addLog(sprintf("Added invoice # %s ",$invoice->id));
        if ($request->ajax()) {
            return response()->json(['success'=>true,'invoice'=>$invoice]);
        } else {
            return redirect()->back();
        }
    }

    public function pay_invoice(int $invoice_id, Request $request) {
        /**
         * @var Invoice $invoice
         */
        $invoice =  Invoice::where("id",$invoice_id)
            /** @uses Invoice::invoice_project() */
            ->with('invoice_project')
            ->first();

        if (!$invoice) {
            throw new UserException(__("Invoice not found"));
        }

        $payment = floatval($request->request->get('payment'));
        $invoice->payment = $invoice->payment - $payment;
        $invoice->save();
        $invoice = $invoice->refresh();
        if ($invoice->payment < 0.0001) {
            $invoice->status = Invoice::STATUS_PAID;
            $invoice->save();
        }
        UserLog::addLog(sprintf("Added invoice payment of %s to # %s ",$payment,$invoice->id));

        Eventy::action(Plugin::ACTION_INVOICE_PAYMENT, $invoice,$payment);

        if ($request->ajax()) {
            return response()->json(['success'=>true,'invoice'=>$invoice]);
        } else {
            return redirect()->back();
        }
    }


    public function delete_invoice(int $invoice_id, Request $request) {

        /**
         * @var Invoice $invoice
         */
        $invoice =  Invoice::where("id",$invoice_id)
            /** @uses Invoice::invoice_project() */
            ->with('invoice_project')
            ->first();

        if (!$invoice) {
            throw new UserException(__("Invoice not found"));
        }
        $invoice->delete();

        if ($request->ajax()) {
            return response()->json(['success'=>true,'invoice'=>$invoice]);
        } else {
            return redirect()->back();
        }
    }

}
