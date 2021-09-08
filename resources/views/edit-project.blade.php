<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Project (ID: {{ $project->id }})
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
                        <form method="POST" action="{{ route('projects.update', $project->id) }}">
                            @method('PUT')
                            @csrf
                            <!-- Project Name -->
                            <div>
                                <x-label for="name" value="Project Name" />

                                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name') ?? $project->name" required autofocus />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-button class="ml-3">
                                    Update
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
