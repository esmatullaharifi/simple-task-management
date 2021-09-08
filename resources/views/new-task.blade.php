<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            New Task
        </h2>
    </x-slot>

    @if ($errors->any())
        <div class="pt-12">
            <div class="sm:max-w-md mx-auto sm:px-6 lg:px-8">
                <div class="bg-red-100 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-red-100 border-b border-gray-200">
                        <div class="font-medium text-red-600">
                            {{ __('Whoops! Something went wrong.') }}
                        </div>

                        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0 bg-gray-100">
                    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                        <form method="POST" action="{{ route('tasks.store') }}">
                        @csrf
                            <!-- Project Name -->
                            <div style="padding-bottom: 15px;">
                                <x-label for="project_id" value="Project Name" />

                                <select id="project_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="project_id" required autofocus>
                                    <option selected disabled></option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" @if($project->id == old('project_id')) selected @endif>{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Task Name -->
                            <div>
                                <x-label for="name" value="Task Name" />

                                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required />
                            </div>

                            <!-- Task Priority -->
                            <div class="mt-4">
                                <x-label for="priority" value="Priority" />

                                <x-input id="priority" class="block mt-1 w-full" type="number" name="priority" min="1" step="1" :value="old('priority') ?? $newPriority" required />
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <x-button class="ml-3">
                                    Add
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
