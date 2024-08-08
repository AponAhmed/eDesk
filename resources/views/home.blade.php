@extends('layouts.app')

@section('content')
    <div id="mails" class="flex flex-col h-full ox-h w-full bg-white dark:bg-gray-900">
        <div class="list-wraper ox-h dark:border-gray-900">
            <!-- Check if $messages is empty -->
            @if ($messages->isEmpty())
                <div class="px-7 py-7" role="alert">
                    <strong class="font-bold">No messages found!</strong>
                    <span class="block sm:inline">There are no messages to display.</span>
                </div>
            @else
                @foreach ($messages as $message)
                    <div data-id="{{ $message->id }}"
                        class="@php echo implode(' ',$message->getLabels()) @endphp mail-list mail-list-item ox-h hover:bg-slate-50">
                        <div class="list-inner ox-h flex items-center relative">
                            <div class="controll flex items-center px-3">
                                <div class="checkbox-wrapper-31">
                                    <input value="{{ $message->id }}" class="data-check" type="checkbox">
                                    <svg viewBox="0 0 35.6 35.6">
                                        <circle class="background" cx="17.8" cy="17.8" r="17.8"></circle>
                                        <circle class="stroke" cx="17.8" cy="17.8" r="14.37"></circle>
                                        <polyline class="check" points="11.78 18.12 15.55 22.23 25.17 12.87"></polyline>
                                    </svg>
                                </div>
                            </div>
                            <div class="name-wraper flex flex-col md:flex-row md:items-center ox-h w-full">
                                <a href="javascript:void(0)" class="form-name md:px-2 text-gray-800 dark:text-gray-200"
                                    title="{{ $message->name }}">{{ $message->name }}</a>
                                <div class="list-content ox-h">
                                    <div class="message-control absolute right-1 top-0 flex">
                                        <div class="leading-none text-sm px-2 pb-[1px] font-mono bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 rounded-full border border-solid border-gray-200 dark:border-gray-800"
                                            title="Messaged from {{ $message->domain->url }}">
                                            @php echo str_replace(['https://','http://'], "", $message->domain->url) @endphp
                                        </div>
                                    </div>
                                    <a href="javascript:void(0)"
                                        class="subject-line text-gray-800 dark:text-gray-200">{{ $message->subject }}</a>
                                    <span
                                        class="snippet hidden md:block text-gray-600 dark:text-gray-400">{{ $message->snippet(150) }}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Pagination Links -->
        <div
            class="action-pagination flex items-center py-1 px-2 justify-between bg-gray-50 dark:bg-gray-800 dark:border-gray-900 border border-t-0 border-solid border-gray-200">
            <div class="action-wrap flex px-2 items-center">
                <div class="checkbox-wrapper-31 mr-2">
                    <input class="checkAll" type="checkbox">
                    <svg viewBox="0 0 35.6 35.6">
                        <circle class="background" cx="17.8" cy="17.8" r="17.8"></circle>
                        <circle class="stroke" cx="17.8" cy="17.8" r="14.37"></circle>
                        <polyline class="check" points="11.78 18.12 15.55 22.23 25.17 12.87"></polyline>
                    </svg>
                </div>
                <div class="dropdown relative inline-block text-left">
                    <div>
                        <button title="Action"
                            class="dropdown-toggler rounded-full flex flex-col items-center justify-between h-7 w-7 p-2 bg-white dark:bg-gray-800 border border-solid border-gray-300 dark:border-gray-600">
                            <span class="bg-gray-700 dark:bg-gray-300 h-[2px] w-[2px] rounded-full"></span>
                            <span class="bg-gray-700 dark:bg-gray-300 h-[2px] w-[2px] rounded-full"></span>
                            <span class="bg-gray-700 dark:bg-gray-300 h-[2px] w-[2px] rounded-full"></span>
                        </button>
                    </div>
                    <div class="dropdown-content absolute left-0 bottom-full z-10 mb-2 w-56 rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 dark:ring-white dark:ring-opacity-10 focus:outline-none"
                        role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                        <div class="py-1" role="none">
                            @foreach ($actions as $indx => $action)
                                <a href="javascript:void(0)" data-action-title="{{ $action->label }}"
                                    data-action="{{ $action->action }}"
                                    class="multiple-action-trigger text-gray-700 dark:text-gray-300 block px-4 py-2 text-sm hover:bg-slate-100 dark:hover:bg-gray-900"
                                    role="menuitem" tabindex="-1"
                                    id="menu-item-{{ $indx }}">{{ $action->label }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @if ($box == 'trash')
                    <a href="javascript:void(0)" id="delete-all" data-box="{{ $box }}"
                        class="rounded-full flex  items-center justify-between h-7  p-2 bg-white border border-solid border-gray-300 ml-2">Delete
                        all</a>
                @endif
            </div>
            <div class="pagination flex items-center ">
                <span class="mx-3 text-gray-500 text-sm">Page {{ $messages->currentPage() }} of
                    {{ $messages->lastPage() }}</span>
                {{ $messages->links() }}
            </div>
        </div>

    </div>
    <div id="messageDetails" class="mail-details h-full bg-white dark:bg-gray-900"></div>
@endsection
