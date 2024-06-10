@extends('layouts.app')

@section('content')
    <div class="settings-section w-full px-4 pb-4 overflow-hidden">
        <div class="flex py-2 border-b border-solid border-slate-100 mb-2 items-center">
            <h1 class="font-thin text-xl px-2">Settings</h1>
        </div>
        <form id="settingsForm" class="h-full overflow-hidden">
            <div
                class="settings-input-container overflow-hidden max-h-full bg-white border border-solid border-slate-200 rounded-md p-4 h-full">
                <div class="tab-wrap settings-tab h-[calc(100%-40px)] overflow-hidden">
                    <ul class="flex">
                        <li class="active section-head px-4 py-2 cursor-pointer" data-id="mailing">eDesk</li>
                        <li class="section-head px-4 py-2 cursor-pointer" data-id="gdesk">gDesk</li>
                        <li class="section-head px-4 py-2 cursor-pointer" data-id="aiSettings">AI</li>
                    </ul>
                    <div class="tab-contents-wrap px-4 py-6 overflow-y-auto scrollbar-thin">
                        <section id="gdesk" class="tab-pan">
                            <div class="w-full md:w-2/4">

                                <div class="optionField flex flex-col md:flex-row md:items-center justify-start mb-4">
                                    <label class="w-32">Reply to Name</label> <!-- Adjust the width as needed -->
                                    <div class="flex-1 md:ml-4 ml-0">
                                        <input class="w-full border rounded px-2 py-1" type="text"
                                            name="settings[gadmin_name]" value="<?php echo $Settings::get('gadmin_name', 'SiATEX'); ?>">
                                        <span class="text-gray-500 text-sm">Reply to Name Who Receives Replies</span>
                                    </div>
                                </div>
                                <div class="optionField flex flex-col md:flex-row md:items-center justify-start mb-4">
                                    <label class="w-32">Reply to Email</label> <!-- Adjust the width as needed -->
                                    <div class="flex-1 md:ml-4 ml-0">
                                        <input class="w-full border rounded px-2 py-1" type="email"
                                            name="settings[gadmin_email]" value="<?php echo $Settings::get('gadmin_email', 'admin@siatexltd.com'); ?>">
                                        <span class="text-gray-500 text-sm">Reply to  Email Address to Receive reply mails</span>
                                    </div>
                                </div>
                                <div class="optionField flex flex-col md:flex-row md:items-center justify-start mb-4">
                                    <label class="w-32">Read Receipt to Email</label> <!-- Adjust the width as needed -->
                                    <div class="flex-1 md:ml-4 ml-0">
                                        <input class="w-full border rounded px-2 py-1" type="email"
                                            name="settings[gread_receipt]" value="<?php echo $Settings::get('gread_receipt', ''); ?>">
                                        <span class="text-gray-500 text-sm">Disposition-Notification-To" header to the email, requesting a read receipt.</span>
                                    </div>
                                </div>
                                <div class="optionField flex flex-col md:flex-row md:items-center justify-start mb-4">
                                    <label class="w-32">Signature</label> <!-- Adjust the width as needed -->
                                    <div class="flex-1 md:ml-4 ml-0">
                                        <textarea rows="10" name="settings[gdesk_signature]" class="w-full scrollbar-thin border border-gray-300 p-4"><?php echo $Settings::get('gdesk_signature', ''); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section id="aiSettings" class="tab-pan ">

                            <div class="flex items-center">
                                <label for="select" class="mr-2">Select an option:</label>
                                <select id="aiProvider" name="settings[ai_provider]" id="select"
                                    class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-blue-500">
                                    <option value="freebox" @if ($Settings::get('ai_provider', 'gemini') === 'freebox') selected @endif>Open AI
                                        (Freebox)
                                    </option>
                                    <option value="gemini" @if ($Settings::get('ai_provider', 'gemini') === 'gemini') selected @endif>Gemini</option>
                                </select>
                            </div>
                            <hr class="my-4">
                            <div class="flex flex-col md:flex-row gap-7">
                                <div class="w-full md:w-5/12 ">
                                    <div
                                        class="gemini-settings optionField flex flex-col md:flex-row md:items-center justify-start mb-4">
                                        <label class="w-32">API KEY</label> <!-- Adjust the width as needed -->
                                        <div class="flex-1 md:ml-4 ml-0">
                                            <input class="w-full border rounded px-2 py-1" type="text"
                                                name="settings[ai_api_key]" value="<?php echo $Settings::get('ai_api_key', ''); ?>">
                                            <span class="text-gray-500 text-sm">Key for api service provider</span>
                                        </div>
                                    </div>

                                    <div
                                        class="gemini-settings optionField flex flex-col md:flex-row md:items-center justify-start mb-4">
                                        <label class="w-32">Data Model</label> <!-- Adjust the width as needed -->
                                        <div class="flex-1 md:ml-4 ml-0">
                                            <input class="w-full border rounded px-2 py-1" type="text"
                                                name="settings[ai_data_model]" value="<?php echo $Settings::get('ai_data_model', 'gemini-pro'); ?>">
                                            <span class="text-gray-500 text-sm">Data model of service provider</span>
                                        </div>
                                    </div>
                                    <div
                                        class="freebox-settings optionField flex flex-col md:flex-row md:items-center justify-start mb-4">
                                        <label class="w-32">FreeBox Model</label> <!-- Adjust the width as needed -->
                                        <div class="flex-1 md:ml-4 ml-0">
                                            @php
                                                $selectedModel = $Settings::get(
                                                    'ai_freebox_model',
                                                    'ai-content-generator',
                                                ); // Assuming $Settings::get() retrieves the selected language

                                                $models = [
                                                    'ai-content-generator' => 'Content Generator',
                                                    'ai-email-generator' => 'Email Generator',
                                                ];
                                            @endphp
                                            <select name="settings[ai_freebox_model]"
                                                class="block w-full p-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500">
                                                @foreach ($models as $k => $name)
                                                    <option value="{{ $k }}"
                                                        {{ $k == $selectedModel ? 'selected' : '' }}>
                                                        {{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div
                                        class="freebox-settings optionField flex flex-col md:flex-row md:items-center justify-start mb-4">
                                        <label class="w-32">Language</label> <!-- Adjust the width as needed -->
                                        <div class="flex-1 md:ml-4 ml-0">
                                            @php
                                                $selectedLanguage = $Settings::get('ai_lang', ''); // Assuming $Settings::get() retrieves the selected language
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

                                            <select name="settings[ai_lang]"
                                                class="block w-full p-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500">
                                                @foreach ($languages as $language)
                                                    <option value="{{ $language }}"
                                                        {{ $language == $selectedLanguage ? 'selected' : '' }}>
                                                        {{ $language }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div
                                        class="freebox-settings optionField flex flex-col md:flex-row md:items-center justify-start mb-4">
                                        <label class="w-32">Tone</label> <!-- Adjust the width as needed -->
                                        <div class="flex-1 md:ml-4 ml-0">
                                            <select name="settings[ai_tone]"
                                                class="block w-full p-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500">
                                                <option value="" disabled>Select an option</option>
                                                @php
                                                    $selectedOption = $Settings::get('ai_lang', 'Formal'); // Assume $selectedOption contains the value of the selected option
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
                                    <div
                                        class="gemini-settings optionField flex flex-col md:flex-row md:items-center justify-start mb-4">
                                        <label class="w-32">Creativity</label> <!-- Adjust the width as needed -->
                                        <div class="flex-1 md:ml-4 ml-0">
                                            <div class="flex">
                                                <input title="Temperature" name="settings[ai_temperature]" id="temparature"
                                                    type="range" min="0" max="1"
                                                    value="{{ \App\Models\Settings::get('ai_temperature', '0.7') }}"
                                                    step="0.1"
                                                    class="mt-2 range-input appearance-none w-10/12 bg-gray-400 rounded h-1 transition-all ease-in-out duration-300"
                                                    oninput="document.getElementById('temparatureVal').textContent = this.value">

                                                <span id="temparatureVal" class="text-sm ml-2">
                                                    {{ \App\Models\Settings::get('ai_temperature', '0.7') }}
                                                </span>
                                            </div>

                                            {{-- <input class="w-full border rounded px-2 py-1" type="text"
                                                name="settings[ai_temperature]" value="<?php echo $Settings::get('ai_temperature', '0.7'); ?>">
                                             --}}
                                            <span class="text-gray-500 text-sm">Controls the randomness of the output. Must
                                                be positive. Typical values are in the range: [0.0,1.0]</span>
                                        </div>
                                    </div>
                                    <div class="optionField flex flex-col md:flex-row md:items-center justify-start mb-4">
                                        <label class="w-32">Prompt Prefix</label> <!-- Adjust the width as needed -->
                                        <div class="flex-1 md:ml-4 ml-0">
                                            <textarea rows="2" name="settings[ai_prompt_prefix]"
                                                class="p-2 rounded border border-gray-300 bg-transparent w-full h-full"
                                                placeholder="Write a reply in short-sentence to this email using the hints below:"><?php echo $Settings::get('ai_prompt_prefix', ''); ?></textarea>

                                            <span class="text-gray-500 text-sm">Prefix text of prompt</span>
                                        </div>
                                    </div>
                                    <div class="optionField flex flex-col md:flex-row md:items-center justify-start mb-4">
                                        <label class="w-32">Signature Filter</label> <!-- Adjust the width as needed -->
                                        <div class="flex-1 md:ml-4 ml-0">
                                            <textarea placeholder="Best regards," rows="2" name="settings[ai_signeture_prefix]"
                                                class="p-2 scrollbar-thin rounded border border-gray-300 bg-transparent w-full h-full"
                                                placeholder="Write a reply in short-sentence to this email using the hints below:"><?php echo $Settings::get('ai_signeture_prefix', ''); ?></textarea>

                                            <span class="text-gray-500 text-sm">(each should new Line) Prefix text of
                                                signature and remove rest..</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full md:w-7/12 mt-8 md:mt-0">
                                    <div class="flex-column">
                                        <label class="text-sm text-gray-600 mb-1 block">Information About your
                                            Company</label>
                                        <textarea rows="12" name="settings[ai_about_company]"
                                            class="scrollbar-thin p-2 rounded border border-gray-300 bg-transparent w-full h-full"
                                            placeholder="About Your Company"><?php echo $Settings::get('ai_about_company', ''); ?></textarea>
                                    </div>
                                </div>
                            </div>

                        </section>
                        <section id="mailing" class="tab-pan active">

                            @if ($gmail->configured)
                                @if (!$gmail->connect)
                                    <a href="{{ $gmail->client->createAuthUrl() }}"
                                        class="flex rounded-sm w-60 justify-center items-center py-2 px-4 border border-solid border-slate-100">
                                        <svg class="w-5 mr-1" viewBox="0 0 512 512">
                                            <path
                                                d="M473.16 221.48l-2.26-9.59H262.46v88.22H387c-12.93 61.4-72.93 93.72-121.94 93.72-35.66 0-73.25-15-98.13-39.11a140.08 140.08 0 01-41.8-98.88c0-37.16 16.7-74.33 41-98.78s61-38.13 97.49-38.13c41.79 0 71.74 22.19 82.94 32.31l62.69-62.36C390.86 72.72 340.34 32 261.6 32c-60.75 0-119 23.27-161.58 65.71C58 139.5 36.25 199.93 36.25 256s20.58 113.48 61.3 155.6c43.51 44.92 105.13 68.4 168.58 68.4 57.73 0 112.45-22.62 151.45-63.66 38.34-40.4 58.17-96.3 58.17-154.9 0-24.67-2.48-39.32-2.59-39.96z" />
                                        </svg>
                                        <span>Login With Google</span>
                                    </a>
                                @else
                                    <div class="flex items-center">
                                        <div
                                            class="flex rounded-sm w-80 justify-center items-center py-2 px-4 border border-solid border-green-300">
                                            <svg class="w-5 mr-1 text-green-500 fill-green-500" viewBox="0 0 512 512">
                                                <path
                                                    d="M473.16 221.48l-2.26-9.59H262.46v88.22H387c-12.93 61.4-72.93 93.72-121.94 93.72-35.66 0-73.25-15-98.13-39.11a140.08 140.08 0 01-41.8-98.88c0-37.16 16.7-74.33 41-98.78s61-38.13 97.49-38.13c41.79 0 71.74 22.19 82.94 32.31l62.69-62.36C390.86 72.72 340.34 32 261.6 32c-60.75 0-119 23.27-161.58 65.71C58 139.5 36.25 199.93 36.25 256s20.58 113.48 61.3 155.6c43.51 44.92 105.13 68.4 168.58 68.4 57.73 0 112.45-22.62 151.45-63.66 38.34-40.4 58.17-96.3 58.17-154.9 0-24.67-2.48-39.32-2.59-39.96z" />
                                            </svg>
                                            <span class="text-green-700">Loged
                                                in : {{ $gmail->getProfile()->getEmailAddress() }}</span>
                                        </div>
                                        <a href="javascript:void(0)" title="Logout" id="logoutGmail" class="ml-4">
                                            <svg class="text-red-600 w-7 " viewBox="0 0 512 512">
                                                <path
                                                    d="M304 336v40a40 40 0 01-40 40H104a40 40 0 01-40-40V136a40 40 0 0140-40h152c22.09 0 48 17.91 48 40v40M368 336l80-80-80-80M176 256h256"
                                                    fill="none" stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="32" />
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                            @else
                                Error with Gmail APP configuration (Credentials)
                            @endif

                            <hr class="my-4">
                            <div class="w-full md:w-2/4">
                                <div class="optionField flex flex-col md:flex-row md:items-center justify-start mb-4">
                                    <label class="w-32">Replied Box</label> <!-- Adjust the width as needed -->
                                    <div class="flex-1 md:ml-4 ml-0">
                                        <input class="w-full border rounded px-2 py-1" type="text"
                                            name="settings[after_reply_box_name]" value="<?php echo $Settings::get('after_reply_box_name', 'eDesk'); ?>">
                                        <span class="text-gray-500 text-sm">A mail Box - Replied mails will assign
                                            there</span>
                                    </div>
                                </div>
                                <div class="optionField flex flex-col md:flex-row md:items-center justify-start mb-4">
                                    <label class="w-32">Redirect Box</label> <!-- Adjust the width as needed -->
                                    <div class="flex-1 md:ml-4 ml-0">
                                        <input class="w-full border rounded px-2 py-1" type="text"
                                            name="settings[after_redirect_box_name]" value="<?php echo $Settings::get('after_redirect_box_name', 'eDesk-Redirect'); ?>">
                                        <span class="text-gray-500 text-sm">A mail Box - Redirected mails will Stored
                                            there</span>
                                    </div>
                                </div>
                                <hr class="my-4">
                                <div class="optionField flex flex-col md:flex-row md:items-center justify-start mb-4">
                                    <label class="w-32">Admin Name</label> <!-- Adjust the width as needed -->
                                    <div class="flex-1 md:ml-4 ml-0">
                                        <input class="w-full border rounded px-2 py-1" type="text"
                                            name="settings[admin_name]" value="<?php echo $Settings::get('admin_name', 'SiATEX'); ?>">
                                        <span class="text-gray-500 text-sm">Admin Name Who Receives Mail</span>
                                    </div>
                                </div>
                                <div class="optionField flex flex-col md:flex-row md:items-center justify-start mb-4">
                                    <label class="w-32">Admin Email</label> <!-- Adjust the width as needed -->
                                    <div class="flex-1 md:ml-4 ml-0">
                                        <input class="w-full border rounded px-2 py-1" type="email"
                                            name="settings[admin_email]" value="<?php echo $Settings::get('admin_email', 'admin@siatexltd.com'); ?>">
                                        <span class="text-gray-500 text-sm">Admin Email Address to Receive Mail with
                                            tracking
                                            information</span>
                                    </div>
                                </div>
                                <div class="optionField flex flex-col md:flex-row md:items-center justify-start mb-4">
                                    <label class="w-32">Signature</label> <!-- Adjust the width as needed -->
                                    <div class="flex-1 md:ml-4 ml-0">
                                        <textarea rows="5" name="settings[edesk_signature]" class="scrollbar-thin w-full border border-gray-300 p-4"><?php echo $Settings::get('edesk_signature', ''); ?></textarea>

                                        <span class="text-gray-500 text-sm"></span>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                <button type="submit"
                    class="update-btn px-4 py-2 font-semibold text-sm bg-cyan-500 text-white rounded-md shadow-sm ml-4">Update
                    Options</button>
            </div>
        </form>

    </div>
@endsection
