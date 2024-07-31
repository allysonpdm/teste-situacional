<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Empresa\{
    IndexRequest,
    StoreRequest,
    UpdateRequest
};
use App\Models\Empresa;
use App\Models\User;
use App\Services\Api\EmpresaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class EmpresaController extends Controller
{
    protected ?User $user;

    public function __construct(protected EmpresaService $service)
    {
        $this->middleware('auth:api');

        $this->authorizeResource(
            model: Empresa::class,
            parameter: 'empresa'
        );

        $this->user = Auth::user();
    }

    /**
     * @OA\Get(
     *     path="/api/empresa",
     *     summary="Get all enterprises",
     *     description="Get all enterprises of the current user",
     *     tags={"Empresa"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of results per page",
     *         required=false,
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Parameter(
     *         name="where",
     *         in="query",
     *         description="Filter results based on conditions",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(
     *                 type="array",
     *                 @OA\Items(
     *                     type="string",
     *                     description="Column name",
     *                     example="status"
     *                 ),
     *                 @OA\Items(
     *                     type="string",
     *                     description="Operator",
     *                     example="=",
     *                     enum={"LIKE", "NOT LIKE", "IS NOT NULL", "IS NULL", "IN", "=", "<", ">", "<=", ">=", "<>", "!="}
     *                 ),
     *                 @OA\Items(
     *                     type="string",
     *                     description="Value to compare",
     *                     example="active"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved all enterprises of the user",
     *         @OA\JsonContent(
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=11),
     *                     @OA\Property(property="user_id", type="string", example="1"),
     *                     @OA\Property(property="razao_social", type="string", example="Razão social inc"),
     *                     @OA\Property(property="nome_fantasia", type="string", example="Nome fantasia"),
     *                     @OA\Property(property="cnpj", type="string", example="66706525623802"),
     *                     @OA\Property(property="status", type="string", example="ativa"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-30T20:35:35.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-30T20:35:35.000000Z")
     *                 )
     *             ),
     *             @OA\Property(property="first_page_url", type="string", example="http://api.testesituacional.com.br/api/empresa?page=1"),
     *             @OA\Property(property="from", type="integer", nullable=true, example=null),
     *             @OA\Property(property="last_page", type="integer", example=1),
     *             @OA\Property(property="last_page_url", type="string", example="http://api.testesituacional.com.br/api/empresa?page=1"),
     *             @OA\Property(property="links", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="url", type="string", nullable=true, example=null),
     *                     @OA\Property(property="label", type="string", example="&laquo; Previous"),
     *                     @OA\Property(property="active", type="boolean", example=false)
     *                 )
     *             ),
     *             @OA\Property(property="next_page_url", type="string", nullable=true, example=null),
     *             @OA\Property(property="path", type="string", example="http://api.testesituacional.com.br/api/empresa"),
     *             @OA\Property(property="per_page", type="integer", example=15),
     *             @OA\Property(property="prev_page_url", type="string", nullable=true, example=null),
     *             @OA\Property(property="to", type="integer", nullable=true, example=null),
     *             @OA\Property(property="total", type="integer", example=1)
     *         )
     *     )
     * )
     */
    public function index(IndexRequest $request): JsonResponse
    {
        return response()->json(
            data: $this->service->index(request: $request->validated(), user: $this->user),
            status: Response::HTTP_OK
        );
    }

    /**
     * @OA\Get(
     *     path="/api/empresa/{id}",
     *     summary="Get specific enterprise",
     *     description="Get specific enterprise by id",
     *     tags={"Empresa"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the enterprise",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved specific enterprise",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=11),
     *             @OA\Property(property="user_id", type="string", example="1"),
     *             @OA\Property(property="razao_social", type="string", example="Razão social inc"),
     *             @OA\Property(property="nome_fantasia", type="string", example="Nome fantasia"),
     *             @OA\Property(property="cnpj", type="string", example="66706525623802"),
     *             @OA\Property(property="status", type="string", example="ativa"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-30T20:35:35.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-30T20:35:35.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No query results for model [App\Models\Empresa] 10000")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *         )
     *     )
     * )
     */
    public function show(Empresa $empresa): JsonResponse
    {
        return response()->json($this->service->show($empresa));
    }

    /**
     * @OA\Post(
     *     path="/api/empresa",
     *     summary="Create a new enterprise",
     *     description="Create a new enterprise with the provided details",
     *     tags={"Empresa"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"razao_social", "cnpj"},
     *                 @OA\Property(
     *                     property="razao_social",
     *                     type="string",
     *                     description="Company's legal name",
     *                     example="Razão social inc"
     *                 ),
     *                 @OA\Property(
     *                     property="nome_fantasia",
     *                     type="string",
     *                     description="Company's trade name",
     *                     example="Nome fantasia"
     *                 ),
     *                 @OA\Property(
     *                     property="cnpj",
     *                     type="string",
     *                     description="Company's CNPJ number",
     *                     example="66706525623802"
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     description="Company status",
     *                     enum={"ativa", "desabilitada", "pendente"},
     *                     example="ativa"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successfully created new enterprise",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="razao_social", type="string", example="Razão social inc"),
     *             @OA\Property(property="nome_fantasia", type="string", example="Nome fantasia"),
     *             @OA\Property(property="cnpj", type="string", example="66706525623802"),
     *             @OA\Property(property="status", type="string", example="ativa"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-30T20:35:35.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-30T20:35:35.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Content",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The cnpj has already been taken."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="cnpj",
     *                     type="array",
     *                     @OA\Items(type="string", example="The cnpj has already been taken.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function store(StoreRequest $request): JsonResponse
    {
        return response()->json($this->service->store($request->validated(), $this->user), Response::HTTP_CREATED);
    }

    /**
     * @OA\Put(
     *     path="/api/empresa/{id}",
     *     summary="Update an enterprise",
     *     description="Update the details of an existing enterprise",
     *     tags={"Empresa"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the enterprise to be updated",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"razao_social", "cnpj"},
     *             @OA\Property(property="razao_social", type="string", example="Updated Enterprise Name"),
     *             @OA\Property(property="nome_fantasia", type="string", example="Updated Enterprise Name Inc"),
     *             @OA\Property(property="status", type="string", example="ativa"),
     *             @OA\Property(property="cnpj", type="string", example="12345678901234")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Enterprise updated successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Enterprise updated successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No query results for model [App\Models\Empresa] 10000")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to update the enterprise.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to update the enterprise.")
     *         )
     *     )
     * )
     */
    public function update(UpdateRequest $request, Empresa $empresa): JsonResponse
    {
        if ($this->service->update($request->validated(), $empresa)) {
            return response()->json(['message' => 'Enterprise updated successfully.'], Response::HTTP_OK);
        }

        return response()->json(['message' => 'Failed to update the enterprise.'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }


    /**
     * @OA\Delete(
     *     path="/api/empresa/{id}",
     *     summary="Delete an enterprise",
     *     description="Delete an enterprise by id",
     *     tags={"Empresa"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the enterprise to be deleted",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Enterprise deleted successfully.",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No query results for model [App\Models\Empresa] 10000")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to remove the enterprise.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to remove the enterprise.")
     *         )
     *     )
     * )
     */
    public function destroy(Empresa $empresa): JsonResponse
    {
        if ($this->service->destroy($empresa)) {
            return response()->json(null, Response::HTTP_NO_CONTENT);
        }

        return response()->json(['message' => 'Failed to remove the enterprise.'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
