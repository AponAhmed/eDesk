@extends('layouts.app')

@section('content')
    <div class="dataContainer px-4 w-full">
        <div class="flex py-2 border-b border-solid border-slate-100 mb-3 items-center">
            <h1 class="font-thin text-xl">List of Senders</h1>
            <a href="{{ route('senders.create') }}"
                class="popup create-button px-4 py-2 font-semibold text-sm bg-cyan-500 text-white rounded-full shadow-sm ml-4">New
                Sender</a>
        </div>
        <div class="table-wraper overflow-x-auto bg-white p-4 rounded-md border border-gray-200">
            <table>
                <thead>
                    <tr>
                        <th class="hidden">ID</th>
                        <th>Email Address</th>
                        <th>Auth Login Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($senders as $sender)
                        <tr>
                            <td class="hidden">{{ $sender->id }}</td>
                            <td>{{ $sender->email_address }}</td>
                            <td>{{ $sender->auth_login_type ? 'Yes' : 'No' }}</td>
                            <td>
                                <div class="flex sep">
                                    <a class="popup" href="{{ route('senders.edit', $sender->id) }}">Edit</a>
                                    <form class="hidden md:block" method="POST"
                                        action="{{ route('senders.destroy', $sender->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">Delete Sender</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $senders->links() }}
            </div>
        </div>
    </div>
@endsection
