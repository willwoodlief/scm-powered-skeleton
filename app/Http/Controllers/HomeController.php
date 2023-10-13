<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Project;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;


class HomeController extends BaseController {
    /**
     * Display the user's profile form.
     */
    public function root_page(): RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        } else {
            return redirect()->intended(RouteServiceProvider::LOGIN);
        }
    }

    public function dashboard() {

        $active_projects = Project::getAllActiveProjects();

        $employees = Employee::getAllEmployees();

        return view('dashboard')
            ->with('active_projects',$active_projects)
            ->with('employees',$employees)
            ;
    }
}
