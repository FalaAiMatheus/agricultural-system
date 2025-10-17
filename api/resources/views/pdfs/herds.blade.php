<html lang="en">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
  <body class="p-6 font-sans">

    <h1 class="text-2xl font-bold mb-4">Rebanhos por Produtor</h1>

    @foreach([$producer] as $producer)
        <div class="mb-8 border-b pb-4">
            <h2 class="text-xl font-semibold">{{ $producer['name'] }}</h2>
            <p class="text-sm text-gray-600">
                Telefone: {{ $producer['telephone'] ?? '-' }} |
                Email: {{ $producer['email'] ?? '-' }} |
                Endereço: {{ $producer['address'] ?? '-' }}
            </p>

            @foreach($producer['properties'] as $property)
                <div class="mt-4">
                    <h3 class="text-lg font-medium mb-2">
                        Propriedade: {{ $property['name'] }} ({{ $property['municipality'] }}/{{ $property['uf'] }})
                    </h3>

                    @if(!empty($property['herds']))
                        <div class="overflow-x-auto">
                            <table class="min-w-full border border-gray-300 text-left">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-3 py-2 border-b">Espécie</th>
                                        <th class="px-3 py-2 border-b">Quantidade</th>
                                        <th class="px-3 py-2 border-b">Finalidade</th>
                                        <th class="px-3 py-2 border-b">Data Atualização</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($property['herds'] as $herd)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-3 py-2 border-b">{{ $herd['species'] }}</td>
                                            <td class="px-3 py-2 border-b">{{ $herd['quantity'] }}</td>
                                            <td class="px-3 py-2 border-b">{{ $herd['purpose'] }}</td>
                                            <td class="px-3 py-2 border-b">
                                                {{ \Carbon\Carbon::parse($herd['update_date'])->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">Nenhum rebanho cadastrado nesta propriedade.</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach
</body>
</html>


