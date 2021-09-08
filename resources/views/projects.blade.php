<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Available Projects
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

    @if(session()->has('error-message'))
        <div class="pt-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-red-300 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-red-300 border-b border-gray-200">
                        {{ session()->get('error-message') }}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Project
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="table-items">
                        @foreach($projects as $project)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $project-> id}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $project->name }}
                                </td>
                                <td>
                                    <a href="{{ route('projects.edit', $project->id) }}" class="bg-blue-500 text-xs hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
                                        Edit
                                    </a> &nbsp;
                                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display: inline;">
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
</x-app-layout>
