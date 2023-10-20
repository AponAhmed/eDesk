@extends('layouts.app')

@section('content')
    <div class="settings-section w-full px-4 pb-4 overflow-hidden">
        <div class="flex py-2 border-b border-solid border-slate-100 mb-2 items-center">
            <h1 class="font-thin text-xl px-2">Settings</h1>
        </div>
        <form id="settingsForm" class="h-full">
            <div
                class="settings-input-container overflow-y-auto max-h-full bg-white border border-solid border-slate-200 rounded-md p-4 h-full">
                <div class="tab-wrap settings-tab">
                    <ul class="flex">
                        <li class="active section-head px-4 py-2 cursor-pointer" data-id="mailing">Mailing</li>
                        <li class="section-head px-4 py-2 cursor-pointer" data-id="general">General Settings</li>
                    </ul>
                    <div class="tab-contents-wrap px-4 py-6">
                        <section id="general" class="tab-pan ">
                            Content for General Settings tab goes here
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
                            <div class="optionField flex flex-col md:flex-row md:items-center justify-start mb-4">
                                <label class="w-32">Replied Box</label> <!-- Adjust the width as needed -->
                                <div class="flex-1 md:ml-4 ml-0">
                                    <input class="w-full border rounded px-2 py-1" type="text"
                                        name="settings[after_reply_box_name]" value="<?php echo $Settings::get('after_reply_box_name', 'eDesk'); ?>">
                                    <span class="text-gray-500 text-sm">A mail Box - Replied mails will assign there</span>
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
                                    <span class="text-gray-500 text-sm">Admin Email Address to Receive Mail with tracking
                                        information</span>
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
