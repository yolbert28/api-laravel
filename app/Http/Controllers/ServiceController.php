<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Symfony\Component\HttpFoundation\Response;

class ServiceController extends Controller
{
    /**
     *  @OA\Get(
     *      path="/api/service",
     *      tags={"Services"},
     *      summary="Get list of services",
     *      description="Return list of services",
     *      @OA\Response(
     *          response=200,
     *          description="Ok",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="id",
     *                  type="number",
     *                  description="id of the service"
     *              ),
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  description="name of the service"
     *              ),
     *              @OA\Property(
     *                  property="description",
     *                  type="string",
     *                  description="description of the service"
     *              ),
     *              @OA\Property(
     *                  property="price",
     *                  type="string",
     *                  description="price of the service"
     *              ),
     *              @OA\Property(
     *                  property="created_at",
     *                  type="string",
     *                  description="created date of the service"
     *              ),
     *              @OA\Property(
     *                  property="updated_at",
     *                  type="string",
     *                  description="updated date of the service"
     *              ),
     *              @OA\Property(
     *                  property="clients",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          description="Id of the client"
     *                      ),
     *                      @OA\Property(
     *                          property="name",
     *                          type="string",
     *                          description="Name of the client"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string",
     *                          description="email of the client"
     *                      ),
     *                      @OA\Property(
     *                          property="phone",
     *                          type="string",
     *                          description="Phone of the client"
     *                      ),
     *                      @OA\Property(
     *                          property="address",
     *                          type="string",
     *                          description="Address of the client"
     *                      ),
     *                      @OA\Property(
     *                          property="created_at",
     *                          type="string",
     *                          description="created date of the client"
     *                      ),
     *                      @OA\Property(
     *                          property="updated_at",
     *                          type="string",
     *                          description="updated date of the client"
     *                      )
     *                  )
     *              )
     *           )
     *       )
     *  )
     */
    public function index()
    {
        return response()->json(ServiceResource::collection(Service::all()), Response::HTTP_OK);
    }

    /**
     *  @OA\Post(
     *      path="/api/service",
     *      tags={"Services"},
     *      summary="Create a service",
     *      description="Create a service",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "price"},
     *              @OA\Property(property="name", type="string", example="electricidad"),
     *              @OA\Property(property="description", type="string", example="servicio electrico"),
     *              @OA\Property(property="price", type="string", example=50.00)
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Created",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  type="object",
     *                  @OA\Property(
     *                      property="id",
     *                      type="number",
     *                      description="id of the service"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="name of the service"
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="string",
     *                      description="description of the service"
     *                  ),
     *                  @OA\Property(
     *                      property="price",
     *                      type="string",
     *                      description="price of the service"
     *                  ),
     *                  @OA\Property(
     *                      property="created_at",
     *                      type="string",
     *                      description="created date of the service"
     *                  ),
     *                  @OA\Property(
     *                      property="updated_at",
     *                      type="string",
     *                      description="updated date of the service"
     *                  )
     *              )
     *           )
     *       ),
     *       @OA\Response(
     *           response=422,
     *           description="Unprocessable Content",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               ),
     *               @OA\Property(
     *                   property="errors",
     *                   type="object",
     *                   @OA\Property(
     *                       property="name",
     *                       type="array",
     *                       @OA\Items()
     *                   ),
     *                   @OA\Property(
     *                       property="email",
     *                       type="array",
     *                       @OA\Items()
     *                   )
     *               )
     *           )
     *       )
     *  )
     */
    public function store(StoreServiceRequest $request)
    {
        $validatedData = $request->validated();

        $service = Service::create($validatedData);

        return response()->json([
            "message" => "Servicio creado con exito",
            "service" => $service
        ], Response::HTTP_CREATED);
    }

    /**
     *  @OA\Get(
     *      path="/api/service/{id}",
     *      tags={"Services"},
     *      summary="Find a service",
     *      description="Return a service",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Ok",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="id",
     *                  type="number",
     *                  description="id of the service"
     *              ),
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  description="name of the service"
     *              ),
     *              @OA\Property(
     *                  property="description",
     *                  type="string",
     *                  description="description of the service"
     *              ),
     *              @OA\Property(
     *                  property="price",
     *                  type="string",
     *                  description="price of the service"
     *              ),
     *              @OA\Property(
     *                  property="created_at",
     *                  type="string",
     *                  description="created date of the service"
     *              ),
     *              @OA\Property(
     *                  property="updated_at",
     *                  type="string",
     *                  description="updated date of the service"
     *              ),
     *              @OA\Property(
     *                  property="clients",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          description="Id of the client"
     *                      ),
     *                      @OA\Property(
     *                          property="name",
     *                          type="string",
     *                          description="Name of the client"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string",
     *                          description="email of the client"
     *                      ),
     *                      @OA\Property(
     *                          property="phone",
     *                          type="string",
     *                          description="Phone of the client"
     *                      ),
     *                      @OA\Property(
     *                          property="address",
     *                          type="string",
     *                          description="Address of the client"
     *                      ),
     *                      @OA\Property(
     *                          property="created_at",
     *                          type="string",
     *                          description="created date of the client"
     *                      ),
     *                      @OA\Property(
     *                          property="updated_at",
     *                          type="string",
     *                          description="updated date of the client"
     *                      )
     *                  )
     *              )
     *           )
     *       ),
     *       @OA\Response(
     *           response=404,
     *           description="Not found",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       )
     *  )
     */
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
    
