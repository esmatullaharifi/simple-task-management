<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        $tasks = Task::with([
            'project'
        ])->orderBy('priority')->get();

        return view('tasks', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): \Illuminate\Contracts\View\View
    {
        $projects = Project::all();
        $newPriority = (Task::max('priority')) + 1;

        return view('new-task', compact('projects', 'newPriority'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddTaskRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AddTaskRequest $request): \Illuminate\Http\RedirectResponse
    {
        // Update the priorities in case the new task has a duplicate priority number
        // First we find whether the new priority has duplicate or not
        $duplicatePriority = Task::where('priority', $request['priority'])->count();

        // If the priority is duplicate then we have to update all those priorities
        if ($duplicatePriority) {
            Task::where('priority', '>', $request['priority'] - 1)
                ->update([
                    'priority' => \DB::raw('priority + 1')
                ]);
        }

        // Add the new task
        $task = new Task($request->validated());
        $task->save();

        return redirect(route('tasks.index'))->with('success-message', 'New task added.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(int $id): \Illuminate\Contracts\View\View
    {
        $projects = Project::all();
        $task = Task::findOrFail($id);

        return view('edit-task', compact('projects', 'task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTaskRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateTaskRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        //First we retrieve the task for updating
        $task = Task::findOrFail($id);

        //We check whether the priority is updated or not
        if ($task->priority != $request['priority']) {
            // Handle the duplicate priority
            $this->handleDuplicatePriorities($task, $request['priority']);
        }

        $task->project_id = $request['project_id'];
        $task->name = $request['name'];
        $task->priority = $request['priority'];
        $task->save();

        return redirect(route('tasks.index'))->with('success-message', 'Task updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        Task::destroy($id);

        return redirect(route('tasks.index'))->with('success-message', 'Task deleted.');
    }

    /**
     * Change the specified task priority from drag and drop interface.
     *
     * @param Request $request
     * @return void
     */
    public function changePriorities(Request $request): void
    {
        $task = Task::findOrFail($request['id']);
        // We add one to the priority because the value received is the task index and indices starts from 0
        $newPriority = $request['priority'] + 1;

        //We check whether the priority is updated or not
        if ($task->priority != $newPriority) {
            // Handle the duplicate priority
            $this->handleDuplicatePriorities($task, $newPriority);
        }

        $task->priority = $newPriority;
        $task->save();
    }

    /**
     * Handle the duplicate priorities
     *
     * @param Task $task
     * @param int $priority
     * @return void
     */
    private function handleDuplicatePriorities(Task $task, int $priority)
    {
        //We check whether this priority is duplicated or not
        $duplicatePriority = Task::where('priority', $priority)->count();

        // If the priority is duplicated then we have to update all the possible duplicates
        if ($duplicatePriority) {
            $task->update(['priority' => 0]);
            $oneLowerPriority = $priority - 1;
            // We shift the duplicate priority to next priority and so on to last one
            Task::where('priority', '<>', 0)->update([
                'priority' => \DB::raw("CASE
                                                WHEN priority > $oneLowerPriority THEN priority + 1
                                                ELSE priority
                                              END")
            ]);
        }
    }
}
