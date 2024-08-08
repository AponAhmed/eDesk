@extends('layouts.app')

@section('content')
    <div class="dataContainer px-4 w-full">
        <div class="flex py-2 border-b border-solid border-slate-100 dark:border-slate-700 mb-3 items-center">
            <h1 class="font-thin text-xl px-2 text-gray-800 dark:text-gray-200">List of Domain</h1>
            <a href="{{ route('domains.create') }}"
                class="popup create-button px-4 py-1 font-semibold text-sm bg-cyan-500 text-white rounded-full shadow-sm ml-4">New
                Domain</a>
        </div>
        <div
            class="data-table table-wraper overflow-x-auto bg-white dark:bg-gray-800 p-4 rounded-md border border-gray-200 dark:border-gray-700">
            <table class="w-full text-left table-auto">
                <thead>
                    <tr>
                        <th class="hidden">ID</th>
                        <th class="text-gray-700 dark:text-gray-300">URL</th>
                        <th class="text-gray-700 dark:text-gray-300">Key</th>
                        <th class="text-gray-700 dark:text-gray-300">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($domains as $domain)
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td class="hidden">{{ $domain->id }}</td>
                            <td class="text-gray-700 dark:text-gray-300">{{ $domain->url }}</td>
                            <td class="text-gray-700 dark:text-gray-300">{{ $domain->key }}</td>
                            <td class="text-gray-700 dark:text-gray-300">
                                <div class="flex sep">
                                    <a class="popup  text-cyan-600 dark:text-cyan-400 "
                                        href="{{ route('domains.show', $domain->id) }}">View</a>
                                    <a class="popup text-cyan-600 dark:text-cyan-400"
                                        href="{{ route('domains.edit', $domain->id) }}">Edit</a>
                                    <form class="hidden md:block" method="POST"
                                        action="{{ route('domains.destroy', $domain->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400">Delete
                                            Domain</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
