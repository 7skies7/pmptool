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
        // Authorize user requests to view all resource
        if(Gate::allows('View_ACL') != true)
        {
            return abort('403');
        }  
        //Scenario 1 : All Projects With Hours Worked Monthly
        if($request->report_type['id'] == 1)
        {
            if(empty($request->project)){
                $scenario1 = (new Report)->allProjectReport();    
                $variableId = 'project_id'; 
                $variableName = 'project_name'; 

            }elseif(!empty($request->project)){
                $scenario1 = (new Report)->allProjectResourceReport($request);;    
                $variableId = 'created_by';
                $variableName = 'resource_name';
            }
            $scenario1['end']  = -1;
            $finalArray = [];
            $projectArr=[];
            foreach($scenario1 as $key => $record)
            {
                if(!empty($projectArr) && ($key == 'end' || $record->$variableId != $oldProjectId))
                {
                    $finalArray[] = $projectArr;
                    $projectArr = [];
                }

                if(isset($record->$variableId))
                {
                    $projectArr[$variableName] = $record->$variableName; 
                    $projectArr[$record->month] = $record->hours; 

                    $oldProjectId = $record->$variableId;
                }
            }

            if($request->type == 2)
            {
                $this->exportReport($finalArray);die;
            }

            return $finalArray;
        }


        //Scenario 2 : All Resources With Hours Worked Monthly
        if($request->report_type['id'] == 2)
        {
            if(empty($request->resource)){
                $scenario1 = (new Report)->allResourceReport();    
                $variableId = 'created_by'; 
                $variableName = 'resource_name'; 

            }elseif(!empty($request->resource)){
                $scenario1 = (new Report)->allResourceProjectReport($request);    
                $variableId = 'project_id';
                $variableName = 'project_name';
            }
            $scenario1['end']  = -1;
            $finalArray = [];
            $projectArr=[];
            foreach($scenario1 as $key => $record)
            {
                if(!empty($projectArr) && ($key == 'end' || $record->$variableId != $oldProjectId))
                {
                    $finalArray[] = $projectArr;
                    $projectArr = [];
                }

                if(isset($record->$variableId))
                {
                    $projectArr[$variableName] = $record->$variableName; 
                    $projectArr[$record->month] = $record->hours; 
                    $oldProjectId = $record->$variableId;
                }
            }
            return $finalArray;
        }
    }

    public function exportReport($records)
    {
        dd($records);
    }   

}