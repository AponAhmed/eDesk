<h1 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">Create Sender</h1>

@if ($errors->any())
    <div class="mb-4">
        <ul class="text-red-500 dark:text-red-400">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('senders.store') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <div class="flex flex-col md:flex-row gap-x-6">
            <div class="w-4/6">
                <label for="email_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                <input type="text" id="email_address" name="email_address"
                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
            </div>
            <div class="w-2/6">
                <label for="daily_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Daily Limit</label>
                <input type="number" id="daily_limit" name="daily_limit"
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
            </div>
        </div>
    </div>

    <div class="border-b border-gray-900/10 pb-4 dark:border-gray-700">
        <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-100">SMTP Settings</h2>
        <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-3 sm:grid-cols-6">
            <div class="sm:col-span-3">
                <label for="smtp_host" class="block text-sm font-medium text-gray-700 dark:text-gray-300">SMTP Host</label>
                <input type="text" id="smtp_host" name="smtp_options[host]"
                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
            </div>
            <div class="sm:col-span-2">
                <label for="smtp_security" class="block text-sm font-medium text-gray-700 dark:text-gray-300">SMTP Security</label>
                <select id="smtp_security" name="smtp_options[security]"
                    class="block w-full mt-1 rounded-md border-0 py-1.5 px-2 bg-white text-gray-900 shadow-sm sm:max-w-xs sm:text-sm sm:leading-6 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
                    <option value="ssl">SSL</option>
                    <option value="tls">TLS</option>
                    <option value="none">None</option>
                </select>
            </div>
            <div class="sm:col-span-1">
                <label for="smtp_port" class="block text-sm font-medium text-gray-700 dark:text-gray-300">SMTP Port</label>
                <input type="text" id="smtp_port" name="smtp_options[port]"
                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
            </div>
            <div class="sm:col-span-3">
                <label for="smtp_account" class="block text-sm font-medium text-gray-700 dark:text-gray-300">SMTP Account</label>
                <input type="text" id="smtp_account" name="smtp_options[account]"
                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
            </div>
            <div class="sm:col-span-3">
                <label for="smtp_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">SMTP Password</label>
                <input type="password" id="smtp_password" name="smtp_options[password]"
                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 min-h-7 px-2">
            </div>
        </div>
    </div>

    {{-- IMAP settings --}}
    <div class="border-b border-gray-900/10 pb-4 dark:border-gray-700">
        <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-100">IMAP Settings</h2>
        <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-3 sm:grid-cols-6">
            <div class="sm:col-span-3">
                <label for="imap_host" class="block text-sm font-medium text-gray-700 dark:text-gray-300">IMAP Host</label>
                <input type="text" id="imap_host" name="imap_options[host]"
                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
            </div>
            <div class="sm:col-span-2">
                <label for="imap_security" class="block text-sm font-medium text-gray-700 dark:text-gray-300">IMAP Security</label>
                <select id="imap_security" name="imap_options[security]"
                    class="block w-full mt-1 rounded-md border-0 py-1.5 px-2 bg-white text-gray-900 shadow-sm sm:max-w-xs sm:text-sm sm:leading-6 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
                    <option value="ssl">SSL</option>
                    <option value="tls">TLS</option>
                    <option value="none">None</option>
                </select>
            </div>
            <div class="sm:col-span-1">
                <label for="imap_port" class="block text-sm font-medium text-gray-700 dark:text-gray-300">IMAP Port</label>
                <input type="text" id="imap_port" name="imap_options[port]"
                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
            </div>
            <div class="sm:col-span-3">
                <label for="imap_account" class="block text-sm font-medium text-gray-700 dark:text-gray-300">IMAP Account</label>
                <input type="text" id="imap_account" name="imap_options[account]"
                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
            </div>
            <div class="sm:col-span-3">
                <label for="imap_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">IMAP Password</label>
                <input type="password" id="imap_password" name="imap_options[password]"
                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 min-h-7 px-2">
            </div>
        </div>
    </div>

    <div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create
            Sender</button>
    </div>
</form>
