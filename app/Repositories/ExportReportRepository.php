<?php

namespace App\Repositories;

use App\Exports\TasksExport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportReportRepository
{

    private $taskRepository;
    /**
     *
     * Método construtor
     */

    public function __construct()
    {
        $this->taskRepository = new TaskRepository();
    }

    /**
     * Método reponsável por exportar relatório em Xlsx
     *
     * @param mixed $data
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportToExcel($data)
    {
        $tasks = $this->taskRepository->report($data);
        return Excel::download(new TasksExport($tasks), 'tasks_report.xlsx');

    }

    /**
     * Método reponsável por exportar relatório em PDF
     *
     * @param mixed $data
     *
     * @return \Illuminate\Http\Response
     */
    public function exportToPdf($data)
    {
        $reports = $this->taskRepository->report($data);
        $pdf = Pdf::loadView('exports.Report', compact(['reports']))->setPaper('a4', 'landscape');;
        return $pdf->download('tasks_report.pdf');

    }


}
