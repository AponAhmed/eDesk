@extends('layouts.app')

@section('content')
    <div id="mails" class="flex flex-col h-full ox-h w-full">
        <div class="list-wraper ox-h">
            @foreach ($messages as $message)
                <div data-id="{{ $message->id }}" class="mail-list mail-list-item ox-h hover:bg-slate-50">
                    <div class="list-inner ox-h flex items-center">
                        <div class="controll px-3">
                            <input type="checkbox" name="">
                        </div>
                        <div class="name-wraper flex flex-col md:flex-row md:items-center ox-h w-full">
                            <a href="#" class="form-name md:px-2">{{ $message->name }}</a>
                            <div class="list-content ox-h">
                                <a href="#" class="subject-line">{{ $message->subject }}</a>
                                <span class="snippet hidden md:block">{{ $message->snippet(150) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- Pagination Links -->
        <div class="pagination flex items-center">
            <span class="text-slate-600 mr-3">Page {{ $messages->currentPage() }} of {{ $messages->lastPage() }}</span>
            {{ $messages->links() }}
        </div>
    </div>
    <div id="messageDetails" class="mail-details h-full">--Details</div>
@endsection
