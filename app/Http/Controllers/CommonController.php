<?php

namespace App\Http\Controllers;

use App\User;
use App\Task;
use App\Status;
use App\Priority;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use App\Program;
use App\Project;
use App\Company;
use DB;
use App\UserCompany;
use App\ProgramManager;
use Hash;
use App\UserRole;
use App\Scope;
use App\Userstory;
use App\Timecard;
use App\TaskComment;

class CommonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStatus()
    {
        //if(User::isRole(1) || User::isRole(2) || User::isRole(5) || User::isRole(6))
        //{
          //  return Status::select('id',"status_name")->get();
        //}
        return  Status::select('id',"status_name")->where('id','!=',2)->get();
    }

    public function getPriority()
    {
        return Priority::select('id',"priority_type")->get();
    }

    public function getResources()
    {
        // If role is Super Admin / Admin, show list of all users
        if(User::isRole(1) || User::isRole(2))
        {
            return User::select('id',DB::raw("CONCAT(users.first_name,' ',users.last_name) as name", "email"))->where('is_deleted', 0)->get();    
        }
        
        //Once authorized return all users associated to the program
        if(User::isRole(6) || User::isRole(7) || User::isRole(5))
        {
            $companies = UserCompany::where('user_id', auth()->user()->id)->pluck('company_id');
            $userid = UserCompany::whereIn('company_id', $companies)->pluck('user_id');
            return User::select('id',DB::raw("CONCAT(users.first_name,' ',users.last_name) as name", "email"))->whereIn('id', $userid)->where('is_deleted', 0)->get();    

        }
    }

    public function getPrograms()
    {
         // If role is Super Admin / Admin, show list of all users
        if(User::isRole(1) || User::isRole(2))
        {
            return Program::select('id',"program_name")->get();
        }

        if(User::isRole(5))
        {
            $companies = UserCompany::where('user_id', auth()->user()->id)->pluck('company_id');  
            return Program::select('id',"program_name")->whereIn('company_id', $companies)->get();
        }

        // If role is Organization Manager, show list of all programs within that organization
        if(User::isRole(6) || User::isRole(7))
        {
            $programs = ProgramManager::where('user_id', auth()->user()->id)->pluck('program_id');
            return Program::with('managers')->with('company')->whereIn('id', $programs)->where('is_deleted',0)->latest()->get();
        }
        
    
    }

    public function migrateData($type)
    {
        if($type == 'all' || $type == "user"){
            $this->migrateUser();
        }
        if($type == 'all' || $type == "program"){
            $this->migrateProgram();
        }
        if($type == 'all' || $type == "project") {
            $this->migrateProject();
        }
        if($type == 'all' || $type == 'scope') {
            $this->migrateScopeAndUserstory();
        }
        if($type == 'all' || $type == 'task') {
            $this->migrateTask();
        }
        if($type == 'all' || $type == 'timecard') {
            $this->migrateTimecard();
        }
        if($type == 'all' || $type == 'hours') {
            $this->migrateHours();
        }
        
    }

    public function migrateUser()
    {
        DB::connection('mysql')->delete('DELETE FROM users where id not in (1)');
        
        $companyArr['company_name'] = 'Cisco';
        $companyArr['company_desc'] = 'A Networking Company';
        $companyArr['created_by'] = 1;
        $companyArr['modified_by'] = 1;
        $company = Company::create($companyArr);
        echo $company->company_name.' company Added';echo '<br>';
        
        $compArray['user_id'] = 1;
        $compArray['company_id'] = $company->id;
    
        $manager = UserCompany::firstOrCreate($compArray);           
        echo $company->email.' Company Manager Added';echo '<br>';        


        $usersid = DB::connection('timesheet')->select('select DISTINCT user_id from tbl_capture_img order by user_id desc');
        foreach ($usersid as $value) {
            $usserArry[] = (int) $value->user_id; 
        }
        $usserArry[] = 12904;
        $usserArry[] = 14596;
        $usserArry[] = 10796;
        $usserArry[] = 12551;
        $usserArry[] = 2940;
        $usserArry[] = 3209;
        $usserArry[] = 3161;
        $usserArry[] = 1916;
        sort($usserArry);
        // dd($usserArry);
        $usersid = implode(',',$usserArry);
        // $usersid = '(12904)';
        $users = DB::connection('timesheet')->select("select emp_id, email, first_name, last_name from tbl_employee where emp_id in ({$usersid})");

        foreach($users as $user)
        {
            $userArray['id'] = $user->emp_id;
            $userArray['first_name'] = $user->first_name;            
            $userArray['last_name'] = $user->last_name;
            $userArray['email'] = $user->email;            
            $userArray['designation_id'] = 1;            
            $userArray['role_id'] = 1;
            $userArray['password'] = Hash::make('secret');
            $user = User::firstOrCreate($userArray);
            echo $user->id.'_'.$user->email.' Created';echo '<br>';

            $roleArray['user_id'] = $user->id;
            $roleArray['role_id'] = 4;
        
            $role = UserRole::firstOrCreate($roleArray);           
            echo $user->email.' Developer Role Added';echo '<br>';

            $compArray['user_id'] = $user->id;
            $compArray['company_id'] = 1;
        
            $company = UserCompany::firstOrCreate($compArray);           
            echo $user->email.' Cisco Company Added';echo '<br>';


        }
    }

    public function migrateProgram()
    {
        DB::connection('mysql')->delete('DELETE FROM program');

        $programs = DB::connection('timesheet')->select("select pid, project_name, project_description,created_by from tbl_project_management");
        // dd($programs);
        foreach($programs as $program)
        {
            $userArray['id'] = $program->pid;
            $userArray['program_name'] = $program->project_name;            
            $userArray['program_desc'] = $program->project_description;
            $userArray['program_start_date'] = date('Y-m-d');
            $userArray['program_end_date'] = date('Y-m-d');
            $userArray['created_by'] = $program->created_by;
            $userArray['modified_by'] = $program->created_by;  
            $userArray['company_id'] = 1;             
            $program = Program::firstOrCreate($userArray);
            echo $program->id.'_'.$program->program_name.' Created';echo '<br>';
        }
    }

    public function migrateProject()
    {
        DB::connection('mysql')->delete('DELETE FROM project');

        $projects = DB::connection('timesheet')->select("select spid, pid, sub_project_name, sub_project_description, created_by, priority from tbl_sub_project;");
        // dd($projects);
        foreach($projects as $project)
        {
            $userArray['id'] = $project->spid;
            $userArray['program_id'] = $project->pid;
            $userArray['project_name'] = $project->sub_project_name;            
            $userArray['project_desc'] = $project->sub_project_description;
            $userArray['project_start_date'] = date('Y-m-d');
            $userArray['project_end_date'] = date('Y-m-d');
            $userArray['created_by'] = $project->created_by;
            $userArray['modified_by'] = $project->created_by;             
            $userArray['project_status'] = 1;

            $project = Project::firstOrCreate($userArray);
            echo $project->id.'_'.$project->project_name.' Created';echo '<br>';
        }
    }

    public function migrateScopeAndUserstory()
    {
        DB::connection('mysql')->delete('DELETE FROM scope');
        DB::connection('mysql')->delete('DELETE FROM userstory');

        $projects = Project::all()->pluck('id');
        foreach($projects as $project)
        {
            // $userArray['id'] = $project->spid;
            $userArray['crd_id'] = 'SCOPE_'.$project.'_1';
            $userArray['project_id'] = $project;
            $userArray['crd_title'] = 'Initial Scope';            
            $userArray['crd_desc'] = 'Initital Scope';
            $userArray['crd_status'] = 1;
            $userArray['created_by'] = 1;
            $userArray['modified_by'] = 1;             
            $userArray['crd_status'] = 1;

            $scope = Scope::firstOrCreate($userArray);
            echo $scope->project_id.'_'.$scope->crd_title.' Created';echo '<br>';    

            $usArray['userstory_id'] = 'US_'.$scope->id.'_1';
            $usArray['project_id'] = $project;
            $usArray['cr_id'] = $scope->id; 
            $usArray['userstory_desc'] = 'Initital User Story Description';
            $usArray['userstory_point'] = 20;
            $usArray['created_by'] = 1;
            $usArray['modified_by'] = 1;             
            $usArray['userstory_status'] = 2;
            
            $userstory = Userstory::firstOrCreate($usArray);
            echo '--'.$userstory->cr_id.'_'.$userstory->userstory_title.' Created';echo '<br>';            
        }
    }

    public function migrateTask()
    {
        DB::connection('mysql')->delete('DELETE FROM tasks');
        DB::connection('mysql')->delete('DELETE FROM task_comments');

        $tasks = DB::connection('timesheet')->select('select * from tbl_pid_approval');
        // dd($tasks);
        $idss=1;
        // echo '<pre>';
        foreach($tasks as $task)
        {
            // $taskArr['id'] = $task->pid_id;
            $scope = Scope::where('project_id', $task->sub_project_id)->pluck('id');
            // dd($scope);
            $userstory = Userstory::where('project_id', $task->sub_project_id)->pluck('id');
            $taskArr['task_id'] = 'ST_'.$scope[0].'_'.$userstory[0].'_'.$idss;
            $taskArr['project_id'] = $task->sub_project_id;
            $taskArr['cr_id'] = $scope[0];
            $taskArr['userstory_id'] = $userstory[0];
            $taskArr['task_heirarchy'] = 1;
            $taskArr['task_type'] = 1;
            $taskArr['task_title'] = $task->task_title;
            $taskArr['task_desc'] = $task->task_description;
            $taskArr['task_status'] = 1;
            $taskArr['parent_id'] = 0;
            $taskArr['task_point'] = $task->total_est_hrs / 4;
            $taskArr['created_by'] = ($task->created_by == 0) ? 1 : $task->created_by;
            $taskArr['modified_by'] = ($task->created_by == 0) ? 1 : $task->created_by;
            // $taskArr['task_assignee'] = $task->emp_id;


            $task1 = Task::firstOrCreate($taskArr);
            echo 'T'.$idss.'_'.$task1->id.'_'.$task1->task_title.' Created';echo '<br>';
            // print_r($task1);
            $idss++;
            $subTasks = DB::connection('timesheet')->select("select * from tbl_sub_task where pid_approval_id = {$task->pid_id}");
            $i = 1;
            foreach($subTasks as $sbtask)
            {
                // $staskArr['id'] = $task->pid_id;
                $scope = Scope::where('project_id', $sbtask->sub_project_id)->pluck('id');
                $userstory = Userstory::where('project_id', $sbtask->sub_project_id)->pluck('id');
                $staskArr['task_id'] = 'ST_'.$scope[0].'_'.$userstory[0].'_'.$idss.'_'.$i;
                $staskArr['project_id'] = $sbtask->sub_project_id;
                $staskArr['cr_id'] = $scope[0];
                $staskArr['userstory_id'] = $userstory[0];
                $staskArr['task_heirarchy'] = 2;
                $staskArr['task_type'] = 1;
                $staskArr['task_title'] = $sbtask->sub_task_name;
                $staskArr['task_desc'] = $sbtask->description;
                $staskArr['task_status'] = 1;
                $staskArr['parent_id'] = $task1->id;
                $staskArr['task_point'] = $sbtask->est_hrs / 4;
                $staskArr['created_by'] = ($sbtask->created_by == 0) ? 1 : $sbtask->created_by;
                $staskArr['modified_by'] = ($sbtask->created_by == 0) ? 1 : $sbtask->created_by;
                $staskArr['task_assignee'] = $sbtask->emp_id;


                $stask1 = Task::firstOrCreate($staskArr);
                echo '--ST'.$i.'_'.$stask1->id.'_'.$stask1->task_title.' Created';echo '<br>';
                // echo 'asdasd';print_r($stask1);
                $i++;
                $dayComments = DB::connection('timesheet')->select("select * from tbl_day_comment where stask_id = {$sbtask->stask_id}");

                foreach ($dayComments as $daycomment) {
                    $dcArr['task_id'] = $stask1->id;
                    $dcArr['task_comment'] = $daycomment->comment;
                    $dcArr['task_hours'] = $daycomment->hours;
                    $dcArr['task_completion'] = 70;
                    $dcArr['created_by'] = $daycomment->emp_id;
                    $dcArr['modified_by'] = $daycomment->emp_id;
                    $dcArr['created_at'] = $daycomment->created_at;

                    $comment1 = TaskComment::firstOrCreate($dcArr);
                    echo '---TC_'.$comment1->id.'_'.$comment1->task_comment.' Created';echo '<br>';
                    // print_r($comment1);
                }
                // die;
            }
            echo '<br><br><br>';
                // die;
        }     


    }

    public function migrateTimecard()
    {
        DB::connection('mysql')->delete('DELETE FROM timecard');
        $timecards = DB::connection('timesheet')->select("select * from tbl_capture_img where todaydate >= '2018-12-07'");
        // dd($timecards);
        foreach($timecards as $timecard)
        {
            $timearry['user_id'] = $timecard->user_id;
            $timearry['log_in_date'] = $timecard->todaydate;
            $timearry['log_in_time'] = date("Y-m-d H:i:s", strtotime("{$timecard->todaydate} {$timecard->in_time}"));
            // echo $timearry['log_in_time'];die;
            $timearry['log_in_image'] = $timecard->in_image_url;
            $timearry['log_out_time'] = date("Y-m-d H:i:s", strtotime("{$timecard->todaydate} {$timecard->out_time}"));;
            $timearry['log_out_image'] = $timecard->out_image_url;
            $timearry['created_by'] = $timecard->user_id;
            $timearry['modified_by'] = $timecard->user_id;
            
            $timecard1 = Timecard::firstOrCreate($timearry);
            echo '--'.$timecard1->user_id.'_'.$timecard1->log_in_time.' Created';echo '<br>';            
        }
    }

    public function migrateHours()
    {
        $projects = Project::all()->pluck('id');
        foreach($projects as $project)
        {
            $estimated = DB::connection('timesheet')->select("select sum(level_hours) as estimated_hrs from tbl_sub_project sp  left join tbl_project_level_allocation pl on pl.project_id = sp.spid left join tbl_level_master lm on lm.level_id = pl.level_id where spid = {$project}");
        // echo $project;print_r($estimated);                     
            $pointArr['userstory_point'] = $estimated[0]->estimated_hrs / 4;
            $update = Userstory::where('project_id', $project)->update($pointArr);
            echo $pointArr['userstory_point'];echo '<br>';
        }
    }

}