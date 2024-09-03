<?php

namespace Tests\App\Http\Controllers;

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * Test class of controller TaskController.
 * run :
 * php artisan test --filter=TaskControllerTest
 */
class TaskControllerTest extends TestCase
{
    private static $projectCode;
    private static $taskId;
    /**
     * Create test project to relate to tasks
     */

    public function test_create_project()
    {
        $request = new Request([
            "title" => "UnitTest Projeto",
            "description" => "Projeto de teste unitário",
            "end_date" => "2024-09-03"
        ]);
        $controller = new ProjectController();
        $response = $controller->create($request);
        $responseData = $response->getData(true);
        self::$projectCode = $responseData['project_code'];
        $this->assertEquals(200, $response->getStatusCode());

    }

    /**
     * Test of method create
     *
     * @return void
     */

    public function test_create()
    {
        $request = new Request([
            "project_code" => self::$projectCode,
            "title" => "Tarefa Projeto UnitTEST",
            "description" => "Executar o teste de inclusão",
            "end_date" => "2024-09-03",
            "users" => [
                "user2@ip4y.com.br"
            ]
        ]);
        $controller = new TaskController();
        $response = $controller->create($request);
        $responseData = $response->getData(true);
        self::$taskId = $responseData['task']['id'];
        $this->assertEquals(200, $response->getStatusCode());

    }

    /**
     * Test of method getAll
     *
     * @return void
     */
    public function test_get_all()
    {
        $user = User::where('email', 'user2@ip4y.com.br')->first();
        Auth::login($user);
        $request = new Request();
        $controller = new TaskController();
        $response = $controller->getAll();
        $this->assertEquals(200, $response->getStatusCode());

    }

    /**
     * Test of method getById
     *
     * @return void
     */
    public function test_get_by_id()
    {
        $request = new Request(['id' => self::$taskId]);
        $controller = new TaskController();
        $response = $controller->getById($request);
        $this->assertEquals(200, $response->getStatusCode());

    }

    /**
     * Test of method upadteAllocateds
     *
     * @return void
     */
    public function test_update_allocateds()
    {
        $user = User::where('email', 'admin@ip4y.com.br')->first();
        Auth::login($user);
        $request = new Request([
            "task_id" => self::$taskId,
            "remove_user" => ['user2@ip4y.com.br'],
            "add_user" => ["marcelo.bezerra@ip4y.com.br"]
        ]);
        $controller = new TaskController();
        $response = $controller->updateAllocateds($request);
        $this->assertEquals(200, $response->getStatusCode());

    }

    /**
     * Test of method upadte
     *
     * @return void
     */

    public function test_update()
    {
        $user = User::where('email', 'admin@ip4y.com.br')->first();
        Auth::login($user);
        $request = new Request([
            "id" => self::$taskId,
            "project_code" => self::$projectCode,
            "title" => "Tarefa Projeto UnitTEST",
            "description" => "Altera tarefa",
            "end_date" => "2024-09-13"
        ]);
        $controller = new TaskController();
        $response = $controller->update($request);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test of method delete
     *
     * @return void
     */
    public function test_delete()
    {
        $user = User::where('email', 'admin@ip4y.com.br')->first();
        Auth::login($user);
        $request = new Request([
            "id" => self::$taskId,
        ]);
        $controller = new TaskController();
        $response = $controller->update($request);
        $this->assertEquals(200, $response->getStatusCode());
    }


    /**
     * Test of method delete project.
     * @return void
     */

    public function test_delete_project()
    {
        $requestDelete = new Request([
            "project_code" => self::$projectCode
        ]);
        $controller = new ProjectController();
        $response = $controller->delete($requestDelete);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
