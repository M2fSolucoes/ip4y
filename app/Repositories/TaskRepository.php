<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskRepository
{

    private $task;
    private $user;
    private $locationClass;
    private $taskUser;

    /**
     * Contruct Method
     *
     */
    public function __construct()
    {
        $this->task = new Task();
        $this->user = new User();
        $this->taskUser = new TaskUser();
        $this->locationClass = basename(__FILE__);
    }

    /**
     * Método reponsável por criar nova tarefa e atribuir a um ou mais usuários
     *
     * @param array $data
     *
     * @return  array $response
     */

    public function createTask($data)
    {
        try {
            $userNotFound = [];
            $users = [];
            // recuperamdo o projeto indicado na tarefa
            $hasProject = Project::where('project_code', $data['project_code'])->first();

            //verificando se o projeto existe, se não existir retorna exceção caso contrário segue o processo
            if (is_null($hasProject)) {
                return ['message' => 'Codigo de projeto inexistente!', 'code' => 400];
            } else {
                //recuperando o a tarefa de acordo com o projeto e verificando a duplicidade de título se retornar ao menos 1(uma) tarefa com o mesmo título
                // retorna exceção
                $hasTask = $this->task->where(['project_id' => $hasProject->id, 'title' => $data['title']])->count();
                if ($hasTask > 0) {
                    return ['message' => "O projeto  $hasProject->title, já possiu uma tarefa com o titulo descrito. Altere o título da tarefa.", 'code' => 400];
                }
                //atribuindo valores de relacionamento com a tabela projects e status da tarefa
                $data['project_id'] = $hasProject->id;
                $data['status'] = 'pendente';
            }

            //criando Tabrefa
            $newTask = $this->task->create($data);
            // atribuindo a tarefa para usuários
            for ($i = 0; $i < count($data['users']); $i++) {

                //recuperando dados do usuário atribuido, verificando a existencia do usuário
                $user = $this->user->where('email', $data['users'][$i])->first();
                // se usuário não existir garda em array e segue o fluxo
                if (is_null($user)) {
                    array_push($userNotFound, $data['users'][$i]);
                } else {
                    // atribuindo tarefa ao(s) usuário(s)
                    $taskUsers = [
                        'task_id' => $newTask->id,
                        'user_id' => $user->id
                    ];
                    $this->taskUser->create($taskUsers);
                    array_push($users, $user->name);
                }
            }

            // array de resposta
            $response = [
                'message' => 'Tarefa inclusa com sucesso!',
                'project' => $hasProject->title,
                'task' => $newTask,
                'allocated' => $users,
            ];
            // caso algum(uns) usuario(s) não for reconhecido retorna chave user_not_found, caso contrário a chave não retorna para o consumidor
            if (!empty($userNotFound)) {
                $response['user_not_foud'] = $userNotFound;
            }

        } catch (\Exception $e) {
            $response = ['message' => 'Ocorreu uma falha fatal na execução da inclusão de tarefa', 'code' => 400];
            Log::error($this->locationClass . " - Falha Fatal no método " . basename(__FUNCTION__) . " ERROR -> " . $e->getMessage());
        }
        return $response;
    }

    /**
     * Método reponsável por retornar list ade tarefas por Projeto
     * Se Usuário logado for do tipo admin retorna todas tarefas
     * Caso Contrário retorna apenas as taferas designadas ao usuário logado
     *
     * @param array $data
     *
     * @return  array $response
     */
    public function getAllByUser()
    {
        try {
            //Recupera o usuário logado
            $user = Auth::user();
            $userId = $user->id;
            //verifica o tipo do usuário
            // Se usuário for do tipo admin, retornará todas as tarefas e seus usuários atribuidos
            if ($user->user_type == 'admin') {
                $response = $this->task->with([
                    'users' => function ($query) {
                        $query->select('name', 'email');
                    }
                ])->get();
                // caso contrário retorna apenas as tarefas atribuidas au usuário logado
            } else {
                $response = $this->task->whereHas('users', function ($query) use ($userId) {
                    $query->where('users.id', $userId);
                })->get();
            }

        } catch (\Exception $e) {
            $response = ['message' => 'Ocorreu uma falha fatal na execução da coleta de tarefas', 'code' => 400];
            Log::error($this->locationClass . " - Falha Fatal no método " . basename(__FUNCTION__) . " ERROR -> " . $e->getMessage());
        }
        return $response;
    }

    /**
     * Método responsável por retornar uma tarefa específica e seus atribuidos
     *
     * @param  int $id
     *
     * @return mixed $response
     *
     */
    public function getTaskById($id)
    {
        try {
            $response = $this->task->where('id', $id)->with([
                'users' => function ($query) {
                    $query->select('name', 'email');
                }
            ])->get();
        } catch (\Exception $e) {
            $response = ['message' => 'Ocorreu uma falha fatal na execução da coleta de tarefa', 'code' => 400];
            Log::error($this->locationClass . " - Falha Fatal no método " . basename(__FUNCTION__) . " ERROR -> " . $e->getMessage());
        }
        return $response;

    }

    /**
     * Método responsável por efetuar updade de uma tarefa
     *
     * @param array $data
     *
     * @return  mixed $response
     *
     */

    public function update($data)
    {
        try {
            //Recupera o usuário logado
            $user = Auth::user();
            //verifica usuário, se usuário não for do tipo admin o acesso é negado
            if ($user->user_type != 'admin') {
                return ['message' => "Acesso negado, apenas usuários do tipo admin podem efetuar alterações em tarefas", 'code' => 401];
            }

            // Efetua update da tarefa
            $updateTask = $this->task->find($data['id']);
            $updateTask->update($data);

            $response = ['task' => $updateTask, 'message' => 'Atualização da tarefa ocorreu com sucesso', 'code' => 200];

        } catch (\Exception $e) {
            $response = ['message' => 'Ocorreu uma falha fatal na execução da atualização de tarefa', 'code' => 400];
            Log::error($this->locationClass . " - Falha Fatal no método " . basename(__FUNCTION__) . " ERROR -> " . $e->getMessage());
        }

        return $response;
    }


    /**
     * Método responsável por efetuar updade de status deuma tarefa
     *
     * @param array $data
     *
     * @return  mixed $response
     *
     */

     public function updateStatus($data)
     {
         try {

             //Recupera o usuário logado
             $user = Auth::user();
             $updateTask = $this->task->find($data['task_id']);
             $thisTaskUser = $this->taskUser->where([
                'task_id' => $data['task_id'],
                'user_id' => $user->id
            ])->count();


            if($thisTaskUser == 0 && $user->user_type != "admin") {
                return ['message' => "A tarefa $updateTask->title, não está atribuia ao seu usuário. Você pode alterar o status apenas de tarefas atribuidas ao seu usuário", 'code' => 400];
            }
             // Efetua update da tarefa
             $updateTask = $this->task->find($data['task_id']);
             $updateTask->status = $data['status'];
             $updateTask->save();
             $response = ['task' => $updateTask, 'message' => 'Atualização da tarefa ocorreu com sucesso', 'code' => 200];

         } catch (\Exception $e) {
             $response = ['message' => 'Ocorreu uma falha fatal na execução da atualização de tarefa', 'code' => 400];
             Log::error($this->locationClass . " - Falha Fatal no método " . basename(__FUNCTION__) . " ERROR -> " . $e->getMessage());
         }

         return $response;
     }



    /**
     * Método responsável por efetuar reatribuição de uma tarefa
     *
     * @param array $data
     *
     * @return  mixed $response
     *
     */

    public function updateAllocateds($data)
    {
        try {

            $userNotFound = [];
            $users = [];
            //Recupera o usuário logado
            $user = Auth::user();
            //verifica usuário, se usuário não for do tipo admin o acesso é negado
            if ($user->user_type != 'admin') {
                return ['message' => "Acesso negado, apenas usuários do tipo admin podem efetuar reatribuição de tarefas", 'code' => 401];
            }
            //recupera tarefa do bando de dados caso não exista retorna mensagem
            $task = $this->task->find($data['task_id']);
            if (is_null($task)) {
                return ['message' => 'Identificação de tarefa (task_id) não encontrada, verifique', 'code' => 400];
            }

            // verifica se remove_user, não está nulo , vazio ou se existe a chave
            if (!empty($data['remove_user']) || !is_null($data['remove_user']) || isset($data['remove_user'])) {
                for ($i = 0; $i < count($data['remove_user']); $i++) {
                    //recupera usuário
                    $userTask = $this->user->where('email', $data['remove_user'][$i])->first();
                    // verifica se usuáro existe
                    if (is_null($userTask)) {
                        array_push($userNotFound, $data['remove_user'][$i]);
                        // se existe exclui a atribuição da tarefa
                    } else {
                        $this->taskUser->where([
                            'task_id' => $task->id,
                            'user_id' => $userTask->id
                        ])->delete();
                    }
                }
            }
            // verifica se add_user, não está nulo , vazio ou se existe a chave
            if (!empty($data['add_user']) || !is_null($data['add_user']) || isset($data['add_user'])) {

                for ($i = 0; $i < count($data['add_user']); $i++) {
                    //recupera usuário
                    $userTask = $this->user->where('email', $data['add_user'][$i])->first();
                   // verifica se usuáro existe
                    if (is_null($userTask)) {
                        array_push($userNotFound, $data['add_user'][$i]);
                    } else {
                        // se existe inclui a atribuição da tarefa
                        $this->taskUser->updateOrCreate([
                            'task_id' => $task->id,
                            'user_id' => $userTask->id
                        ]);
                        array_push($users, $user->name);
                    }
                }
            }

            // array de resposta
            $response = [
                'message' => 'Tarefa reatribuida com sucesso!',
                'task' => $this->getTaskById($data['task_id']),
                'allocated' => $users,
            ];
            // caso algum(uns) usuario(s) não for reconhecido retorna chave user_not_found, caso contrário a chave não retorna para o consumidor
            if (!empty($userNotFound)) {
                $response['user_not_foud'] = $userNotFound;
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            $response = ['message' => 'Ocorreu uma falha fatal na execução da reatribuição de tarefa', 'code' => 400];
            Log::error($this->locationClass . " - Falha Fatal no método " . basename(__FUNCTION__) . " ERROR -> " . $e->getMessage());
        }

        return $response;
    }


    /**
     * Método responsável por coletar dados para geração de relatório
     *
     *@param array $data
     *
     *@return array $response
     */

    public function report($data, $host=null)
    {
        try {

            if (isset($data['start_date']) && isset($data['end_date'])) {
                $query = $this->task->whereBetween('end_date', [$data['start_date'], $data['end_date']]);
                $betweenDates = 'start_date='.$data['start_date'].'&end_date='.$data['end_date'].'&';
            }else {
                $betweenDates = "";
            }
            if (isset($data['status'])) {
                $query = $this->task->where('status', $data['status']);
                $status = 'status='.$data['status'];
            } else {
                $betweenDates = str_replace("&", "", $betweenDates);
                $status ="";
            }

            $tasks =  $query->with(['project', 'users'])->get();

            $groupedTasks = $tasks->groupBy(function ($task) {
                return $task->project->id;
            });
            $response = $groupedTasks->map(function ($tasks, $projectId) {
                return [
                    'project' => $tasks->first()->project,
                    'tasks' => $tasks->values()
                ];
            })->values()->all();


            if(!is_null($host)){
                $protocol = $_SERVER['PROTOCOL'] = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';
                $reportParams = "?$betweenDates$status";
                $response['excel']  = "$protocol$host/task/report/xlsx$reportParams";
                $response['pdf'] =  "$protocol$host/task/report/pdf$reportParams";
            }

        }  catch (\Exception $e) {
            $response = ['message' => 'Ocorreu uma falha fatal na execução da reatribuição de tarefa', 'code' => 400];
            Log::error($this->locationClass . " - Falha Fatal no método " . basename(__FUNCTION__) . " ERROR -> " . $e->getMessage());
        }

        return $response;
    }



    /**
     * Método responsável por efetuar a exclusão de tarefa especifica
     *
     *@param integer $id
     *
     *@return array $response
     */


    public function delete($data)
    {
        try {
            //Recupera o usuário logado
            $user = Auth::user();
            //verifica usuário, se usuário não for do tipo admin o acesso é negado
            if ($user->user_type != 'admin') {
                return ['message' => "Acesso negado, apenas usuários do tipo admin podem efetuar exclusão de tarefas", 'code' => 401];
            }

            // Efetua update da tarefa
            $updateTask = $this->task->find($data['id']);
            $updateTask->update($data);

            $response = ['task' => $updateTask, 'message' => 'Exclusão da tarefa ocorreu com sucesso', 'code' => 200];

        } catch (\Exception $e) {
            $response = ['message' => 'Ocorreu uma falha fatal na execução da exclusão de tarefa', 'code' => 400];
            Log::error($this->locationClass . " - Falha Fatal no método " . basename(__FUNCTION__) . " ERROR -> " . $e->getMessage());
        }

        return $response;
    }




}
