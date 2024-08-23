@extends('layouts.app')

@section('content')
    <div class="settings-section w-full px-4 pb-4">
        <div class="flex py-2 border-b border-solid border-slate-100 dark:border-gray-900 mb-2 items-center">
            <h1 class="font-thin text-xl px-2 dark:text-gray-300  text-gray-800">Ai Reply Generator</h1>
        </div>
        <div class="p-4">
            <div class="mb-4 relative ">
                <div class="absolute right-0 top-[-25px] flex items-center">
                    <select id="aiProvider" id="select"
                        class="border border-gray-300 text-xs rounded-md py-[2px] px-2  dark:bg-gray-700 dark:text-gray-300 dark:border-gray-950">
                        <option class="dark:text-gray-200" value="freebox" @if (App\Models\Settings::get('ai_provider', 'gemini') === 'freebox') selected @endif>
                            Open
                            AI
                            (Freebox)
                        </option>
                        <option class="dark:text-gray-200" value="gemini" @if (App\Models\Settings::get('ai_provider', 'gemini') === 'gemini') selected @endif>
                            Gemini</option>
                    </select>
                </div>
                <div id="aiSettings" class="md:p-0 md:bg-transparent md:mb-0 p-2 bg-gray-100 rounded-md mb-3">
                    <div class="flex flex-col md:flex-row gap-2 mb-2">
                        <div class="w-full md:w-7/12">
                            <div class="flex">
                                <label class="text-sm text-gray-500">Prompt</label>
                                <button type="button" onclick="queryPrefix.classList.toggle('hidden')"
                                    class="flex items-center p-1 leading-4 dark:text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 dark:text-gray-300"
                                        viewBox="0 0 512 512">
                                        <path
                                            d="M262.29 192.31a64 64 0 1057.4 57.4 64.13 64.13 0 00-57.4-57.4zM416.39 256a154.34 154.34 0 01-1.53 20.79l45.21 35.46a10.81 10.81 0 012.45 13.75l-42.77 74a10.81 10.81 0 01-13.14 4.59l-44.9-18.08a16.11 16.11 0 00-15.17 1.75A164.48 164.48 0 01325 400.8a15.94 15.94 0 00-8.82 12.14l-6.73 47.89a11.08 11.08 0 01-10.68 9.17h-85.54a11.11 11.11 0 01-10.69-8.87l-6.72-47.82a16.07 16.07 0 00-9-12.22 155.3 155.3 0 01-21.46-12.57 16 16 0 00-15.11-1.71l-44.89 18.07a10.81 10.81 0 01-13.14-4.58l-42.77-74a10.8 10.8 0 012.45-13.75l38.21-30a16.05 16.05 0 006-14.08c-.36-4.17-.58-8.33-.58-12.5s.21-8.27.58-12.35a16 16 0 00-6.07-13.94l-38.19-30A10.81 10.81 0 0149.48 186l42.77-74a10.81 10.81 0 0113.14-4.59l44.9 18.08a16.11 16.11 0 0015.17-1.75A164.48 164.48 0 01187 111.2a15.94 15.94 0 008.82-12.14l6.73-47.89A11.08 11.08 0 01213.23 42h85.54a11.11 11.11 0 0110.69 8.87l6.72 47.82a16.07 16.07 0 009 12.22 155.3 155.3 0 0121.46 12.57 16 16 0 0015.11 1.71l44.89-18.07a10.81 10.81 0 0113.14 4.58l42.77 74a10.8 10.8 0 01-2.45 13.75l-38.21 30a16.05 16.05 0 00-6.05 14.08c.33 4.14.55 8.3.55 12.47z"
                                            fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="32" />
                                    </svg>
                                </button>
                            </div>
                            <textarea rows="2"
                                class="text-sm w-full p-2 hidden rounded-md border border-solid border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-950"
                                id="queryPrefix" placeholder="Prompt Prefix">{{ \App\Models\Settings::get('ai_prompt_prefix', 'Write Reply') }}</textarea>
                            <textarea rows="5"
                                class="text-sm w-full p-2 rounded-md border border-solid border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-950"
                                id="query"></textarea>
                        </div>
                        <div class="w-full md:w-5/12">
                            <label class="text-sm text-gray-500">Hint</label>
                            <textarea rows="3"
                                class="text-sm w-full p-2 rounded-md border border-solid border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-950"
                                id="hint"></textarea>
                            <div class="gemini-settings flex flex-col">
                                <label class="text-xs leading-4 mt-1 text-gray-500">Creativity</label>
                                <div class="flex">
                                    <input title="Temperature" id="temparature" type="range" min="0" max="1"
                                        value="{{ \App\Models\Settings::get('ai_temperature', '0.7') }}" step="0.1"
                                        class="mt-2 range-input appearance-none w-10/12 bg-gray-400 rounded h-1 transition-all ease-in-out duration-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-950"
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
                                            class="block w-full py-[2px] px-2 text-xs bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-950">
                                            @foreach ($languages as $language)
                                                <option class="dark:text-gray-200" value="{{ $language }}"
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
                                            class="block w-full py-[2px] px-2 text-xs bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-950">
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
                                                <option class="dark:text-gray-200" value="{{ $value }}"
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
                <textarea
                    class="w-full p-2 rounded-md border border-solid border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-950"
                    id="message" rows="12" name="message" placeholder="Write Here"></textarea>
            </div>
            <div class="flex items-center">
                <button type="button"
                    class="border border-black border-opacity-20 dark:border-opacity-50 flex items-center leading-4 dark:text-gray-300 dark:bg-gray-900 px-5 py-2 font-semibold text-sm bg-cyan-500 text-white rounded-md shadow-sm"
                    onclick="generateReplyhere(this)">
                    <svg class="w-3 mr-1 text-gray-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024">
                        <path fill="currentColor"
                            d="m199.04 672.64 193.984 112 224-387.968-193.92-112-224 388.032zm-23.872 60.16 32.896 148.288 144.896-45.696zM455.04 229.248l193.92 112 56.704-98.112-193.984-112-56.64 98.112zM104.32 708.8l384-665.024 304.768 175.936L409.152 884.8h.064l-248.448 78.336zm384 254.272v-64h448v64h-448z">
                        </path>
                    </svg> Generate</button>
                <button type="button"
                    class="border border-black border-opacity-20 dark:border-opacity-50 flex items-center leading-4 dark:text-gray-300 dark:bg-gray-900 px-5 py-2 font-semibold text-sm bg-gray-200 ml-3 text-gray-700 rounded-md shadow-sm"
                    onclick="copy2Clipboard(this)">Copy to Clipboard</button>
            </div>



        </div>
    </div>
    <script>
        function generateReplyhere(_this) {
            let queryVDom = document.createElement('textarea');
            queryVDom.value = queryPrefix.value + " " + query.value;
            window.generateReply(aiProvider.value, _this, queryVDom, hint, message, temparature.value, language.value, tone
                .value);

        }

        function copy2Clipboard(_this) {
            var textarea = document.getElementById("message");

            if (navigator.clipboard) {
                // Use the Clipboard API if available
                navigator.clipboard.writeText(textarea.value)
                    .then(() => {
                        _this.innerHTML = "Copied  !"
                        //alert("Copied to clipboard: " + textarea.value);
                    })
                    .catch(err => {
                        _this.innerHTML = "Error  !"
                        console.error("Failed to copy text: ", err);
                    });
            } else {
                // Fallback to the execCommand method
                textarea.select();
                textarea.setSelectionRange(0, 99999); // For mobile devices

                try {
                    document.execCommand("copy");
                    _this.innerHTML = "Copied  !"
                    // alert("Copied to clipboard: " + textarea.value);
                } catch (err) {
                    _this.innerHTML = "Error  !"
                    console.error("Failed to copy text: ", err);
                }
            }

            setTimeout(() => {
                _this.innerHTML = "Copy to Clipboard";
            }, 1000);
        }
    </script>
@endsection