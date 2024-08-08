<form method="POST" enctype='multipart/form-data'
    action="{{ route(request()->segment(1) == 'gdesk' ? 'greply' : 'reply') }}" class="ajx">
    @csrf
    <input type="hidden" name="message_id" value="{{ $id }}">
    <h3 class="text-lg font-light mb-4 dark:text-gray-300">Reply</h3>


    <div class="mb-4 relative">
        <div class="absolute right-0 top-[-25px] flex items-center">
            <button type="button" class="flex py-1 px-4 items-center leading-4 dark:text-gray-300"
                onclick="window.generateReply(aiProvider.value,this,query,hint,message,temparature.value,language.value,tone.value)">
                <svg class="w-3 mr-1 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024">
                    <path fill="currentColor"
                        d="m199.04 672.64 193.984 112 224-387.968-193.92-112-224 388.032zm-23.872 60.16 32.896 148.288 144.896-45.696zM455.04 229.248l193.92 112 56.704-98.112-193.984-112-56.64 98.112zM104.32 708.8l384-665.024 304.768 175.936L409.152 884.8h.064l-248.448 78.336zm384 254.272v-64h448v64h-448z">
                    </path>
                </svg> Generate</button>
            <select id="aiProvider" id="select" class="border border-gray-300 text-xs rounded-md py-[2px] px-2  dark:bg-gray-800 dark:text-gray-300 dark:border-gray-950">
                <option value="freebox" @if (App\Models\Settings::get('ai_provider', 'gemini') === 'freebox') selected @endif>Open AI
                    (Freebox)
                </option>
                <option value="gemini" @if (App\Models\Settings::get('ai_provider', 'gemini') === 'gemini') selected @endif>Gemini</option>
            </select>
            <button type="button" onclick="aiSettings.classList.toggle('hidden')"
                class="flex items-center p-1 leading-4 dark:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 dark:text-gray-300" viewBox="0 0 512 512">
                    <path
                        d="M262.29 192.31a64 64 0 1057.4 57.4 64.13 64.13 0 00-57.4-57.4zM416.39 256a154.34 154.34 0 01-1.53 20.79l45.21 35.46a10.81 10.81 0 012.45 13.75l-42.77 74a10.81 10.81 0 01-13.14 4.59l-44.9-18.08a16.11 16.11 0 00-15.17 1.75A164.48 164.48 0 01325 400.8a15.94 15.94 0 00-8.82 12.14l-6.73 47.89a11.08 11.08 0 01-10.68 9.17h-85.54a11.11 11.11 0 01-10.69-8.87l-6.72-47.82a16.07 16.07 0 00-9-12.22 155.3 155.3 0 01-21.46-12.57 16 16 0 00-15.11-1.71l-44.89 18.07a10.81 10.81 0 01-13.14-4.58l-42.77-74a10.8 10.8 0 012.45-13.75l38.21-30a16.05 16.05 0 006-14.08c-.36-4.17-.58-8.33-.58-12.5s.21-8.27.58-12.35a16 16 0 00-6.07-13.94l-38.19-30A10.81 10.81 0 0149.48 186l42.77-74a10.81 10.81 0 0113.14-4.59l44.9 18.08a16.11 16.11 0 0015.17-1.75A164.48 164.48 0 01187 111.2a15.94 15.94 0 008.82-12.14l6.73-47.89A11.08 11.08 0 01213.23 42h85.54a11.11 11.11 0 0110.69 8.87l6.72 47.82a16.07 16.07 0 009 12.22 155.3 155.3 0 0121.46 12.57 16 16 0 0015.11 1.71l44.89-18.07a10.81 10.81 0 0113.14 4.58l42.77 74a10.8 10.8 0 01-2.45 13.75l-38.21 30a16.05 16.05 0 00-6.05 14.08c.33 4.14.55 8.3.55 12.47z"
                        fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="32" />
                </svg>
            </button>
        </div>
        <div id="aiSettings" class="hidden md:p-0 md:bg-transparent md:mb-0 p-2 bg-gray-100 rounded-md mb-3">
            <div class="flex flex-col md:flex-row gap-2 mb-2">
                <div class="w-full md:w-7/12">
                    <label class="text-sm text-gray-500">Prompt</label>
                    <textarea rows="5" class="text-sm w-full p-2 rounded-md border border-solid border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-950" id="query">{{ $query }}</textarea>
                </div>
                <div class="w-full md:w-5/12">
                    <label class="text-sm text-gray-500">Hint</label>
                    <textarea rows="3" class="text-sm w-full p-2 rounded-md border border-solid border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-950" id="hint"></textarea>
                    <div class="gemini-settings flex flex-col">
                        <label class="text-xs leading-4 mt-1 text-gray-500">Creativity</label>
                        <div class="flex">
                            <input title="Temperature" id="temparature" type="range" min="0" max="1"
                                value="{{ \App\Models\Settings::get('ai_temperature', '0.7') }}" step="0.1"
                                class="mt-2 range-input appearance-none w-10/12 bg-gray-400 rounded h-1 transition-all ease-in-out duration-300 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-950"
                                oninput="document.getElementById('temparatureVal').textContent = this.value">

                            <span id="temparatureVal" class="text-sm ml-2">
                                {{ \App\Models\Settings::get('ai_temperature', '0.7') }}
                            </span>
                        </div>
                    </div>
                    <div class="freebox-settings flex gap-1">
                        <div class="flex flex-col w-1/2">
                            <label class=" text-xs leading-4 mt-1 text-gray-500">Language</label>
                            <div class="flex">
                                @php
                                    $selectedLanguage = \App\Models\Settings::get('ai_lang', ''); // Assuming $Settings::get() retrieves the selected language
                                    $languages = [
                                        'English',
                                        'Bulgarian',
                                        'Czech',
                                        'Chinese (Simplified)',
                                        'Chinese (Traditional)',
                                        'Dutch',
                                        'Danish',
                                        'Estonian',
                                        'French',
                                        'Finnish',
                                        'German',
                                        'Greek',
                                        'Hungarian',
                                        'Italian',
                                        'Japanese',
                                        'Korean',
                                        'Lithuanian',
                                        'Latvian',
                                        'Norwegian',
                                        'Polish',
                                        'Portuguese (Portugal)',
                                        'Portuguese (Brazil)',
                                        'Romanian',
                                        'Spanish',
                                        'Slovak',
                                        'Slovenian',
                                        'Swedish',
                                    ];
                                @endphp

                                <select id="language"
                                    class="block w-full py-[2px] px-2 text-xs bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-950">
                                    @foreach ($languages as $language)
                                        <option value="{{ $language }}"
                                            {{ $language == $selectedLanguage ? 'selected' : '' }}>
                                            {{ $language }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex flex-col w-1/2">
                            <label class=" text-xs leading-4 mt-1 text-gray-500">Tone</label>
                            <div class="flex">
                                <select id="tone"
                                    class="block w-full py-[2px] px-2 text-xs bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-950">
                                    @php
                                        $selectedOption = \App\Models\Settings::get('ai_lang', 'Formal'); // Assume $selectedOption contains the value of the selected option
                                        $options = [
                                            'Formal' => 'Formal',
                                            'Professional' => 'Professional',
                                            'Friendly' => 'Friendly',
                                            'Concise' => 'Concise',
                                            'Detailed' => 'Detailed',
                                            'Informal' => 'Informal',
                                            'Inspirational' => 'Inspirational',
                                            'Requestive' => 'Requestive',
                                            'Consultative' => 'Consultative',
                                            'Appreciative' => 'Appreciative',
                                            'Declination' => 'Declination',
                                        ];
                                    @endphp
                                    @foreach ($options as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ $selectedOption === $value ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <textarea class="w-full p-2 rounded-md border border-solid border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-950" id="message" rows="12" name="message"
            placeholder="Write Here"></textarea>
    </div>

    <div class="mb-1">
        <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300" for="redirect_to">Return To</label>
        <div class="relative flex items-center mb-3">
            <select name="return_to"
                class="block appearance-none w-full bg-white border border-gray-300 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-800 dark:text-gray-300 dark:border-gray-950">
                @foreach ($emails as $email => $name)
                    <option value="{{ $name }}:{{ $email }}">
                        {{ $name }}&lt;{{ $email }}&gt;</option>
                @endforeach
            </select>
            <span class="text-gray-500 p-2 inline-block ">or </span>
            <input type="text" name="return_to_custom"
                class="appearance-none w-full bg-white border border-gray-300 hover:border-gray-500 px-4 py-2 rounded shadow leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-800 dark:text-gray-300 dark:border-gray-950"
                placeholder="Email Address">
        </div>
        <div class="flex flex-col md:flex-row relative  md:items-center">
            <input type="text" name="reply_cc"
                class="appearance-none  w-full bg-white border border-gray-300 hover:border-gray-500 px-4 py-2 rounded shadow leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-800 dark:text-gray-300 dark:border-gray-950"
                placeholder="CC">
            <label class="flex md:ml-5 mt-2 md:mt-0 w-2/6"><input name="reminder" type="checkbox">&nbsp;Set
                Reminder</label>
        </div>
    </div>
    <div class="flex flex-col-reverse md:flex-row relative  md:items-center">
        <button type="submit"
            class="button mt-4 px-4 py-2 font-semibold text-sm bg-cyan-500 text-white rounded-md shadow-sm">Send</button>
        <div class="flex">
            <label class="mx-4 md:mx-3 ml-0  mr-3"><input type="checkbox" value="1" name="read_receipt"
                    checked>
                Read Receipt
            </label>
            <input type="file" name="attachments[]" multiple id="attachments">
        </div>
    </div>
</form>

<script>
    var aiPorivider = document.getElementById("aiProvider");
    if (aiPorivider) {
        aiSettingsFieldManage(aiPorivider.value);
        aiPorivider.addEventListener("change", function(e) {
            aiSettingsFieldManage(aiPorivider.value);
        });
    }


    function aiSettingsFieldManage(prov) {
        // Get all elements with class "freebox-settings"
        var freeboxSettings = document.querySelectorAll('.freebox-settings');

        // Get all elements with class "gemini-settings"
        var geminiSettings = document.querySelectorAll('.gemini-settings');

        switch (prov) {
            case "freebox":
                // Loop through all elements with class "gemini-settings" and add class "hidden"
                geminiSettings.forEach(function(element) {
                    element.classList.add('hidden');
                });

                // Loop through all elements with class "freebox-settings" and remove class "hidden"
                freeboxSettings.forEach(function(element) {
                    element.classList.remove('hidden');
                });
                break;
            case "gemini":
                // Loop through all elements with class "freebox-settings" and add class "hidden"
                freeboxSettings.forEach(function(element) {
                    element.classList.add('hidden');
                });

                // Loop through all elements with class "gemini-settings" and remove class "hidden"
                geminiSettings.forEach(function(element) {
                    element.classList.remove('hidden');
                });
                break;
        }
    }
</script>
