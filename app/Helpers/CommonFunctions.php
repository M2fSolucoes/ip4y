<?php
use App\Models\Project;
use App\Models\User;


function MakeProjectCode()
{

    $project = Project::where('id', '>', 0)->orderByDesc('id')->first();

    if (is_null($project)) {
        return 'PRJ-' . date('ym') . '01';
    } else {
        $code = explode('-', $project->project_code)[1];

        $base = substr($code, 0, 4);
        if ($base == date('ym')) {
            $code++;
        } else {
            $code = date('ym') . '01';
        }
    }
    return 'PRJ-' . $code;
}
