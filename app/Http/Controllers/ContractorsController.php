<?php

namespace App\Http\Controllers;

use App\Exceptions\UserException;
use App\Http\Requests\ContractorSaveRequest;
use App\Models\Contractor;
use App\Plugins\Plugin;
use App\Providers\RouteServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use TorMorten\Eventy\Facades\Eventy;


class ContractorsController extends Controller {
    public function index()
    {

        $contractors = Contractor::getAllContractors();

        return view('contractors.contractor_index')->with(compact('contractors'));
    }

    public function view_contractor(int $contractor_id)
    {
        $start_contractor = Contractor::find($contractor_id);
        if (!$start_contractor) {
            throw new UserException(__("Contractor not found in view"));
        }

        $query = function() use($contractor_id,$start_contractor) : Builder {
            $query = Contractor::where("id",$contractor_id);
            return Eventy::filter(Plugin::FILTER_QUERY_CONTRACTOR_FOR_VIEW, $query,$start_contractor);
        };
        $contractor = $query()->first();
        if (!$contractor) {
            throw new UserException(__("Contractor not found"));
        }
        return view('contractors.view_contractor',compact('contractor'));
    }

    public function add_contractor_form()
    {
        $contractor = new Contractor();
        return view('contractors.new_contractor',compact('contractor'));
    }

    /**
     * @param ContractorSaveRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function create_contractor(ContractorSaveRequest $request)
    {
        $contractor = new Contractor();
        $contractor->fill($request->validated());


        if ($request->hasFile('profile_picture')) {
            $contractor->process_uploaded_image($request->file('profile_picture'));
        } else {
            $contractor->logo = '';
        }
        $contractor->save();
        return redirect()->intended(RouteServiceProvider::CONTRACTORS);
    }

    public function edit_contractor(int $contractor_id)
    {
        $start_contractor = Contractor::find($contractor_id);
        if (!$start_contractor) {
            throw new UserException(__("Contractor not found in edit"));
        }

        $query = function() use($contractor_id,$start_contractor) : Builder {
            $query = Contractor::where("id",$contractor_id);
            return Eventy::filter(Plugin::FILTER_QUERY_CONTRACTOR_FOR_EDIT, $query,$start_contractor);
        };
        $contractor = $query()->first();
        if (!$contractor) {
            throw new UserException(__("Contractor not found"));
        }
        return view('contractors.edit_contractor',compact('contractor'));
    }

    /**
     * @param int $contractor_id
     * @param ContractorSaveRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update_contractor(int $contractor_id,ContractorSaveRequest $request)
    {
        $contractor =  Contractor::where("id",$contractor_id)->first();
        if (!$contractor) {
            throw new UserException(__("Contractor not found"));
        }
        $contractor->fill($request->validated());

        if ($request->hasFile('profile_picture')) {
            $contractor->process_uploaded_image($request->file('profile_picture'));
        }

        $contractor->save();
        return redirect()->intended(RouteServiceProvider::CONTRACTORS);
    }

    public function delete_contractor(int $contractor_id) {
        $contractor =  Contractor::where("id",$contractor_id)->first();
        if (!$contractor) {
            throw new UserException(__("Contractor not found"));
        }
        $contractor->cleanup_contractor_resources();
        $contractor->delete();
    }
}
