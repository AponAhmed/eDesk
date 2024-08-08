@extends('layouts.app')

@section('content')
    <div class="dataContainer px-4 w-full">
        <div class="flex py-2 border-b border-solid border-slate-100 dark:border-slate-700 mb-3 items-center">
            <h1 class="font-thin text-xl px-2 text-gray-800 dark:text-gray-200">List of Senders</h1>
            <a href="{{ route('senders.create') }}"
                class="popup create-button px-4 py-1 font-semibold text-sm bg-cyan-500 text-white rounded-full shadow-sm ml-4">New
                Sender</a>
        </div>
        <div class="data-table table-wraper overflow-x-auto bg-white dark:bg-gray-800 p-4 rounded-md border border-gray-200 dark:border-gray-700">
            <table class="w-full text-left table-auto">
                <thead>
                    <tr>
                        <th class="hidden">ID</th>
                        <th class="text-gray-700 dark:text-gray-300">Email Address</th>
                        <th class="text-gray-700 dark:text-gray-300">Quota Left</th>
                        <th class="text-gray-700 dark:text-gray-300">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($senders as $sender)
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td class="hidden">{{ $sender->id }}</td>
                            <td class="text-gray-700 dark:text-gray-300">{{ $sender->email_address }} {{ $sender->auth_login_type == 1 ? '(API)' : '(SMTP)' }}</td>
                            <td class="text-gray-700 dark:text-gray-300">{{ $sender->daily_limit - $sender->daily_send_count }}</td>
                            <td>
                                <div class="flex sep space-x-4">
                                    <a class="popup text-cyan-600 dark:text-cyan-400 hover:underline" href="{{ route('senders.edit', $sender->id) }}">Edit</a>
                                    <form class="hidden md:block" method="POST"
                                        action="{{ route('senders.destroy', $sender->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete Sender</button>
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
