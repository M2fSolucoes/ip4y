<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Request;

class ProjectRepository
{

    private $project;
    private $locationClass;


    /**
     * Contruct Method
     *
     */
    public function __construct()
    {
        $this->project = new Project();
        $this->locationClass = basename(__FILE__);
    }


    /**
     * Método responsável por retornar os projetos ativos e inativos
     *
     *
     * @return mixed $response
     */
    public function getAll()
    {
        try {
            $response = $this->project->all();
            if($response->count() == 0) {
                $response = ['message'=> 'Não há projetos registrados'];
            }

        } catch (\Exception $e) {

            $response = ['message' => 'Ocorreu uma falha fatal na execução da inclusão de tarefa', 'code' => 400];
            Log::error($this->locationClass . " - Falha Fatal no método " . basename(__FUNCTION__) . " ERROR -> " . $e->getMessage());
        }
        return $response;
    }

    /**
     * Método responsável por criar um projeto novo
     *
     * @param  object $data
     *
     * @return mixed $response
     *
     */
    public function createProject($data)
    {
        try {
            $data['project_code'] = MakeProjectCode();
            $hasProjectTitle  = $this->project->where('title', $data['title'])->count();
            if($hasProjectTitle > 0) {
                return ['message' => "O projeto ".$data['tile'] .", já existe um projeto com o titulo descrito. Altere o título do projeto.", 'code' => 400];
            }
            $response = $this->project->create($data);
        } catch (\Exception $e) {
            $response = ['message' => 'Ocorreu uma falha fatal na execução da inclusão de tarefa', 'code' => 400];
            Log::error($this->locationClass . " - Falha Fatal no método " . basename(__FUNCTION__) . " ERROR -> " . $e->getMessage());
        }
        return $response;
    }

    /**
     * Método responsável por retornar um projeto específico
     *
     * @param  int $id
     *
     * @return mixed $response
     *
     */

    public function getByCode($projectCode)
    {
        try {
            $response = $this->project->where('project_code', $projectCode)->first();
            if(is_null($response)) {
                return['message' => 'Projeto não encontrado com o código '.$projectCode , 'code' => 400];
            }
        } catch (\Exception $e) {
            $response = ['message' => 'Ocorreu uma falha fatal na execução da inclusão de tarefa', 'code' => 400];
            Log::error($this->locationClass . " - Falha Fatal no método " . basename(__FUNCTION__) . " ERROR -> " . $e->getMessage());
        }
        return $response;

    }

    /**
     * Método responsável por alterar um projeto específico e seus atribuidos
     *
     * @param  array $data
     *
     * @return mixed $response
     *
     */
    public function update($data)
    {
        try {
            $updateProject = $this->project->where('project_code', $data['project_code'])->first();
            $updateProject->update($data);

            $response = ['peoject' => $updateProject, 'message' => 'Alteração de projeto alterado com sucesso', 'code' => 200];
        } catch (\Exception $e) {
            $response = ['message' => 'Ocorreu uma falha fatal na alteração de projeto', 'code' => 400];
            Log::error($this->locationClass . " - Falha Fatal no método " . basename(__FUNCTION__) . " ERROR -> " . $e->getMessage());
        }
        return $response;
    }

    /**
     * Método responsável por excluir um projeto especifico e suas tarefas
     *
     * @param  int $id
     *
     * @return mixed $response
     *
     */

    public function delete($data)
    {
        try {
            $updateProject = $this->project->where('project_code', $data['project_code'])->with('tasks')->first();
            if ($updateProject->tasks->count() > 0) {
                foreach ($updateProject->tasks as $task) {
                    $task = Task::find($task->id);
                    $task->delete();
                }
            }
            $updateProject->delete();
            $response = ['message' => 'Exclusão de projeto e suas tarefas excluidos com sucesso', 'code' => 200];
        } catch (\Exception $e) {
            $response = ['message' => 'Ocorreu uma falha fatal na exclusão de projeto', 'code' => 400];
            Log::error($this->locationClass . " - Falha Fatal no método " . basename(__FUNCTION__) . " ERROR -> " . $e->getMessage());
        }
        return $response;
    }



}
