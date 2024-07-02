<?php

namespace App\Http\Controllers;

use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Service::all(), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['sometimes', 'string', 'max:500'],
            'price' => ['required', 'decimal:2'],
        ]);

        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $validatedData = $request->all();

        $service = Service::create($validatedData);

        return response()->json([
            "message" => "Servicio creado con exito",
            "service" => $service
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $service = Service::find($id);

        if ($service) {
            return response()->json(new ServiceResource($service), Response::HTTP_OK);
        }

        return response()->json([
            "message" => "No existe el servicio con id: {$id}"
        ], Response::HTTP_BAD_REQUEST);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['sometimes', 'string', 'max:500'],
            'price' => ['required', 'decimal:2'],
        ]);

        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                "message" => "No existe el servicio con id: {$id}"
            ], Response::HTTP_BAD_REQUEST);
        }

        $service->update($request->all());

        return response()->json([
            "message" => "Servicio actualizado con exito",
            "service" => new ServiceResource($service)
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, $force = false)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                "message" => "No existe el servicio con id: {$id}"
            ], Response::HTTP_BAD_REQUEST);
        }

        if ((sizeof($service->clients) > 0) && !$force) {
            return response()->json([
                "message" => "Algunos clientes tienen el servicio registrado"
            ], Response::HTTP_BAD_REQUEST);
        }

        $service->clients()->detach();

        $service->delete();

        return response()->json([
            "message" => "Servicio eliminado con exito",
        ], Response::HTTP_OK);
    }
}
