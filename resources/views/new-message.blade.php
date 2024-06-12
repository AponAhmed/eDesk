@extends('layouts.app')

@section('content')
    <div class="flex flex-col h-full ox-h w-full bg-white">
        <div class="ox-h p-5">
            <form method="POST" id="mailSendNew" enctype='multipart/form-data' action="{{ route('sendnew') }}"
                class="w-full md:w-8/12 m-auto">
                @csrf
                <div class="to-area mb-2 flex items-center">
                    <div class="mr-2 w-full md:w-1/3 rounded-md flex items-center border border-gray-300  bg-white">
                        <label class="p-1 px-2 bg-gray-300">From:</label>
                        <select name="sender" class="bg-transparent px-2 w-full">
                            @foreach ($senders as $sender)
                                <option value="{{ $sender->id }}">{{ $sender->email_address }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="singleReceiver" class="w-full md:w-2/3 flex">
                        <input id="toaddress" class="rounded-md" type="text" placeholder="To">
                    </div>
                    <a href="javascript:void(0)" onclick="window.bulkTriger(this)"
                        class="ml-2 p-1 px-2  rounded-md">Bulk</a>
                </div>
                <div id="bulkReceiver" class="mb-1 hidden">
                    <textarea id="toaddressBulk" class="border p-1 w-full" placeholder="Bulk Email Address as Receiver"></textarea>
                </div>

                <div>
                    <input name="subject" class="rounded-md" type="text" placeholder="Subject">
                </div>

                <div class="mb-4 relative pt-6 mt-2">
                    <div class="absolute right-0 top-0 flex items-center">
                        <button type="button" class="flex py-1 px-4 items-center leading-4"
                            onclick="window.generateReply(aiProvider.value,this,query,hint,message,temparature.value,language.value,tone.value)">
                            <svg class="w-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024">
                                <path fill="currentColor"
                                    d="m199.04 672.64 193.984 112 224-387.968-193.92-112-224 388.032zm-23.872 60.16 32.896 148.288 144.896-45.696zM455.04 229.248l193.92 112 56.704-98.112-193.984-112-56.64 98.112zM104.32 708.8l384-665.024 304.768 175.936L409.152 884.8h.064l-248.448 78.336zm384 254.272v-64h448v64h-448z">
                                </path>
                            </svg> Generate</button>
                        <select id="aiProvider" id="select"
                            class="border border-gray-300 text-xs rounded-md py-[2px] px-2 ">
                            <option value="freebox" @if (App\Models\Settings::get('ai_provider', 'gemini') === 'freebox') selected @endif>Open AI
                                (Freebox)
                            </option>
                            <option value="gemini" @if (App\Models\Settings::get('ai_provider', 'gemini') === 'gemini') selected @endif>Gemini</option>
                        </select>
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
                                <textarea rows="5" class="text-sm w-full p-2 rounded-md border border-solid border-gray-200" id="query"></textarea>
                            </div>
                            <div class="w-5/12">
                                <label class="text-sm text-gray-500">Hint</label>
                                <textarea rows="3" class="text-sm w-full p-2 rounded-md border border-solid border-gray-200" id="hint"></textarea>
                                <div class="gemini-settings flex flex-col">
                                    <label class="text-xs leading-4 mt-1 text-gray-500">Creativity</label>
                                    <div class="flex">
                                        <input title="Temperature" id="temparature" type="range" min="0"
                                            max="1" value="{{ \App\Models\Settings::get('ai_temperature', '0.7') }}"
                                            step="0.1"
                                            class="mt-2 range-input appearance-none w-10/12 bg-gray-400 rounded h-1 transition-all ease-in-out duration-300"
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
                                                class="block w-full py-[2px] px-2 text-xs bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500">
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
                                                class="block w-full py-[2px] px-2 text-xs bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500">
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
                    <textarea class="w-full p-2 rounded-md border border-solid border-gray-200" id="message" rows="12" name="message"
                        placeholder="Write Here"></textarea>
                </div>

                <div class="mb-1">
                    <div class="to-area mb-2 flex md:flex-row flex-col items-center">
                        <div class="md:w-5/12 w-full flex items-center border border-gray-300 rounded-md  bg-white">
                            <label class="p-1 px-2 bg-gray-300">Return to:</label>
                            <select id="return_to" name="return_to" class="bg-transparent px-1">
                                @foreach ($emails as $email => $name)
                                    <option value="{{ $name }}:{{ $email }}">
                                        {{ $name }}&lt;{{ $email }}&gt;</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:w-3/12 w-full flex items-center">
                            <span class="text-gray-500 p-2 inline-block">or </span>
                            <input type="text" name="return_to_custom"
                                class="appearance-none w-full bg-white border border-gray-300 hover:border-gray-500 px-4 py-2 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="Email Address">
                        </div>
                        <div class="md:w-4/12 w-full flex items-center ml-3">
                            <input type="text" name="cc"
                                class="appearance-none  w-full bg-white border border-gray-300 hover:border-gray-500 px-4 py-2 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="CC">
                        </div>
                    </div>
                </div>

                <div id="progressBar" class="bg-gray-200 h-4 rounded overflow-hidden relative">
                    <div id="lbl"
                        class="h-full absolute left-1/2 top-0 text-gray-900 -translate-x-1/2 text-xs leading-1"></div>
                    <div id="progress" class="bg-cyan-300 h-full"></div>
                </div>

                <button id="submitBtn" type="submit"
                    class="button mt-4 px-4 py-2 font-semibold text-sm bg-cyan-500 text-white rounded-md shadow-sm">Send</button>
                <label class="mx-4"><input type="checkbox" value="1" name="read_receipt" checked> Read Receipt
                </label>
                <input type="file" name="attachments[]" multiple id="attachments">
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var bulkRec = false;
        var aiPorivider = document.getElementById("aiProvider");
        if (aiPorivider) {
            aiSettingsFieldManage(aiPorivider.value);
            aiPorivider.addEventListener("change", function(e) {
                aiSettingsFieldManage(aiPorivider.value);
            });
        }


        function getEmailsFromBulkTextarea() {
            var bulkTextarea = document.getElementById("toaddressBulk");
            var emailRegex = /[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/g;
            var matches = bulkTextarea.value.match(emailRegex);
            return matches ? matches : [];
        }


        window.bulkTriger = function(_elm) {
            if (bulkRec) {
                // text-white
                _elm.classList.remove('bg-cyan-600');
                _elm.classList.remove('text-white');
                bulkRec = false;
                bulkReceiver.classList.add('hidden')
                singleReceiver.classList.remove('hidden');
            } else {
                bulkRec = true;
                bulkReceiver.classList.remove('hidden')
                singleReceiver.classList.add('hidden');
                _elm.classList.add('bg-cyan-600');
                _elm.classList.add('text-white');
            }
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


        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('mailSendNew');
            var submitBtn = document.getElementById('submitBtn');
            var toAddress = document.getElementById('toaddress');
            var progressBar = document.getElementById('progress');
            progressBar.style.width = "0px";
            var progressWrap = document.getElementById('progressBar');
            progressWrap.style.display = 'none';
            let lbl = document.getElementById('lbl');

            var bulkTextarea = document.getElementById("toaddressBulk");


            submitBtn.addEventListener('click', async function(event) {
                event.preventDefault();
                submitBtn.innerHTML = "Sending...";
                let completedEmails = 0;
                // Bulk emails 

                let emails = getEmailsFromBulkTextarea();
                let cc = false;
                if (emails.length > 0) {
                    progressWrap.style.display = 'block';
                    const totalEmails = emails.length;
                    const emailsCopy = [...emails];
                    cc=true;
                    for (let email of emails) {
                        await new Promise((resolve, reject) => {
                            setTimeout(() => {
                                submitForm(email, lbl, cc)
                                    .then(() => {
                                        cc = false;
                                        completedEmails++;
                                        updateProgressBar(completedEmails,
                                            totalEmails);
                                        if (completedEmails == totalEmails) {
                                            form.reset();
                                            submitBtn.innerHTML = totalEmails +
                                                " Sent Successfully";
                                        }
                                        //Email
                                        // Find the index of the item to remove
                                        let index = emailsCopy.indexOf(email);
                                        if (index !== -1) {
                                            // Remove the item using splice
                                            emailsCopy.splice(index, 1);
                                        }
                                        //update textarea
                                        bulkTextarea.value = emailsCopy.join('\n');

                                        resolve();
                                    })
                                    .catch(error => {
                                        reject(error);
                                    });
                            }, getRandomDelay()); // 
                        });
                    }
                } else {
                    const totalEmails = 1
                    if (toaddress.value == '') {
                        alert('Please enter Receipent address');
                        submitBtn.innerHTML = "Send";
                    } else {
                        progressWrap.style.display = 'block';

                        await submitForm(toaddress.value, lbl, cc)
                            .then(() => {
                                completedEmails++;
                                updateProgressBar(completedEmails, totalEmails);
                                form.reset();
                                submitBtn.innerHTML = "Sent Successfully";
                            });
                    }
                }
            });
        });

        function submitForm(email, lbl, cc) {
            lbl.innerHTML = `Sending to: ${email}`;

            var form = document.getElementById('mailSendNew');
            var formData = new FormData(form);
            formData.append('toaddress', email); // Append the 'toaddress' with the email value

            if (cc) {
                formData.append('auto_cc', 1);
            }

            return new Promise((resolve, reject) => {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', form.action, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        //console.log('Response Success:', xhr.responseText);
                        if (xhr.responseText == "1") {
                            console.log('Sent To:', email);
                        } else {
                            console.log('Error Sending To:', email);
                        }
                        resolve();
                    } else {
                        console.error('Error:', xhr.statusText);
                        reject(xhr.statusText);
                    }
                };
                xhr.onerror = function() {
                    console.error('Error:', xhr.statusText);
                    reject(xhr.statusText);
                };
                xhr.send(formData);
            });
        }

        function getRandomDelay() {
            // Generate a random delay between 1 and 5 seconds
            //return Math.floor(Math.random() * 1000) + 1000;
            return Math.floor(Math.random() * 4000) + 1000;
        }

        function updateProgressBar(completed, total) {
            var currentTime = new Date();
            //console.log("Current time:", currentTime);
            var percentage = (completed / total) * 100;
            var progressBar = document.getElementById('progress');
            progressBar.style.width = percentage + '%';
        }
    </script>
@endsection
