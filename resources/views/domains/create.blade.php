<h1 class="text-lg mb-4 border-b border-solid border-slate-200 py-2 dark:border-slate-700 dark:text-gray-300">Create a New Domain</h1>
<form method="POST" action="{{ route('domains.store') }}" class="ajx">
    @csrf
    <div class="flex">
        <label for="url" class="px-2 dark:text-gray-300">Url</label>
        <input type="text" name="url" required
            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
    </div>
    <br>
    <button type="submit"
        class="button mt-4 px-4 py-2 font-semibold text-sm bg-cyan-500 text-white rounded-md shadow-sm dark:bg-cyan-600 dark:text-gray-200">Create
        Domain</button>
</form>
