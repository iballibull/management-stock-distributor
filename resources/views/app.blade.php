<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Alpine Test</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div x-data="{ count: 0 }" class="text-center space-y-4 bg-white p-6 rounded shadow-md">
        <h1 class="text-2xl font-bold text-gray-800">Alpine.js Counter</h1>

        <p class="text-lg text-gray-700">Current count: <span x-text="count"></span></p>

        <div class="space-x-2">
            <button @click="count--" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                -
            </button>
            <button @click="count++" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                +
            </button>
        </div>
    </div>

</body>

</html>
