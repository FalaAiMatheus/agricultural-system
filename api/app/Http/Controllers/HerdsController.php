<?php

namespace App\Http\Controllers;

use App\Models\Herds;
use Illuminate\Foundation\Exceptions\Renderer\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HerdsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $herds = Herds::all();

        return response()->json(['data' => $herds]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'species' => 'required|string|max:255',
            'property_id' => 'required|integer',
            'quantity' => 'required|integer',
            'purpose' => 'required|string|max:255',
        ], [
            'species.required' => 'Nome da especie é obrigatório',
            'property_id.required' => 'Id da Propriedade é obrigatório',
            'quantity.required' => 'Quantidade é obrigatória',
            'purpose.required' => 'Finalidade é obrigatória',
        ]);

        try {
            DB::beginTransaction();

            Herds::create([...$validated, 'update_date' => now()]);

            DB::commit();

            return response()->json(['message' => 'Rebanho inserido com sucesso'], 201);
        } catch (Exception $error) {
            DB::rollBack();
            $statusCode = method_exists($error, 'getStatusCode') ? $error->httpStatusCode() : 500;

            $message = ($statusCode >= 500) ? 'Ocorreu um erro interno no servidor.' : $error->message();

            return response()->json(['message' => $message], $statusCode);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(String $herdId)
    {
        $herd = Herds::find($herdId);

        if (!$herd) {
            return response()->json(['message' => 'Rebanho não encontrado!'], 404);
        }

        return response()->json(['data' => $herd]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $herdId)
    {
        try {
            DB::beginTransaction();

            $herd = Herds::find($herdId);

            if (!$herd) {
                return response()->json(['message' => 'Rebanho não encontrada!'], 404);
            }

            $validated = $request->validate([
                'species' => 'nullable|string|max:255',
                'property_id' => 'nullable|integer',
                'quantity' => 'nullable|integer',
                'purpose' => 'nullable|string|max:255',
            ]);

            $herd->update($validated);

            DB::commit();

            return response()->json(['message' => 'Rebanho atualizada com sucesso!']);
        } catch (Exception $error) {
            DB::rollBack();
            $statusCode = method_exists($error, 'getStatusCode') ? $error->httpStatusCode() : 500;

            $message = ($statusCode >= 500) ? 'Ocorreu um erro interno no servidor.' : $error->message();

            return response()->json(['message' => $message], $statusCode);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $herdId)
    {
        $herd = Herds::find($herdId);

        if (!$herd) {
            return response()->json(['message' => 'Rebanho não encontrado!'], 404);
        }

        $herd->delete();

        return response()->json(['message' => 'Rebanho deletado com sucesso!']);
    }
}
