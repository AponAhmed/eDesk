<h1 class="text-lg mb-4 border-b border-solid border-slate-200 py-2">Edit Domain</h1>

<form method="POST" action="{{ route('domains.update', $domain->id) }}">
    @csrf
    @method('PATCH')
    <div class="flex">
        <label for="url" class="px-2">Url</label>
        <input type="text" name="url" value="{{ $domain->url }}" required>
    </div>
    <br>
    <button type="submit"
        class="button mt-4 px-4 py-2 font-semibold text-sm bg-cyan-500 text-white rounded-md shadow-sm">Update
        Domain</button>
</form>
