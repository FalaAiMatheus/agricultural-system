<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Policies\RolePolicy;
use Illuminate\Foundation\Exceptions\Renderer\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $properties = Property::all();

        return response()->json(['data' => $properties]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
            'uf' => 'required|string|max:255',
            'state_registration' => 'required|integer|max:255',
            'total_area' => 'required',
            'producer_id' => 'required|integer',
        ], [
            'name.required' => 'Nome é obrigatório',
            'municipality.required' => 'Município é obrigatório',
            'uf.required' => 'UF é obrigatória',
            'state_registration.required' => 'Inscrição estadual é obrigatória',
            'total_area.required' => 'Area total é obrigatória',
            'producer_id.required' => 'Id do Produtor é obrigatório',
        ]);

        try {
            DB::beginTransaction();

            Property::create($validated);

            DB::commit();

            return response()->json(['message' => 'Propriedade inserido com sucesso'], 201);
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
    public function show(String $propertyId)
    {
        $property = Property::find($propertyId);

        if (!$property) {
            return response()->json(['message' => 'Propriedade não encontrada!'], 404);
        }

        return response()->json(['data' => $property]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $propertyId)
    {
        try {
            DB::beginTransaction();

            $property = Property::find($propertyId);

            if (!$property) {
                return response()->json(['message' => 'Propriedade não encontrada!'], 404);
            }

            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'municipality' => 'nullable|string|max:255',
                'uf' => 'nullable|string|max:255',
                'state_registration' => 'nullable|integer|max:255',
                'total_area' => 'nullable',
                'producer_id' => 'nullable|integer',
            ]);

            $property->update($validated);

            DB::commit();

            return response()->json(['message' => 'Propriedade atualizada com sucesso']);
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
    public function destroy(String $propertyId)
    {

        $property = Property::find($propertyId);

        if (!$property) {
            return response()->json(['message' => 'Propriedade não encontrada!'], 404);
        }

        $property->delete();

        return response()->json(['message' => 'Propriedade deletada com sucesso']);
    }
}
