<h1 class="text-2xl font-bold mb-4">Create Sender</h1>
@if ($errors->any())
    <div class="mb-4">
        <ul class="text-red-500">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('senders.store') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <label for="email_address" class="block text-sm font-medium text-gray-700">Email Address</label>
        <input type="email" id="email_address" name="email_address" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
    </div>
    <div>
        <label for="auth_login_type" class="block text-sm font-medium text-gray-700">Auth Login Type</label>
        <input type="checkbox" id="auth_login_type" name="auth_login_type" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md">
    </div>
    <!-- Add more input fields for other sender attributes if needed -->
    <div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Sender</button>
    </div>
</form>