    /**
     *  @OA\Put(
     *      path="/api/service/{id}",
     *      tags={"Services"},
     *      summary="Update a service",
     *      description="Update a service",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "price"},
     *              @OA\Property(property="name", type="string", example="electricidad"),
     *              @OA\Property(property="description", type="string", example="servicio electrico"),
     *              @OA\Property(property="price", type="string", example=50.00)
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Ok",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="id",
     *                  type="number",
     *                  description="id of the service"
     *              ),
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  description="name of the service"
     *              ),
     *              @OA\Property(
     *                  property="description",
     *                  type="string",
     *                  description="description of the service"
     *              ),
     *              @OA\Property(
     *                  property="price",
     *                  type="string",
     *                  description="price of the service"
     *              ),
     *              @OA\Property(
     *                  property="created_at",
     *                  type="string",
     *                  description="created date of the service"
     *              ),
     *              @OA\Property(
     *                  property="updated_at",
     *                  type="string",
     *                  description="updated date of the service"
     *              ),
     *              @OA\Property(
     *                  property="clients",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          description="Id of the client"
     *                      ),
     *                      @OA\Property(
     *                          property="name",
     *                          type="string",
     *                          description="Name of the client"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string",
     *                          description="email of the client"
     *                      ),
     *                      @OA\Property(
     *                          property="phone",
     *                          type="string",
     *                          description="Phone of the client"
     *                      ),
     *                      @OA\Property(
     *                          property="address",
     *                          type="string",
     *                          description="Address of the client"
     *                      ),
     *                      @OA\Property(
     *                          property="created_at",
     *                          type="string",
     *                          description="created date of the client"
     *                      ),
     *                      @OA\Property(
     *                          property="updated_at",
     *                          type="string",
     *                          description="updated date of the client"
     *                      )
     *                  )
     *              )
     *           )
     *       ),
     *       @OA\Response(
     *           response=422,
     *           description="Unprocessable Content",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               ),
     *               @OA\Property(
     *                   property="errors",
     *                   type="object",
     *                   @OA\Property(
     *                       property="name",
     *                       type="array",
     *                       @OA\Items()
     *                   ),
     *                   @OA\Property(
     *                       property="email",
     *                       type="array",
     *                       @OA\Items()
     *                   )
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=404,
     *           description="Not found",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       )
     *  )
     */
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

    /**
     *  @OA\Delete(
     *      path="/api/service/{id}",
     *      tags={"Services"},
     *      summary="Delete a service",
     *      description="Delete a service",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Ok",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  description="message"
     *              )
     *           )
     *       ),
     *       @OA\Response(
     *           response=400,
     *           description="Bad Request",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=404,
     *           description="Not found",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       )
     *  )
     */ 
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

    /**
     *  @OA\Post(
     *      path="/api/service/removeAllClient/{id}",
     *      tags={"Services"},
     *      summary="Remove all clients from a service",
     *      description="Remove all clients from a service",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Ok",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="id",
     *                  type="number",
     *                  description="id of the service"
     *              ),
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  description="name of the service"
     *              ),
     *              @OA\Property(
     *                  property="description",
     *                  type="string",
     *                  description="description of the service"
     *              ),
     *              @OA\Property(
     *                  property="price",
     *                  type="string",
     *                  description="price of the service"
     *              ),
     *              @OA\Property(
     *                  property="created_at",
     *                  type="string",
     *                  description="created date of the service"
     *              ),
     *              @OA\Property(
     *                  property="updated_at",
     *                  type="string",
     *                  description="updated date of the service"
     *              ),
     *              @OA\Property(
     *                  property="clients",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          description="Id of the client"
     *                      ),
     *                      @OA\Property(
     *                          property="name",
     *                          type="string",
     *                          description="Name of the client"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string",
     *                          description="email of the client"
     *                      ),
     *                      @OA\Property(
     *                          property="phone",
     *                          type="string",
     *                          description="Phone of the client"
     *                      ),
     *                      @OA\Property(
     *                          property="address",
     *                          type="string",
     *                          description="Address of the client"
     *                      ),
     *                      @OA\Property(
     *                          property="created_at",
     *                          type="string",
     *                          description="created date of the client"
     *                      ),
     *                      @OA\Property(
     *                          property="updated_at",
     *                          type="string",
     *                          description="updated date of the client"
     *                      )
     *                  )
     *              )
     *           )
     *       ),
     *       @OA\Response(
     *           response=404,
     *           description="Not found",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       )
     *  )
     */ 
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
