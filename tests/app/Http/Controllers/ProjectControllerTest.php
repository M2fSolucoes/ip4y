<?php


namespace Tests\App\Http\Controllers;

use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * Test class of controller ProjectController.
 * run :
 * php artisan test --filter=ProjectControllerTest
 */
class ProjectControllerTest extends TestCase
{
    private static $projectCode;

    /**
     * Test of method create and setUp testData
     *
     * @return void
     */

    public function test_create()
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
     * Test of method getAll.
     * run :
     * @return void
     */

    public function test_get_all()
    {
        $controller = new ProjectController();
        $response = $controller->getAll();
        $this->assertEquals(200, $response->getStatusCode());

    }


    /**
     * Test of method GetByCode.
     * run :
     * @return void
     */

    public function test_get_by_code()
    {
        $requestByCode = new Request([
            "project_code" => self::$projectCode
        ]);
        $controller = new ProjectController();
        $response = $controller->getByCode($requestByCode);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test of method update.
     * @return void
     */
    public function test_update()
    {
        $requestUpdate = new Request([
            "title" => "UnitTest Projeto",
            "description" => "Projeto de teste unitário",
            "end_date" => "2024-09-10",
            "project_code" =>  self::$projectCode
        ]);
        $controller = new ProjectController();
        $response = $controller->update($requestUpdate);
        $responseData = $response->getData(true);
        $this->assertEquals(200, $response->getStatusCode(), $responseData['message']);
    }

    /**
     * Test of method delete.
     * @return void
     */

    public function test_delete()
    {
        $requestDelete = new Request([
            "project_code" =>  self::$projectCode
        ]);
        $controller = new ProjectController();
        $response = $controller->delete($requestDelete);
        $responseData = $response->getData(true);
        $this->assertEquals(200, $response->getStatusCode(), $responseData['message']);
    }
}
