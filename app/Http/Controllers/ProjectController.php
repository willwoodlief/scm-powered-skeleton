<?php

namespace App\Http\Controllers;

use App\Exceptions\UserException;
use App\Helpers\Utilities;
use App\Http\Requests\ProjectFileRequest;
use App\Http\Requests\ProjectSaveRequest;
use App\Models\Contractor;
use App\Models\DailyLog;
use App\Models\DailyLogPhoto;
use App\Models\Employee;
use App\Models\Project;
use App\Models\User;
use App\Models\UserLog;
use App\Plugins\Plugin;
use App\Providers\RouteServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use TorMorten\Eventy\Facades\Eventy;


class ProjectController extends Controller {


    public function index()
    {

        $projects = Project::getAllProjects();
        $contractors = Contractor::getAllContractors();
        $project_managers = Employee::getAllProjectManagers();
        $foremen = Employee::getAllForemen();

        return view('projects.project_index',compact('projects','contractors','project_managers','foremen'));
    }

    public function new_project_form()
    {
        $project = new Project();
        $contractors = Contractor::getAllContractors();
        $project_managers = Employee::getAllProjectManagers();
        $foremen = Employee::getAllForemen();
        return view('projects.new_project',compact('project','contractors','project_managers','foremen'));
    }

    /**
     * @param ProjectSaveRequest $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function create_project(ProjectSaveRequest $request)
    {
        $project = new Project();
        $project->fill($request->validated());

        $project->status = Project::STATUS_NOT_STARTED;
        $given_foremen = $request->request->all('foreman');
        if (empty($given_foremen)) {$given_foremen = [];}

        $project->setForemen($given_foremen);

        $project->save();
        UserLog::addLog(sprintf("Added project %s", $project->project_name) );
        return redirect()->intended(RouteServiceProvider::PROJECTS);
    }

    public function edit_project(int $project_id)
    {

        $start_project = Project::find($project_id);
        if (!$start_project) {
            throw new UserException(__("Project not found"));
        }

        $query = function() use($project_id,$start_project) : Builder {
            $query = Project::where("id",$project_id)
                /**  @uses Project::project_contractor()*/
                ->with('project_contractor')

                /**  @uses Project::project_manager()*/
                ->with('project_manager');
            return Eventy::filter(Plugin::FILTER_QUERY_PROJECT_FOR_EDIT, $query,$start_project);
        };

        $project = $query()->first();

        if (!$project) {
            throw new UserException(__("Project not found"));
        }
        $contractors = Contractor::getAllContractors();
        $project_managers = Employee::getAllProjectManagers();
        $foremen = Employee::getAllForemen();

        return view('projects.edit_project',compact('project','contractors','project_managers','foremen'));
    }

    public function view_project(int $project_id)
    {

        $start_project = Project::find($project_id);
        if (!$start_project) {
            throw new UserException(__("Project not found"));
        }
        $query = function() use($project_id,$start_project) : Builder {
            $query = Project::where("id",$project_id)
                /**  @uses Project::project_expenses()*/
                ->with('project_expenses')

                /**  @uses Project::project_invoices()*/
                ->with('project_invoices')

                /**  @uses Project::project_contractor()*/
                ->with('project_contractor')

                /**  @uses Project::project_manager()*/
                ->with('project_manager')

                /**  @uses Project::project_logs()*/
                ->with('project_logs')

                /**  @uses Project::project_assigns()*/
                ->with('project_assigns');
            return Eventy::filter(Plugin::FILTER_QUERY_PROJECT_FOR_VIEW, $query,$start_project);
        };

        $project = $query()->first();

        if (!$project) {
            throw new UserException(__("Project not found"));
        }
        return view('projects.project_view',compact('project'));
    }

    /**
     * @param int $project_id
     * @param ProjectFileRequest $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function upload_file(int $project_id,ProjectFileRequest $request)
    {

        $project =  Project::where("id",$project_id)->first();
        if (!$project) {
            throw new UserException(__("Project not found"));
        }
        if ($request->hasFile('file')) {
            $project->process_uploaded_file($request->file('file'));
            UserLog::addLog(sprintf("Uploaded file %s to project %s ",
                $request->file('file')->getClientOriginalName(),$project->project_name));
        }

        return redirect()->route('project.view', ['project_id'=>$project_id]);
    }


    public function delete_file(int $project_id,Request $request) {
        $project =  Project::where("id",$project_id)->first();
        if (!$project) {
            throw new UserException(__("Project not found"));
        }
        $file_name = $request->request->get('project_file_name');
        if(!$file_name) {
            throw new UserException("Need filename");
        }
        if ($project->delete_project_file($file_name)) {
            return redirect()->route('project.view', ['project_id'=>$project_id]);
        }
        throw new UserException("Cannot find the file $file_name");

    }


    /**
     * @throws \Exception
     */
    public function create_log(int $project_id, Request $request) : RedirectResponse
    {

        $user = Utilities::get_logged_user();
        $content = $request->request->get('content');
        if (!$content) {
            throw new UserException(__('Logs need content'));
        }
        $new_log = new DailyLog();
        $new_log->content = $content;
        $new_log->project_id = $project_id;
        $new_log->user_type = DailyLog::USER_TYPE_ADMIN;
        $new_log->user_id = $user->id;
        $new_log->timestampss = time();
        $new_log->save();

        /**
         * @var DailyLog $mature_log
         */
        $mature_log = DailyLog::where('id',$new_log->id)
            /**
             * @uses DailyLog::parent_project_of_log
             */
            ->with('parent_project_of_log')
            ->first();

        if(!$mature_log) {
            throw new \LogicException("Cannot find newly created daily log!!!");
        }

        if ($request->files->has('photos')) {
            foreach ($request->file('photos') as $file) {
                DailyLogPhoto::createDailyPhoto($file,$mature_log);
            }
        }

        return redirect()->route('project.view', ['project_id'=>$project_id]);
    }



    /**
     * @param int $project_id
     * @param ProjectSaveRequest $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function update_project(int $project_id,ProjectSaveRequest $request) : RedirectResponse
    {
        /**
         * @var Project $project
         */
        $project =  Project::where("id",$project_id)
            /** @uses Project::project_assigns() */
            ->with('project_assigns')
            ->first();
        if (!$project) {
            throw new UserException(__("Project not found"));
        }
        $project->fill($request->validated());

        $given_foremen = $request->request->all('foreman');
        if (empty($given_foremen)) {$given_foremen = [];}
        $project->setForemen($given_foremen);

        $project->save();
        UserLog::addLog(sprintf("Updated project %s ",$project->project_name));
        return redirect()->route('project.view', ['project_id'=>$project_id]);
    }

    /**
     * @param int $project_id
     * @return RedirectResponse
     * @throws \Exception
     */
    public function delete_project(int $project_id) {
        $project =  Project::where("id",$project_id)->first();
        if (!$project) {
            throw new UserException(__("Project not found"));
        }
        try {
            $user = Utilities::get_logged_user();
            if (!$user->isAdmin()) {
                throw new UserException("You do not have permissions to delete this project ". $project->project_name);
            }
            $project->cleanup_project_resources();
            $project->delete();
        } catch (\Exception $e) {
            UserLog::addLog(sprintf("Attempted to delete project %s ",$project->project_name));
            throw $e;
        }

        UserLog::addLog(sprintf("Deleted project %s ",$project->project_name));
        return redirect()->intended(RouteServiceProvider::PROJECTS);
    }
}
