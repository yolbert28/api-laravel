<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Client::all(), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        $client = Client::create($request->all());

        return response()->json([
            "message" => "Cliente creado con exito",
            "client" => $client
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $client = Client::find($id);

        if ($client) {
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
        $validate = Validator::make($request->all(), [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'max:255'],
            'phone' => ['sometimes', 'string', 'max:255'],
            'address' => ['sometimes', 'string', 'max:255'],
        ]);

        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $client = Client::find($id);

        if (!$client) {
            return response()->json([
                "message" => "No existe el cliente con id: {$id}"
            ], Response::HTTP_BAD_REQUEST);
        }

        $client->update($request->all());

        return response()->json([
            "message" => "Cliente actualizado con exito",
            "client" => new ClientResource($client)
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, $force = false)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json([
                "message" => "No existe el cliente con id: {$id}"
            ], Response::HTTP_BAD_REQUEST);
        }

        if ((sizeof($client->services) > 0) && !$force) {
            return response()->json([
                "message" => "El cliente tiene servicios registrados"
            ], Response::HTTP_BAD_REQUEST);
        }

        $client->services()->detach();

        $client->delete();

        return response()->json([
            "message" => "Cliente eliminado con exito",
        ], Response::HTTP_OK);
    }

    public function addService(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'client_id' => ['required'],
                'service_id' => ['required']
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $client = Client::find($request->only('client_id'))->first();

        if (!$client) {
            return response()->json([
                "message" => "El cliente no existe"
            ], Response::HTTP_BAD_REQUEST);
        }

        foreach ($client->services as $service) {
            if ($service->id == $request->only('service_id')['service_id']) {
                return response()->json([
                    "message" => "El cliente ya tiene este servicio registrado"
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        $client->services()->attach($request->only('service_id')['service_id']);

        $client = Client::find($request->only('client_id'))->first();

        return response()->json([
            "message" => "Servicio añadido con exito",
            "client" => new ClientResource($client)
        ], Response::HTTP_OK);
    }

    public function removeService(Request $request, $all = false)
    {
        $rules = $all ? [
            'client_id' => ['required']
        ] : [
            'client_id' => ['required'],
            'service_id' => ['required']
        ];

        // return $rules;

        $validate = Validator::make(
            $request->all(),
            $rules
        );

        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $client = Client::find($request->only('client_id'))->first();

        if (!$client) {
            return response()->json([
                "message" => "El cliente no existe"
            ], Response::HTTP_BAD_REQUEST);
        }

        $exist = false;

        if (!$all) {
            foreach ($client->services as $service) {
                if ($service->id == $request->only('service_id')['service_id'])
                    $exist = true;
            }

            if (!$exist) {
                return response()->json([
                    "message" => "El cliente no tiene este servicio registrado"
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        if ($all) {
            $client->services()->detach();
        } else {
            $client->services()->detach($request->only('service_id'));
        }

        $client = Client::find($request->only('client_id'))->first();

        return response()->json([
            "message" => "Servicio eliminado con exito",
            "client" => new ClientResource($client),
            "all" => $all
        ], Response::HTTP_OK);
    }
}
