<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Symfony\Component\HttpFoundation\Response;

class ServiceController extends Controller
{
    public function index()
    {
        return response()->json(ServiceResource::collection(Service::all()), Response::HTTP_OK);
    }

    public function store(StoreServiceRequest $request)
    {
        $validatedData = $request->validated();

        $service = Service::create($validatedData);

        return response()->json([
            "message" => "Servicio creado con exito",
            "service" => $service
        ], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $service = Service::find($id);

        if ($service) {
            return response()->json(new ServiceResource($service), Response::HTTP_OK);
        }

        return response()->json([
            "message" => "El servicio no existe"
        ], Response::HTTP_NOT_FOUND);
    }

    public function update(UpdateServiceRequest $request, $id)
    {
        $validatedData = $request->validated();

        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                "message" => "El servicio no existe"
            ], Response::HTTP_NOT_FOUND);
        }

        $service->update($validatedData);

        return response()->json([
            "message" => "Servicio actualizado con exito",
            "service" => new ServiceResource($service)
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {

        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                "message" => "El servicio no existe"
            ], Response::HTTP_NOT_FOUND);
        }

        // verificamos si hay clientes que tienen el servicio registrado
        if (sizeof($service->clients) > 0) {
            return response()->json([
                "message" => "Algunos clientes tienen el servicio registrado"
            ], Response::HTTP_BAD_REQUEST);
        }

        // eliminamos el servicio
        $service->delete();

        return response()->json([
            "message" => "Servicio eliminado con exito",
        ], Response::HTTP_OK);
    }

    public function removeAllClients($id){

        $service = Service::find($id);

        if(!$service){
            return response()->json([
                "message" => "El servicio no existe"
            ], Response::HTTP_NOT_FOUND);
        }

        // separamos todos los clientes del servicio
        $service->clients()->detach();

        // buscamos nuevamente al servicio para mostrar la informacion actualizada
        $service = Service::find($id);

        return response()->json([
            "message" => "Clientes removidos con exito",
            "client" => new ServiceResource($service)
        ], Response::HTTP_OK);
    }
}
