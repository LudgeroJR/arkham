<!-- filepath: resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Teste Tailwind</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-900 via-purple-900 to-pink-900 min-h-screen flex flex-col items-center justify-center gap-8">

    <div class="bg-white rounded-xl shadow-lg p-8 flex flex-col items-center gap-4">
        <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-pink-500">
            Tailwind CSS 4.1.10 Funcionando!
        </h1>
        <p class="text-gray-700 text-lg">
            Se você está vendo este cartão estilizado, o Tailwind está funcionando corretamente.
        </p>
        <button class="px-6 py-2 bg-pink-500 hover:bg-pink-700 text-white rounded-full font-semibold shadow transition">
            Botão de Teste
        </button>
        <div class="flex gap-2">
            <span class="w-6 h-6 rounded-full bg-blue-500"></span>
            <span class="w-6 h-6 rounded-full bg-green-500"></span>
            <span class="w-6 h-6 rounded-full bg-yellow-400"></span>
            <span class="w-6 h-6 rounded-full bg-red-500"></span>
        </div>
    </div>

</body>
</html>