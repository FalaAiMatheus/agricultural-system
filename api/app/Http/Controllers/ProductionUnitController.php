<?php

namespace App\Http\Controllers;

use App\Models\ProductionUnit;
use Illuminate\Foundation\Exceptions\Renderer\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productionUnits = ProductionUnit::with('properties')->get();

        return response()->json(['data' => $productionUnits]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'culture_name' => 'required|string|max:255',
            'property_id' => 'required|integer',
            'total_area_ha' => 'required|decimal:4',
            'latitude' => 'required|decimal:8',
            'longitude' => 'required|decimal:8',
        ], [
            'culture_name.required' => 'Nome da cultura é obrigatório',
            'property_id.required' => 'Id da Propriedade é obrigatório',
            'total_area_ha.required' => 'Area total é obrigatória',
            'latitude.required' => 'Latitude é obrigatória',
            'longitude.required' => 'Longitude é obrigatória',
            'total_area_ha.decimal' => 'Area total invalida',
            'latitude.decimal' => 'Latitude invalida',
            'longitude.decimal' => 'Longitude invalida',
        ]);

        try {
            DB::beginTransaction();

            ProductionUnit::create($validated);

            DB::commit();

            return response()->json(['message' => 'Unidade de produção inserida com sucesso'], 201);
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
    public function show(String $productionUnitId)
    {
        $productionUnit = ProductionUnit::with('properties')->find($productionUnitId);

        if (!$productionUnit) {
            return response()->json(['message' => 'Unidade de produção não encontrada!'], 404);
        }

        return response()->json(['data' => $productionUnit]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $productionUnitId)
    {
        try {
            DB::beginTransaction();

            $productionUnit = ProductionUnit::find($productionUnitId);

            if (!$productionUnit) {
                return response()->json(['message' => 'Unidade de produção não encontrada!'], 404);
            }

            $validated = $request->validate([
                'culture_name' => 'nullable|string|max:255',
                'property_id' => 'nullable|integer',
                'total_area_ha' => 'nullable|decimal:12,4',
                'latitude' => 'nullable|decimal:10,8',
                'longitude' => 'nullable|decimal:11,8',
            ], [
                'total_area_ha.decimal' => 'Area total invalida',
                'latitude.decimal' => 'Latitude invalida',
                'longitude.decimal' => 'Longitude invalida',
            ]);

            $productionUnit->update($validated);

            DB::commit();

            return response()->json(['message' => 'Unidade de produção atualizada com sucesso!']);
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
    public function destroy(String $productionUnitId)
    {
        $productionUnit = ProductionUnit::find($productionUnitId);

        if (!$productionUnit) {
            return response()->json(['message' => 'Unidade de produção não encontrada!'], 404);
        }

        $productionUnit->delete();

        return response()->json(['message' => 'Unidade de produção deletada com sucesso!']);
    }
}
