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
    /**
     *  @OA\Get(
     *      path="/api/client",
     *      tags={"Clients"},
     *      summary="Get list of clients",
     *      description="Return list of clients",
     *      @OA\Response(
     *          response=200,
     *          description="Ok",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  type="object",
     *                  @OA\Property(
     *                      property="id",
     *                      type="integer",
     *                      description="Id of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="Name of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      description="email of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="phone",
     *                      type="string",
     *                      description="Phone of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="address",
     *                      type="string",
     *                      description="Address of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="created_at",
     *                      type="string",
     *                      description="created date of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="updated_at",
     *                      type="string",
     *                      description="updated date of the client"
     *                  )
     *              )
     *           )
     *       )
     *  )
     */
    public function index()
    {
        return response()->json(Client::all(), Response::HTTP_OK);
    }

    /**
     *  @OA\Post(
     *      path="/api/client",
     *      tags={"Clients"},
     *      summary="Create a client",
     *      description="Create a client",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "email"},
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", example="john.doe@examp.com"),
     *              @OA\Property(property="phone", type="string", example="04125748345"),
     *              @OA\Property(property="address", type="string", example="Venezuela")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Created",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  description="message"
     *              ),
     *              @OA\Property(
     *                  property="client",
     *                  type="object",  
     *                  @OA\Property(
     *                      property="id",
     *                      type="integer",
     *                      description="Id of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="Name of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      description="email of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="phone",
     *                      type="string",
     *                      description="Phone of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="address",
     *                      type="string",
     *                      description="Address of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="created_at",
     *                      type="string",
     *                      description="created date of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="updated_at",
     *                      type="string",
     *                      description="updated date of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="services",
     *                      type="array",
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(
     *                              property="id",
     *                              type="number",
     *                              description="id of the service"
     *                          ),
     *                          @OA\Property(
     *                              property="name",
     *                              type="string",
     *                              description="name of the service"
     *                          ),
     *                          @OA\Property(
     *                              property="description",
     *                              type="string",
     *                              description="description of the service"
     *                          ),
     *                          @OA\Property(
     *                              property="price",
     *                              type="string",
     *                              description="price of the service"
     *                          ),
     *                          @OA\Property(
     *                              property="created_at",
     *                              type="string",
     *                              description="created date of the service"
     *                          ),
     *                          @OA\Property(
     *                              property="updated_at",
     *                              type="string",
     *                              description="updated date of the service"
     *                          )
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
     *       )
     *  )
     */
    public function store(StoreClientRequest $request)
    {
        $validatedData = $request->validated();

        $client = Client::create($validatedData);

        return response()->json([
            "message" => "Cliente creado con exito",
            "client" => new ClientResource($client)
        ], Response::HTTP_CREATED);
    }

    /**
     *  @OA\Get(
     *      path="/api/client/{id}",
     *      tags={"Clients"},
     *      summary="Find a client",
     *      description="Return a client",
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
     *                  type="integer",
     *                  description="Id of the client"
     *              ),
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  description="Name of the client"
     *              ),
     *              @OA\Property(
     *                  property="email",
     *                  type="string",
     *                  description="email of the client"
     *              ),
     *              @OA\Property(
     *                  property="phone",
     *                  type="string",
     *                  description="Phone of the client"
     *              ),
     *              @OA\Property(
     *                  property="address",
     *                  type="string",
     *                  description="Address of the client"
     *              ),
     *              @OA\Property(
     *                  property="created_at",
     *                  type="string",
     *                  description="created date of the client"
     *              ),
     *              @OA\Property(
     *                  property="updated_at",
     *                  type="string",
     *                  description="updated date of the client"
     *              ),
     *              @OA\Property(
     *                  property="services",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(
     *                          property="id",
     *                          type="number",
     *                          description="id of the service"
     *                      ),
     *                      @OA\Property(
     *                          property="name",
     *                          type="string",
     *                          description="name of the service"
     *                      ),
     *                      @OA\Property(
     *                          property="description",
     *                          type="string",
     *                          description="description of the service"
     *                      ),
     *                      @OA\Property(
     *                          property="price",
     *                          type="string",
     *                          description="price of the service"
     *                      ),
     *                      @OA\Property(
     *                          property="created_at",
     *                          type="string",
     *                          description="created date of the service"
     *                      ),
     *                      @OA\Property(
     *                          property="updated_at",
     *                          type="string",
     *                          description="updated date of the service"
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
        $client = Client::find($id);

        if ($client) {
            return response()->json(new ClientResource($client), Response::HTTP_OK);
        }

        return response()->json([
            "message" => "El cliente no existe"
        ], Response::HTTP_NOT_FOUND);
    }

    /**
     *  @OA\Put(
     *      path="/api/client/{id}",
     *      tags={"Clients"},
     *      summary="Update a client",
     *      description="Update a client",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "email"},
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", example="john.doe@examp.com"),
     *              @OA\Property(property="phone", type="string", example="04125748345"),
     *              @OA\Property(property="address", type="string", example="Venezuela")
     *          )
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
     *              ),
     *              @OA\Property(
     *                  property="client",
     *                  type="object",  
     *                  @OA\Property(
     *                      property="id",
     *                      type="integer",
     *                      description="Id of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="Name of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      description="email of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="phone",
     *                      type="string",
     *                      description="Phone of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="address",
     *                      type="string",
     *                      description="Address of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="created_at",
     *                      type="string",
     *                      description="created date of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="updated_at",
     *                      type="string",
     *                      description="updated date of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="services",
     *                      type="array",
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(
     *                              property="id",
     *                              type="number",
     *                              description="id of the service"
     *                          ),
     *                          @OA\Property(
     *                              property="name",
     *                              type="string",
     *                              description="name of the service"
     *                          ),
     *                          @OA\Property(
     *                              property="description",
     *                              type="string",
     *                              description="description of the service"
     *                          ),
     *                          @OA\Property(
     *                              property="price",
     *                              type="string",
     *                              description="price of the service"
     *                          ),
     *                          @OA\Property(
     *                              property="created_at",
     *                              type="string",
     *                              description="created date of the service"
     *                          ),
     *                          @OA\Property(
     *                              property="updated_at",
     *                              type="string",
     *                              description="updated date of the service"
     *                          )
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

    /**
     *  @OA\Delete(
     *      path="/api/client/{id}",
     *      tags={"Clients"},
     *      summary="Delete a client",
     *      description="Delete a client",
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

    /**
     *  @OA\Post(
     *      path="/api/client/addService",
     *      tags={"Clients"},
     *      summary="Add a service to a client",
     *      description="Add a service to a client",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"client_id", "service_id"},
     *              @OA\Property(property="client_id", type="integer", example=1),
     *              @OA\Property(property="service_id", type="integer", example=2)
     *          )
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
     *              ),
     *              @OA\Property(
     *                  property="client",
     *                  type="object",  
     *                  @OA\Property(
     *                      property="id",
     *                      type="integer",
     *                      description="Id of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="Name of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      description="email of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="phone",
     *                      type="string",
     *                      description="Phone of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="address",
     *                      type="string",
     *                      description="Address of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="created_at",
     *                      type="string",
     *                      description="created date of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="updated_at",
     *                      type="string",
     *                      description="updated date of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="services",
     *                      type="array",
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(
     *                              property="id",
     *                              type="number",
     *                              description="id of the service"
     *                          ),
     *                          @OA\Property(
     *                              property="name",
     *                              type="string",
     *                              description="name of the service"
     *                          ),
     *                          @OA\Property(
     *                              property="description",
     *                              type="string",
     *                              description="description of the service"
     *                          ),
     *                          @OA\Property(
     *                              property="price",
     *                              type="string",
     *                              description="price of the service"
     *                          ),
     *                          @OA\Property(
     *                              property="created_at",
     *                              type="string",
     *                              description="created date of the service"
     *                          ),
     *                          @OA\Property(
     *                              property="updated_at",
     *                              type="string",
     *                              description="updated date of the service"
     *                          )
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
     *               )
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

    /**
     *  @OA\Post(
     *      path="/api/client/removeService",
     *      tags={"Clients"},
     *      summary="Remove a service to a client",
     *      description="Remove a service to a client",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"client_id", "service_id"},
     *              @OA\Property(property="client_id", type="integer", example=1),
     *              @OA\Property(property="service_id", type="integer", example=2)
     *          )
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
     *              ),
     *              @OA\Property(
     *                  property="client",
     *                  type="object",  
     *                  @OA\Property(
     *                      property="id",
     *                      type="integer",
     *                      description="Id of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="Name of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      description="email of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="phone",
     *                      type="string",
     *                      description="Phone of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="address",
     *                      type="string",
     *                      description="Address of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="created_at",
     *                      type="string",
     *                      description="created date of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="updated_at",
     *                      type="string",
     *                      description="updated date of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="services",
     *                      type="array",
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(
     *                              property="id",
     *                              type="number",
     *                              description="id of the service"
     *                          ),
     *                          @OA\Property(
     *                              property="name",
     *                              type="string",
     *                              description="name of the service"
     *                          ),
     *                          @OA\Property(
     *                              property="description",
     *                              type="string",
     *                              description="description of the service"
     *                          ),
     *                          @OA\Property(
     *                              property="price",
     *                              type="string",
     *                              description="price of the service"
     *                          ),
     *                          @OA\Property(
     *                              property="created_at",
     *                              type="string",
     *                              description="created date of the service"
     *                          ),
     *                          @OA\Property(
     *                              property="updated_at",
     *                              type="string",
     *                              description="updated date of the service"
     *                          )
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

    
    /**
     *  @OA\Post(
     *      path="/api/client/removeAllService/{id}",
     *      tags={"Clients"},
     *      summary="Remove all services from a client",
     *      description="Remove all services from a client",
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
     *              ),
     *              @OA\Property(
     *                  property="client",
     *                  type="object",  
     *                  @OA\Property(
     *                      property="id",
     *                      type="integer",
     *                      description="Id of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="Name of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      description="email of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="phone",
     *                      type="string",
     *                      description="Phone of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="address",
     *                      type="string",
     *                      description="Address of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="created_at",
     *                      type="string",
     *                      description="created date of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="updated_at",
     *                      type="string",
     *                      description="updated date of the client"
     *                  ),
     *                  @OA\Property(
     *                      property="services",
     *                      type="array",
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(
     *                              property="id",
     *                              type="number",
     *                              description="id of the service"
     *                          ),
     *                          @OA\Property(
     *                              property="name",
     *                              type="string",
     *                              description="name of the service"
     *                          ),
     *                          @OA\Property(
     *                              property="description",
     *                              type="string",
     *                              description="description of the service"
     *                          ),
     *                          @OA\Property(
     *                              property="price",
     *                              type="string",
     *                              description="price of the service"
     *                          ),
     *                          @OA\Property(
     *                              property="created_at",
     *                              type="string",
     *                              description="created date of the service"
     *                          ),
     *                          @OA\Property(
     *                              property="updated_at",
     *                              type="string",
     *                              description="updated date of the service"
     *                          )
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
