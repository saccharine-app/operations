<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-md p-6">
        <h2 class="text-2xl font-bold mb-4">How can we help?</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('intake.store') }}" method="POST" class="space-y-4">
            @csrf 

            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="submitted_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="tel" name="contact_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="contact_email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Message</label>
                <textarea name="message" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2"></textarea>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white rounded-md p-2 hover:bg-blue-700 font-bold">
                Submit
            </button>
        </form>
    </div>
</body>
</html>