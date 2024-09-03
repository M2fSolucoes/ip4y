<?php

namespace App\Http\Controllers;

use App\Repositories\ExportReportRepository;
use Illuminate\Http\Request;

class ExportReportController extends Controller
{

    private $exportReportRepository;


    /**
     *
     * Método contrutor
     */
    public function __construct()
    {
        $this->exportReportRepository = new ExportReportRepository();
    }

    /**
     * Método responsável por invocar a funcionalidade de exportação de relatório para excel
     *
     * @param  Request $request
     *
     * @return object
     */

    public function reportXls(Request $request)
    {
        return $this->exportReportRepository->exportToExcel($request->all());
    }

   /**
     * Método responsável por invocar a funcionalidade de exportação de relatório para pdf
     *
     * @param  Request $request
     *
     * @return object
     */

    public function reportPdf(Request $request)
    {
        return $this->exportReportRepository->exportToPdf($request->all());

    }

}
