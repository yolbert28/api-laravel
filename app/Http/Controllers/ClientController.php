<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Client::all(),Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:clients'],
            'phone' => ['sometimes', 'string', 'max:255'],
            'address' => ['sometimes', 'string', 'max:255'],
        ]);

        $client = Client::create($validatedData);

        return response()->json([
            "message" => "Cliente creado con exito",
            "client" => $client
        ],Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $client = Client::find($id);

        if($client){
            return response()->json(new ClientResource($client), Response::HTTP_OK);
        }

        return response()->json([
            "message" => "No existe el cliente con id: {$id}"
        ], Response::HTTP_BAD_REQUEST);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'max:255'],
            'phone' => ['sometimes', 'string', 'max:255'],
            'address' => ['sometimes', 'string', 'max:255'],
        ]);

        $client = Client::find($id);

        if(!$client){
            return response()->json([
                "message" => "No existe el cliente con id: {$id}"
            ], Response::HTTP_BAD_REQUEST);
        }
        
        $client->update($validatedData);
        
        return response()->json([
            "message" => "Cliente actualizado con exito",
            "client" => new ClientResource($client)
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $client = Client::find($id);

        if(!$client){
            return response()->json([
                "message" => "No existe el cliente con id: {$id}"
            ], Response::HTTP_BAD_REQUEST);
        }

        $client->delete();

        return response()->json([
            "message" => "Cliente eliminado con exito",
        ], Response::HTTP_OK);
    }
}
