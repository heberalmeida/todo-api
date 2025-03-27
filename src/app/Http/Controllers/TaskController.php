<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Autenticação via token JWT",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth"
 * )
 */

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected TaskService $service) {}

    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     summary="Listar tarefas do usuário autenticado",
     *     tags={"Tarefas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista paginada de tarefas"
     *     )
     * )
     */

    public function index(): AnonymousResourceCollection
    {
        return TaskResource::collection($this->service->index());
    }

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     summary="Criar nova tarefa",
     *     tags={"Tarefas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="Comprar pão"),
     *             @OA\Property(property="description", type="string", example="Na padaria"),
     *             @OA\Property(property="status", type="string", example="pending"),
     *             @OA\Property(property="due_date", type="string", format="date-time", example="2025-03-28 18:00:00")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Tarefa criada")
     * )
     */

    public function store(TaskRequest $request): TaskResource
    {
        $this->authorize('create', Task::class);

        $task = $this->service->store($request->validated());
        return new TaskResource($task);
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/{id}",
     *     summary="Exibir tarefa específica",
     *     tags={"Tarefas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Detalhes da tarefa")
     * )
     */

    public function show($id): TaskResource
    {
        return new TaskResource($this->service->show($id));
    }

    /**
     * @OA\Put(
     *     path="/api/tasks/{id}",
     *     summary="Atualizar tarefa",
     *     tags={"Tarefas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Novo título"),
     *             @OA\Property(property="status", type="string", example="completed"),
     *             @OA\Property(property="due_date", type="string", format="date-time", example="2025-03-28 18:00:00")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Tarefa atualizada")
     * )
     */

    public function update(TaskRequest $request, Task $task): TaskResource
    {
        $this->authorize('update', $task);

        $task = $this->service->update($task, $request->validated());
        return new TaskResource($task);
    }

    /**
     * @OA\Delete(
     *     path="/api/tasks/{id}",
     *     summary="Deletar tarefa",
     *     tags={"Tarefas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Tarefa removida")
     * )
     */

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $this->service->destroy($task);
        return response()->json(['message' => 'Tarefa removida.']);
    }
}
