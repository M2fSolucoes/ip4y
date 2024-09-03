<?php

namespace App\Http\Controllers;

use App\Repositories\TaskRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    private $taskRepository;

    /**
     * Summary of __construct
     */
    public function __construct()
    {
        $this->taskRepository = new TaskRepository();
    }

    /**
     * Método reponsável por invocar a coleta de todas tarefas
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */

    public function getAll()
    {
        $response = $this->taskRepository->getAllByUser();
        if (!isset($response['code'])) {
            $response['code'] = 200;
        }
        return response()->json($response, $response['code']);
    }

    /**
     * Método reponsável por invocar a inclusão de novas tarefas
     *
     * @param Request $request
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $response = $this->taskRepository->createTask($request->all());
        if (!isset($response['code'])) {
            $response['code'] = 200;
        }
        return response()->json($response, $response['code']);
    }

    /**
     * Método reponsável por invocar a consulta de uma tarefa pelo seu ID
     *
     * @param Request $request
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getById(Request $request)
    {
        $response = $this->taskRepository->getTaskById($request->id);
        if (!isset($response['code'])) {
            $response['code'] = 200;
        }
        return response()->json($response, $response['code']);
    }


    /**
     * Método reponsável por invocar a alteração de uma tarefa
     *
     * @param Request $request
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $response = $this->taskRepository->update($request->all());
        if (!isset($response['code'])) {
            $response['code'] = 200;
        }
        return response()->json($response, $response['code']);

    }

    /**
     * Método reponsável por invocar a alteração de status de uma tarefa
     *
     * @param Request $request
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request)
    {
        $messages = [
            'status.required' => "O campo status deve ser fornecido.",
            'status.in' => "Deve ser fornecido apenas os valores 'progresso' para tarefa em andamento  ou 'concluída' para tarefa finalizada!"
        ];
        $validate = $request->validate([
            'status' => ['required', 'string', 'in:pendente,progresso,concluída,concluida'],

        ], $messages);

        $response = $this->taskRepository->updateStatus($request->all());
        if (!isset($response['code'])) {
            $response['code'] = 200;
        }
        return response()->json($response, $response['code']);

    }

    /**
     * Método reponsável por invocar a exclusão e inclusão de atribuição de uma tarefa
     *
     * @param Request $request
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function updateAllocateds(Request $request)
    {
        $response = $this->taskRepository->updateAllocateds($request->all());
        if (!isset($response['code'])) {
            $response['code'] = 200;
        }
        return response()->json($response, $response['code']);

    }

    /**
     * Método reponsável por invocar o relatorio de tarefas e projetos
     *
     * @param Request $request
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function report(Request $request)
    {
        $response = $this->taskRepository->report($request->all(), $request->getHttpHost());
        if (!isset($response['code'])) {
            $response['code'] = 200;
        }
        return response()->json($response, $response['code']);

    }

    /**
     * Método reponsável por invocar a exclusão  de uma tarefa
     *
     * @param Request $request
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $response = $this->taskRepository->delete($request->all());
        if (!isset($response['code'])) {
            $response['code'] = 200;
        }
        return response()->json($response, $response['code']);
    }
}
