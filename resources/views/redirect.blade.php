<form method="POST" action="{{ route(request()->segment(1) == 'gdesk' ? 'gredirect' : 'redirect') }}" class="ajx">
    @csrf
    <input type="hidden" name="message_id" value="{{ $id }}">

    <div class="mb-4">
        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="redirect_to">Redirect To</label>
        <div class="relative">
            <select name="redirect_to"
                class="block appearance-none w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 hover:border-gray-500 dark:hover:border-gray-600 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline dark:focus:border-blue-500 dark:text-gray-300">
                @foreach ($emails as $email => $name)
                    <option value="{{ $name }}:{{ $email }}">
                        {{ $name }}&lt;{{ $email }}&gt;</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="redirect_to_custom">Custom Email Address</label>
        <input type="text" name="redirect_to_custom"
            class="appearance-none w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 hover:border-gray-500 dark:hover:border-gray-600 px-4 py-2 rounded shadow leading-tight focus:outline-none focus:shadow-outline dark:focus:border-blue-500 dark:text-gray-300"
            placeholder="Email Address">
    </div>

    <button type="submit"
        class="button mt-4 px-4 py-2 font-semibold text-sm bg-cyan-500 dark:bg-cyan-700 text-white dark:text-gray-200 rounded-md shadow-sm">Send</button>
</form>
