<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Membros da ARKHAM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white p-6">
    <h1 class="text-3xl font-bold mb-4">Lista de Membros ARKHAM</h1>

    <ul class="space-y-2">
        @foreach ($membros as $membro)
            <li class="p-3 bg-gray-800 rounded">
                {{ $membro->membro_name }} - {{ $membro->membro_cargo }}
            </li>
        @endforeach
    </ul>
</body>
</html>
