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


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchUserstoryProgress()
    {
        $usreport = [];
        if(User::isRole(6) || User::isRole(7))
        {
            $projects = $this->getProjectsBasedOnRole();
            if(count($projects) > 0)
            {
                $userstory = Userstory::with('priority')->whereIn('project_id', $projects)->where('userstory_status',2)->where('is_deleted',0)->get();

                if(count($userstory) > 0)
                {
                    foreach($userstory as $story)
                    {
                        $usreport['userstory_name'] = $story->userstory_desc;
                        $usreport['userstory_point'] = $story->userstory_point;
                        $usreport['userstory_priority'] = $story->priority->priority_type;
                        $usreport['userstory_status'] = $story->userstory_status;
                        $usreport['userstory_project'] = Project::find($story->project_id)->pluck('project_name')[0];
                        $usreport['userstory_progress'] = floor(Task::where('userstory_id', $story->id)->where('task_heirarchy', 2)->where('is_deleted', 0)->avg('task_completion'));
                        
                        $finalArr[] = $usreport;
                    }

                    return $finalArr;
                }
            }
        }
        return 0;
    }

    public function fetchUserstoryPending()
    {
        $usreport = [];
        if(User::isRole(6) || User::isRole(7))
        {
            $projects = $this->getProjectsBasedOnRole();
            if(count($projects) > 0)
            {
                $userstory = Userstory::with('priority')->whereIn('project_id', $projects)->where('userstory_status',1)->where('is_deleted',0)->where('userstory_point', '!=', null)->get();
                return $userstory;
            }
        }
        return 0;
    }

    public function fetchProjectsDeadlinePassed($all = false)
    {
        $finalArr = [];
        if(User::isRole(6) || User::isRole(7))
        {
            $projects = $this->getProjectsBasedOnRole();
            if(count($projects) > 0)
            {
                if($all)
                {
                    $projectArr = Project::with('status')->whereIn('id', $projects)->where('is_deleted',0)->get();
                }else{
                    $projectArr = Project::with('status')->whereIn('id', $projects)->whereDate('project_end_date','<',DB::raw('CURDATE()'))->where('is_deleted',0)->get();
                }
                
                if(count($projectArr) > 0)
                {
                    foreach($projectArr as $project)
                    {
                        
                        $project->project_progress = floor(Task::where('project_id', $project->id)->where('task_heirarchy', 2)->where('is_deleted', 0)->avg('task_completion'));
                        
                        $finalArr[] = $project;
                    }
                    
                }
            }
        }
        
        return $finalArr;   
    }

    public function fetchProjectTask()
    {
        $taskArr = [];
        if(User::isRole(6) || User::isRole(7))
        {
            $projects = $this->getProjectsBasedOnRole();
            if(count($projects) > 0)
            {
                $taskArr = Task::with('status')->with('project')->with('assignee')->whereIn('project_id', $projects)->where('is_deleted',0)->where('task_heirarchy',2)->get();
            }
        }
        
        return $taskArr;   
    }

    public function fetchProjects()
    {
        return $this->fetchProjectsDeadlinePassed(true);
    }

    public function fetchUserComments()
    {
        $comments = [];
        if(User::isRole(6) || User::isRole(7))
        {
            $projects = $this->getProjectsBasedOnRole();
            
            if(count($projects) > 0)
            {
                $taskid = Task::whereIn('project_id', $projects)->where('is_deleted',0)->pluck('id');
                // dd($taskid);
                $comments = TaskComment::with('users')->select('task_comment','task_hours','task_completion','id','created_at')->where('is_deleted',0)->get();
            }
        }
        return $comments;
    }

    public function fetchRoleScreen()
    {
        return strtolower(str_replace(' ','',auth()->user()->roles[0]->role_title));
    }

    public function getProjectsBasedOnRole()
    {
        $projects = [];
        if(User::isRole(7))
        {
            $projects = ProjectManager::where('user_id', auth()->user()->id)->pluck('project_id');
        }else{
            $projects = Project::whereIn('program_id',ProgramManager::where('user_id', auth()->user()->id)->pluck('program_id'))->where('is_deleted',0)->pluck('id');
        }

        return $projects;

    }

    public function fetchTasksDeadlinePassed()
    {
        $tasks = [];
        $tasks = Task::with('project')->with('assignee')->with('priority')->where('task_assignee', auth()->user()->id)->whereDate('task_end_date','<',DB::raw('CURDATE()'))->where('is_deleted',0)->get();
        return $tasks;
    }

    public function fetchUpcomingTasks()
    {
        $tasks = [];
        $tasks = Task::with('project')->with('assignee')->with('priority')->where('task_assignee', auth()->user()->id)->whereDate('task_end_date','>=',DB::raw('CURDATE()'))->where('is_deleted',0)->orderby('task_end_date')->get();
        return $tasks;
    }
    
}
