<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Available Tasks
        </h2>
    </x-slot>

    <style>
        .sortable-chosen {
            box-shadow: 8px 8px 32px #e1e1e1;
        }
        .sortable-drag {
            opacity: 0;
        }
    </style>

    @if(session()->has('success-message'))
        <div class="pt-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-green-300 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-green-300 border-b border-gray-200">
                        {{ session()->get('success-message') }}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Filter By Project:<br/>
                    <div style="padding-bottom: 15px;">
                        <select id="project_id" onchange="filterTasksByProject(this)" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="0">All</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" @if($project->id == old('project_id')) selected @endif>{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="pb-6">
                        You can change the priorities by drag and drop the tasks.
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Priority
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Project
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Task
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="table-items">
                        @foreach($tasks as $key => $task)
                            <tr data-id="{{ $task->id }}" data-project="{{ $task->project_id }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $key + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $task->project->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $task->name }}
                                </td>
                                <td>
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="bg-blue-500 text-xs hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
                                        Edit
                                    </a> &nbsp;
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-xs hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        function filterTasksByProject(element) {
            let projectId = element.value;
            let tasks = document.getElementById('table-items').children;
            [].forEach.call(tasks, (row) => {
                //In case All Project option is selected then we will remove the hidden attr from all of the tasks
                if (projectId === 0) {
                    row.removeAttribute('hidden');
                } else {
                    //In case specific project is selected, then we will remove the hidden attr from corresponding tasks
                    // and add the hidden field to rest of the tasks
                    if (row.getAttribute('data-project') === projectId.toString()) {
                        row.removeAttribute('hidden');
                    } else {
                        row.setAttribute('hidden', 'hidden');
                    }
                }
            });
        }

        const tableItems = document.getElementById('table-items');
        // Sortable is a function from SortableJS library and used for drag and drop sorting here
        new Sortable(tableItems, {
            animation: 350,
            chosenClass: "sortable-chosen",
            dragClass: "sortable-drag",
            onEnd: function (task) {
                // After each drag and drop we will send the new priority and the task id to the server for storage
                fetch('{{ route('tasks.changePriorities') }}', {
                    method: 'POST',
                    //We set these headers to attach CSRF token with the request as the request is POST
                    // and also to set the content type header
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: task.item.getAttribute('data-id'),
                        priority: task.newIndex
                    })
                }).catch(() => {
                    alert('Ooops! Something went wrong! Refresh the webpage and try again.');
                });
            },
            store: {
                set: function (tasks) {
                    //Using this section we will change the priority numbers as drag and drop is done.
                    let tableRows = tasks.el.children;
                    [].forEach.call(tableRows, (row, index) => {
                        row.cells[0].innerText = index + 1;
                    });
                }
            }
        });
    </script>
</x-app-layout>
