<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        $projects = Project::all();

        return view('projects', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): \Illuminate\Contracts\View\View
    {
        return view('new-project');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddProjectRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AddProjectRequest $request): \Illuminate\Http\RedirectResponse
    {
        $project = new Project($request->validated());
        $project->save();

        return redirect(route('projects.index'))->with('success-message', 'New project added.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(int $id): \Illuminate\Contracts\View\View
    {
        $project = Project::findOrFail($id);

        return view('edit-project', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProjectRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateProjectRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        Project::find($id)->update(['name' => $request['name']]);

        return redirect(route('projects.index'))->with('success-message', 'Project updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        \DB::beginTransaction();
        try {
            Project::destroy($id);
            \DB::commit();
            return redirect(route('projects.index'))->with('success-message', 'Project deleted.');
        } catch (QueryException $e) {
            \DB::rollback();
            return redirect(route('projects.index'))->with('error-message', 'Project has tasks, so it can not be deleted.');
        }
    }
}
