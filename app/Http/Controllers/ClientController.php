<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends Controller
{

    public function index()
    {
        return response()->json(Client::all(), Response::HTTP_OK);
    }

    public function store(StoreClientRequest $request)
    {
        $validatedData = $request->validated();

        $client = Client::create($validatedData);

        return response()->json([
            "message" => "Cliente creado con exito",
            "client" => new ClientResource($client)
        ], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $client = Client::find($id);

        if ($client) {
            return response()->json(new ClientResource($client), Response::HTTP_OK);
        }

        return response()->json([
            "message" => "El cliente no existe"
        ], Response::HTTP_NOT_FOUND);
    }

    public function update(UpdateClientRequest $request, $id)
    {
        $validatedData = $request->validated();

        $client = Client::find($id);

        if (!$client) {
            return response()->json([
                "message" => "El cliente no existe"
            ], Response::HTTP_NOT_FOUND);
        }

        $client->update($validatedData);

        return response()->json([
            "message" => "Cliente actualizado con exito",
            "client" => new ClientResource($client)
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json([
                "message" => "El cliente no existe"
            ], Response::HTTP_NOT_FOUND);
        }

        // verificamos si el cliente tiene servicios registrados
        if (sizeof($client->services) > 0) {
            return response()->json([
                "message" => "El cliente tiene servicios registrados"
            ], Response::HTTP_BAD_REQUEST);
        }

        // eliminamos al cliente
        $client->delete();

        return response()->json([
            "message" => "Cliente eliminado con exito",
        ], Response::HTTP_OK);
    }

    public function addService(Request $request)
    {
        // validamos los datos recibidos
        $validate = Validator::make(
            $request->all(),
            [
                'client_id' => ['required'],
                'service_id' => ['required']
            ],
            [
                'client_id.required' => 'El id del cliente es requerido',
                'service_id.required' => 'El id del servicio es requerido',
            ]
        );

        // si algun dato es incorrecto enviamos el mensaje de error
        if ($validate->fails()) {
            return response()->json([
                "message" => $validate->errors()->first()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $client = Client::find($request->only('client_id'))->first();

        $service = Service::find($request->only('service_id'))->first();

        if (!$client) {
            return response()->json([
                "message" => "El cliente no existe"
            ], Response::HTTP_NOT_FOUND);
        }

        if (!$service) {
            return response()->json([
                "message" => "El servicio no existe"
            ], Response::HTTP_NOT_FOUND);
        }

        // verificamos que el cliente no tenga registrado el servicio que se desea registrar
        foreach ($client->services as $service) {
            if ($service->id == $request->only('service_id')['service_id']) {
                return response()->json([
                    "message" => "El cliente ya tiene este servicio registrado"
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        // registramos el archivo
        $client->services()->attach($request->only('service_id')['service_id']);

        // buscamos nuevamente al cliente para mostrar la informacion actualizada
        $client = Client::find($request->only('client_id'))->first();

        return response()->json([
            "message" => "Servicio añadido con exito",
            "client" => new ClientResource($client)
        ], Response::HTTP_OK);
    }

    public function removeService(Request $request)
    {
        // validamos los datos recibidos
        $validate = Validator::make(
            $request->all(),
            [
                'client_id' => ['required'],
                'service_id' => ['required']
            ],
            [
                'client_id.required' => 'El id del cliente es requerido',
                'service_id.required' => 'El id del servicio es requerido',
            ]
        );

        // si algun dato es incorrecto enviamos el mensaje de error
        if ($validate->fails()) {
            return response()->json([
                "message" => $validate->errors()->first()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $client = Client::find($request->only('client_id'))->first();

        $service = Service::find($request->only('service_id'))->first();

        if (!$client) {
            return response()->json([
                "message" => "El cliente no existe"
            ], Response::HTTP_NOT_FOUND);
        }

        if (!$service) {
            return response()->json([
                "message" => "El servicio no existe"
            ], Response::HTTP_NOT_FOUND);
        }

        // variable que chequea la existencia del servicio a eliminar
        $exist = false;

        // verifica que el cliente tenga el servicio registrado
        foreach ($client->services as $service) {
            if ($service->id == $request->only('service_id')['service_id'])
                $exist = true;
        }

        if (!$exist) {
            return response()->json([
                "message" => "El cliente no tiene este servicio registrado" 
            ], Response::HTTP_NOT_FOUND);
        }

        $client->services()->detach($request->only('service_id'));

        // buscamos nuevamente al cliente para mostrar la informacion actualizada
        $client = Client::find($request->only('client_id'))->first();

        return response()->json([
            "message" => "Servicio removido con exito",
            "client" => new ClientResource($client)
        ], Response::HTTP_OK);
    }

    public function removeAllServices($id){

        $client = Client::find($id);

        if(!$client){
            return response()->json([
                "message" => "El cliente no existe"
            ], Response::HTTP_NOT_FOUND);
        }

        $client->services()->detach();

        // buscamos nuevamente al cliente para mostrar la informacion actualizada
        $client = Client::find($id);

        return response()->json([
            "message" => "Servicios removidos con exito",
            "client" => new ClientResource($client)
        ], Response::HTTP_OK);
    }
}
