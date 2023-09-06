<h1 class="text-lg mb-4 border-b border-solid border-slate-200 py-2">Domain Details</h1>
<p><strong>ID:</strong> {{ $domain->id }}</p>
<p><strong>URL:</strong> {{ $domain->url }}</p>
<p><strong>Key:</strong> {{ $domain->key }}</p>
<p><strong>Messages:</strong> {{ $domain->messages->count() }}</p>
<br>
<hr>
<div class="flex sep">
    <a href="{{ route('domains.edit', $domain->id) }}" class="popup ml-0">Edit Domain</a>
    <form method="POST" action="{{ route('domains.destroy', $domain->id) }}">
        @csrf
        @method('DELETE')
        <button type="submit">Delete Domain</button>
    </form>
</div>
