<h1 class="text-lg mb-4 border-b border-solid border-slate-200 py-2 dark:border-slate-700 dark:text-gray-300">Domain Details</h1>

<p class="dark:text-gray-200"><strong>ID:</strong> {{ $domain->id }}</p>
<p class="dark:text-gray-200"><strong>URL:</strong> {{ $domain->url }}</p>
<p class="dark:text-gray-200"><strong>Key:</strong> {{ $domain->key }}</p>
<p class="dark:text-gray-200"><strong>Messages:</strong> {{ $domain->messages->count() }}</p>

<br>
<hr class="dark:border-gray-600">

<div class="flex sep dark:bg-gray-800 dark:text-gray-200">
    <a href="{{ route('domains.edit', $domain->id) }}" class="popup ml-0 dark:text-cyan-400">Edit Domain</a>
    <form method="POST" action="{{ route('domains.destroy', $domain->id) }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">Delete Domain</button>
    </form>
</div>
