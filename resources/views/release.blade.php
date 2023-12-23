    <form method="POST" enctype='multipart/form-data'>
        @csrf
        <input type="hidden" id="reply_id" value="{{ $message->id }}">
        <div class="message-header pr-12">
            <div class="header-subject mb-1">
                <div class="close-view-button"><svg xmlns="http://www.w3.org/2000/svg" class="ionicon"
                        viewBox="0 0 512 512">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="48" d="M244 400L100 256l144-144M120 256h292"></path>
                    </svg></div>
                <h3>{{ $message->subject }}</h3>
            </div>
            <div class="from-details">
                <div class="flex justify-between md:flex-row flex-col">
                    <div class="flex">
                        <div class="hidden md:flex flex-col md:flex-row">
                            <div class="flex mb-1 md:mb-0">
                                <strong class="mr-2 text-sm pr-">To :</strong>
                                <span
                                    class="from-name text-slate-500 text-sm">{{ $message->getOptions(false)->toName }}</span>
                                <span class="from-name text-slate-400 px-1 text-sm">&lt;{{ $message->to }}&gt;</span>
                            </div>
                            <span class="from-name text-slate-400 px-0 md:px-1 text-sm"><strong
                                    class="inline-block text-sm md:hidden">Time : </strong> at
                                {{ $message->date() }}</span>
                        </div>
                    </div>
                    @if (!$preview)
                        <div class="actions justify-end md:absolute static">
                            <button type="button" id="releaseBtn"
                                class="button mt-4 px-4 py-2 font-semibold text-sm bg-cyan-500 text-white rounded-md shadow-sm">Release</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @if ($preview)
            <div class="mb-4 mt-4">
                <div id="modifiedMessage" class="w-full p-2 h-full">{!! $message->replyBody !!}</div>
            </div>
        @else
            <div class="mb-4 mt-4">
                <div id="modifiedMessage" contenteditable="true" class="w-full p-2 h-full">{!! $message->replyBody !!}</div>
            </div>
            {{-- <div class="mb-1">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="redirect_to">Return To</label>
            <div class="relative flex items-center mb-3">
                <select name="return_to"
                    class="block appearance-none w-full bg-white border border-gray-300 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                    @foreach ($emails as $email => $name)
                        <option value="{{ $name }}:{{ $email }}">
                            {{ $name }}&lt;{{ $email }}&gt;</option>
                    @endforeach
                </select>
                <span class="text-gray-500 p-2 inline-block">or </span>
                <input type="text" name="return_to_custom"
                    class="appearance-none w-full bg-white border border-gray-300 hover:border-gray-500 px-4 py-2 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Email Address">
            </div>
            <div class="relative flex items-center">
                <input type="text" name="reply_cc"
                    class="appearance-none w-full bg-white border border-gray-300 hover:border-gray-500 px-4 py-2 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="CC">
            </div>
        </div> --}}
        @endif
    </form>
