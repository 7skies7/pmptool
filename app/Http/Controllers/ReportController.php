<?php

namespace App\Http\Controllers;

use App\Userstory;
use App\Project;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use App\ProjectManager;
use App\ProgramManager;
use DB;
use App\Task;
use App\TaskComment;
use App\Report;


class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchResourceReport()
    {
        $resourceReport = (new Report)->getResourceReport();
        // dd($recourceReport);
        return $resourceReport;
    }

    /**
     * Generate Report Based on Input Selected By User.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchDynamicReport(Request $request)
    {
        // dd($request->project);
        //Scenario 1 : All Projects With Hours Worked Monthly
        if(empty($request->project) && empty($request->resource) && $request->report_type['id'] == 1)
        {
            $scenario1 = (new Report)->allProjectReport();
            $scenario1['end']  = -1;

            $finalArray = [];
            $projectArr=[];
            foreach($scenario1 as $key => $record)
            {
                if(!empty($projectArr) && ($key == 'end' || $record->project_id != $oldProjectId))
                {
                    $finalArray[] = $projectArr;
                    $projectArr = [];
                }

                if(isset($record->project_id))
                {
                    $projectArr['project_name'] = $record->project_name; 
                    $projectArr[$record->month] = $record->hours; 

                    $oldProjectId = $record->project_id;
                }
            }


            return $finalArray;

        }

        if(!empty($request->project) && empty($request->resource) && $request->report_type['id'] == 1)
        {
            $scenario1 = (new Report)->allProjectResourceReport($request);
            $scenario1['end']  = -1;

            $finalArray = [];
            $resourceArr=[];
            foreach($scenario1 as $key => $record)
            {
                if(!empty($resourceArr) && ($key == 'end' || $record->created_by != $oldCreatedBy))
                {
                    $finalArray[] = $resourceArr;
                    $resourceArr = [];
                }

                if(isset($record->created_by))
                {
                    $resourceArr['resource_name'] = $record->name; 
                    $resourceArr[$record->month] = $record->hours; 

                    $oldCreatedBy = $record->created_by;
                }
            }

            return $finalArray;

        }

        //Scenario 2 : All Resources With Hours Worked Monthly
        if(empty($request->project) && empty($request->resource) && $request->report_type['id'] == 2)
        {
            echo "Scenario 2";
        }

        dd($request);

    }   

}