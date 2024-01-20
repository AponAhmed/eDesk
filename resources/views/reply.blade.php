<form method="POST" enctype='multipart/form-data' action="{{ route('reply') }}" class="ajx">
    @csrf
    <input type="hidden" name="message_id" value="{{ $id }}">
    <h3 class="text-lg font-light mb-4">Reply</h3>

    <div class="mb-4">
        <textarea class="w-full p-2 rounded-md border border-solid border-gray-200" id="message" rows="12" name="message"
            placeholder="Write Here"></textarea>
    </div>

    <div class="mb-1">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="redirect_to">Return To</label>
        <div class="relative flex items-center mb-3">
            <select name="return_to"
                class="block appearance-none w-full bg-white border border-gray-300 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                @foreach ($emails as $email => $name)
                    <option value="{{ $name }}:{{ $email }}">
                        {{ $name }}&lt;{{ $email }}&gt;</option>
                @endforeach
            </select>
            <span class="text-gray-500 p-2 inline-block">or </span>
            <input type="text" name="return_to_custom"
                class="appearance-none w-full bg-white border border-gray-300 hover:border-gray-500 px-4 py-2 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                placeholder="Email Address">
        </div>
        <div class="relative flex items-center">

            <input type="text" name="reply_cc"
                class="appearance-none  w-full bg-white border border-gray-300 hover:border-gray-500 px-4 py-2 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                placeholder="CC">
            <label class="flex ml-5 w-2/6"><input name="reminder" type="checkbox">&nbsp;Set Reminder</label>
        </div>
    </div>

    <button type="submit"
        class="button mt-4 px-4 py-2 font-semibold text-sm bg-cyan-500 text-white rounded-md shadow-sm">Send</button>
    <input type="file" name="attachments[]" multiple id="attachments">
    
</form>
