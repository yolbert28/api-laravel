<?php

namespace App\Http\Controllers;

use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Service::all(),Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['sometimes', 'string', 'max:500'],
            'price' => ['required', 'decimal:2'],
        ]);

        $service = Service::create($validatedData);

        return response()->json([
            "message" => "Servicio creado con exito",
            "service" => $service
        ],Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $service = Service::find($id);

        if($service){
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
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['sometimes', 'string', 'max:500'],
            'price' => ['required', 'decimal:2'],
        ]);

        $service = Service::find($id);

        if(!$service){
            return response()->json([
                "message" => "No existe el servicio con id: {$id}"
            ], Response::HTTP_BAD_REQUEST);
        }
        
        $service->update($validatedData);
        
        return response()->json([
            "message" => "Servicio actualizado con exito",
            "service" => new ServiceResource($service)
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $service = Service::find($id);

        if(!$service){
            return response()->json([
                "message" => "No existe el servicio con id: {$id}"
            ], Response::HTTP_BAD_REQUEST);
        }

        $service->delete();

        return response()->json([
            "message" => "Servicio eliminado con exito",
        ], Response::HTTP_OK);
    }
}
