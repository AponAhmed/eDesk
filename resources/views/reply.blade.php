<form method="POST" enctype='multipart/form-data' action="{{ route('reply') }}" class="ajx">
    @csrf
    <input type="hidden" name="message_id" value="{{ $id }}">
    <h3 class="text-lg font-light mb-4">Reply</h3>

    <div class="mb-4 relative">
        <div class="absolute right-0 top-[-25px] flex items-center">
            <button type="button" class="flex py-1 px-4 items-center leading-4"
                onclick="window.generateReply(this,query,hint,message)">
                <svg class="w-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024">
                    <path fill="currentColor"
                        d="m199.04 672.64 193.984 112 224-387.968-193.92-112-224 388.032zm-23.872 60.16 32.896 148.288 144.896-45.696zM455.04 229.248l193.92 112 56.704-98.112-193.984-112-56.64 98.112zM104.32 708.8l384-665.024 304.768 175.936L409.152 884.8h.064l-248.448 78.336zm384 254.272v-64h448v64h-448z">
                    </path>
                </svg> Generate</button>
            <button type="button" onclick="aiSettings.classList.toggle('hidden')"
                class="flex items-center p-1 leading-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 512 512">
                    <path
                        d="M262.29 192.31a64 64 0 1057.4 57.4 64.13 64.13 0 00-57.4-57.4zM416.39 256a154.34 154.34 0 01-1.53 20.79l45.21 35.46a10.81 10.81 0 012.45 13.75l-42.77 74a10.81 10.81 0 01-13.14 4.59l-44.9-18.08a16.11 16.11 0 00-15.17 1.75A164.48 164.48 0 01325 400.8a15.94 15.94 0 00-8.82 12.14l-6.73 47.89a11.08 11.08 0 01-10.68 9.17h-85.54a11.11 11.11 0 01-10.69-8.87l-6.72-47.82a16.07 16.07 0 00-9-12.22 155.3 155.3 0 01-21.46-12.57 16 16 0 00-15.11-1.71l-44.89 18.07a10.81 10.81 0 01-13.14-4.58l-42.77-74a10.8 10.8 0 012.45-13.75l38.21-30a16.05 16.05 0 006-14.08c-.36-4.17-.58-8.33-.58-12.5s.21-8.27.58-12.35a16 16 0 00-6.07-13.94l-38.19-30A10.81 10.81 0 0149.48 186l42.77-74a10.81 10.81 0 0113.14-4.59l44.9 18.08a16.11 16.11 0 0015.17-1.75A164.48 164.48 0 01187 111.2a15.94 15.94 0 008.82-12.14l6.73-47.89A11.08 11.08 0 01213.23 42h85.54a11.11 11.11 0 0110.69 8.87l6.72 47.82a16.07 16.07 0 009 12.22 155.3 155.3 0 0121.46 12.57 16 16 0 0015.11 1.71l44.89-18.07a10.81 10.81 0 0113.14 4.58l42.77 74a10.8 10.8 0 01-2.45 13.75l-38.21 30a16.05 16.05 0 00-6.05 14.08c.33 4.14.55 8.3.55 12.47z"
                        fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="32" />
                </svg>
            </button>
        </div>
        <div id="aiSettings" class="hidden">
            <div class="flex gap-2 mb-2">
                <div class="w-7/12">
                    <label class="text-sm text-gray-500">Prompt</label>
                    <textarea rows="4" class="text-sm w-full p-2 rounded-md border border-solid border-gray-200" id="query">{{ $query }}</textarea>
                </div>
                <div class="w-5/12">
                    <label class="text-sm text-gray-500">Hint</label>
                    <textarea rows="4" class="text-sm w-full p-2 rounded-md border border-solid border-gray-200" id="hint"></textarea>
                </div>
            </div>
        </div>
        <textarea class="w-full p-2 rounded-md border border-solid border-gray-200" id="message" rows="12" name="message"
            placeholder="Write Here"></textarea>
    </div>

    <div class="mb-1">
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
                class="appearance-none  w-full bg-white border border-gray-300 hover:border-gray-500 px-4 py-2 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                placeholder="CC">
            <label class="flex ml-5 w-2/6"><input name="reminder" type="checkbox">&nbsp;Set Reminder</label>
        </div>
    </div>
    <button type="submit"
        class="button mt-4 px-4 py-2 font-semibold text-sm bg-cyan-500 text-white rounded-md shadow-sm">Send</button>
    <input type="file" name="attachments[]" multiple id="attachments">
</form>
