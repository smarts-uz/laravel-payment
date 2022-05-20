<?php

namespace Teamprodev\LaravelPayment\Http\Controllers;

use Illuminate\Http\Request;
use Teamprodev\LaravelPayment\Models\Project;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{

    public function index()
    {
        $projects = Project::latest()->get();
        return view('pay-uz::projects.index',compact('projects'));
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
