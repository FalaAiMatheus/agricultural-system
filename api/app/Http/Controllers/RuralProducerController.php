<?php

namespace App\Http\Controllers;

use App\Models\RuralProducer;
use Illuminate\Foundation\Exceptions\Renderer\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RuralProducerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ruralProducers = RuralProducer::all();

        return response()->json(['data' => $ruralProducers]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'document' => 'required|string|max:255|unique:rural_producers',
            'email' => 'required|string|email|max:255|unique:rural_producers',
            'telephone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ], [
            'name.required' => 'Nome é obrigatório',
            'document.required' => 'Documento é obrigatório',
            'document.unique' => 'Documento já existe',
            'email.required' => 'Email é obrigatório',
            'email.unique' => 'Email já existe',
            'telephone.required' => 'Telefone é obrigatório',
            'address.required' => 'Endereço é obrigatório',
        ]);

        try {
            DB::beginTransaction();

            RuralProducer::create([...$validated, 'registration_date' => now()]);

            DB::commit();

            return response()->json(['message' => 'Produtor rural inserido com sucesso'], 201);
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
    public function show(String $producerId)
    {
        $ruralProducer = RuralProducer::find($producerId);

        if (!$ruralProducer) {
            return response()->json(['message' => 'Produtor rural não encontrado!'], 404);
        }

        return response()->json(['data' => $ruralProducer]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $producerId)
    {

        try {
            DB::beginTransaction();

            $ruralProducer = RuralProducer::find($producerId);

            if (!$ruralProducer) {
                return response()->json(['message' => 'Produtor rural não encontrado!'], 404);
            }

            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'document' => 'nullable|string|max:255|unique:rural_producers',
                'email' => 'nullable|string|email|max:255|unique:rural_producers',
                'telephone' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:255',
            ], [
                'document.unique' => 'Documento já existe',
                'email.unique' => 'Email já existe',
            ]);

            $ruralProducer->update([...$validated, 'registration_date' => now()]);

            DB::commit();

            return response()->json(['message' => 'Produtor rural atualizado com sucesso']);
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
    public function destroy(String $producerId)
    {

        $ruralProducer = RuralProducer::find($producerId);

        if (!$ruralProducer) {
            return response()->json(['message' => 'Produtor rural não encontrado!'], 404);
        }

        $ruralProducer->delete();

        return response()->json(['message' => 'Produtor rural deletado com sucesso']);
    }
}
