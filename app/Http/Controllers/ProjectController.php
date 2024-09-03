<?php

namespace App\Http\Controllers;

use App\Repositories\ProjectRepository;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    private $projectRepository;

    /** */
    /**
     * Summary of __construct
     */
    public function __construct()
    {
        $this->projectRepository = new ProjectRepository();
    }

    /**
     * Método reponsável por invocar a coleta de todos projetos
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $response = $this->projectRepository->getAll();
        if (!isset($response['code'])) {
            $response['code'] = 200;
        }
        return response()->json($response, $response['code']);
    }


    /**
     * Método reponsável por invocar a  inclusão de uam novo projeto
     *
     * @param Request $request
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $response = $this->projectRepository->createProject($request->all());
        if (!isset($response['code'])) {
            $response['code'] = 200;
        }
        return response()->json($response, $response['code']);
    }

    /**
     * Método reponsável por invocar a  coleta de  de um projeto pelo seu código
     *
     * @param Request $request
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */

    public function getByCode(Request $request)
    {
        $response = $this->projectRepository->getByCode($request->all());
        if (!isset($response['code'])) {
            $response['code'] = 200;
        }
        return response()->json($response, $response['code']);
    }

    /**
     * Método reponsável por invocar a alteração de um projeto
     *
     * @param Request $request
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $response = $this->projectRepository->update($request->all());
        if (!isset($response['code'])) {
            $response['code'] = 200;
        }
        return response()->json($response, $response['code']);
    }

    /**
     * Método reponsável por invocar a exclusão de um projeto
     *
     * @param Request $request
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $response = $this->projectRepository->delete($request->all());
        if (!isset($response['code'])) {
            $response['code'] = 200;
        }
        return response()->json($response, $response['code']);
    }


}
