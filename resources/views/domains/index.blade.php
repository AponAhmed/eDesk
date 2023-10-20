@extends('layouts.app')

@section('content')
    <div class="dataContainer px-4 w-full">
        <div class="flex py-2 border-b border-solid border-slate-100 mb-3 items-center">
            <h1 class="font-thin text-xl">List of Domains</h1>
            <a href="{{ route('domains.create') }}"
                class="popup create-button px-4 py-2 font-semibold text-sm bg-cyan-500 text-white rounded-full shadow-sm ml-4">New
                Domain</a>
        </div>
        <div class="table-wraper overflow-x-auto bg-white p-4">
            <table>
                <thead>
                    <tr>
                        <th class="hidden">ID</th>
                        <th>URL</th>
                        <th>Key</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($domains as $domain)
                        <tr>
                            <td class="hidden">{{ $domain->id }}</td>
                            <td>{{ $domain->url }}</td>
                            <td>{{ $domain->key }}</td>
                            <td>
                                <div class="flex sep">
                                    <a class="popup" href="{{ route('domains.show', $domain->id) }}">View</a>
                                    <a class="popup" href="{{ route('domains.edit', $domain->id) }}">Edit</a>
                                    <form class="hidden md:block" method="POST"
                                        action="{{ route('domains.destroy', $domain->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">Delete Domain</button>
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
