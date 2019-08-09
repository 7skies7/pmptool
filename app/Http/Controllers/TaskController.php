<?php

namespace App\Http\Controllers;

use App\Scope;
use App\Userstory;
use App\User;
use App\Task;
use App\TaskComment;
use App\StoryPointMaster;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Storage;
use App\Imports\TasksImport;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($project_id)
    {
        // Authorize user requests to view all resource
        if(Gate::allows('View_Task') != true)
        {
            return abort('403');
        }

        return Task::with('assignee')->with('priority')->with('userstory')->with('status')->where('is_deleted',0)->where('task_heirarchy',1)->where('project_id', $project_id)->latest()->get();

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSubtask($task_id)
    {
        // Authorize user requests to view all resource
        if(Gate::allows('View_Task') != true)
        {
            return abort('403');
        }

        return Task::with('assignee')->with('priority')->with('userstory')->with('status')->where('is_deleted',0)->where('task_heirarchy',2)->where('parent_id',$task_id)->latest()->get();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Authorize user requests to store resource details
        if(Gate::allows('Create_Task') != true)
        {
            return abort('403');
        }

        //validate
        $attributes = request()->validate(['task_title'=> 'required', 
                                            'task_point' => 'required',
                                            'task_status' => 'required',
                                            'task_priority' => 'required',
                                            'task_start_date' => 'required',
                                            'task_end_date' => 'required',
                                            'task_type' => 'required',
                                        ]);
        if(request('remainingPoint') < $attributes['task_point'])
        {  
            $message['errors']['task_point'][] = "Points added should be less than Approved Point"; 
            $message['errors']['message'] = 'The given data was invalid';
            return response()->json($message, 422); 
        }
        $attributes['created_by'] = auth()->user()->id;
        $attributes['modified_by'] = auth()->user()->id;
        $attributes['task_status'] = $attributes['task_status']['id'];
        $attributes['task_assignee'] = $attributes['task_assignee']['id'];
        $attributes['task_priority'] = $attributes['task_priority']['id'];
        $attributes['project_id'] = request('project_id');
        $attributes['parent_id'] = request('parent_id');
        $parentTask = Task::find(request('parent_id'));
        $attributes['cr_id'] = $parentTask->cr_id;
        $attributes['userstory_id'] = $parentTask->userstory_id;
        $attributes['task_heirarchy'] = 2; 
        $attributes['task_type'] = 1;  
        $lastScopeId = Task::orderBy('id', 'desc')->limit(1)->pluck('id');
        $attributes['task_id'] = 'ST_'.request('cr_id').'_'.request('userstory_id').'_'.($lastScopeId[0] + 1);
        // dd($attributes);
        //store scope details in database
        $task = Task::create($attributes);
        //save in session
        return $task;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $Project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $Project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $Project
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

        $task = Task::with('status')->with('priority')->with('assignee')->with('userstory')->find($id);        

        return json_encode($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\  $Project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Authorize user requests to update resource details
        if(Gate::allows('Update_Task') != true)
        {
            return abort('403');
        }
        //validate
        $attributes = request()->validate(['task_title'=> 'required', 
                                            'task_point' => 'required',
                                            'task_status' => 'required',
                                            'task_priority' => 'required',
                                            'task_start_date' => 'required',
                                            'task_end_date' => 'required',
                                        ]);
        
        
        $id = request('task_heirarchy') == 1 ? request('userstory_id') : $id;
        if($this->fetchAvailablePoints($id, request('task_heirarchy'), 1) < $attributes['task_point'])
        {  
            $message['errors']['task_point'][] = "Points added should be less than Approved Point";
            $message['errors']['message'] = 'The given data was invalid';
            return response()->json($message, 422); 
        }

        $attributes['modified_by'] = auth()->user()->id;
        $attributes['task_status'] = $attributes['task_status']['id'];
        $attributes['task_assignee'] = $attributes['task_assignee']['id'];
        $attributes['task_priority'] = $attributes['task_priority']['id'];

            
        //store in database
        $task = Task::where('id', $id)->update($attributes);

        //save in session
        return $task;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $Project
     * @return \Illuminate\Http\Response
     */
    public function destroy($taskid)
    {
        // Authorize user requests to delete resource
        if(Gate::allows('Delete_Scope') != true)
        {
            return abort('403');
        }

        $taskHeirarchy = Task::find($taskid)->task_heirarchy;
        if($taskHeirarchy == 1)
        {
            $subtasks = Task::where('parent_id', $taskid)->count();
            if($subtasks > 0)
            {
                $message['errors']['message'] = 'Sorry! Task cannot be deleted as it has subtasks associated with it.';
                return response()->json($message, 422); 
            }
        }else{
            $taskComment = TaskComment::where('task_id', $taskid)->count();
            if($taskComment > 0)
            {
                $message['errors']['message'] = 'Sorry! Sub Task cannot be deleted as it has hours and comments associated with it.';
                return response()->json($message, 422); 
            }
        }
        
        $attributes['is_deleted'] = 1;
        $attributes['deleted_by'] = auth()->user()->id;
        $attributes['deleted_at'] = (new Carbon)->toDateTimeString();
        
        $task = Task::where('id', $taskid)->update($attributes);
        
        return $task;  
    }

    /**
     * WBS Excel Upload and Store.
     *
     * @param  \App\Project  $Project
     * @return \Illuminate\Http\Response
     */
    public function storeWBS(Request $request, $projectid)
    {
        $failures = [];
        # VAlidate the request
        $attributes = request()->validate(['file'=> 'required']);
        # Validate the entries and save it 

        //check if file is attachment then save it to the directory and return filename
        if(!empty($request->hasFile('file')))
        {
            # Save the File --fetchfilename
            $file = $this->saveFile($request);
            if(is_array($file))
            {
                return response()->json($file, 422); 
            }    
            
           // Import all rows from the excel, validate the entries if erros then implements the catch to display error to user

            $taskimport = new TasksImport();
            try{
                
                Excel::import($taskimport, $file);
                return 'Tasks Uploaded';
            }catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                $failures = $e->failures();
                    $error['message'] = "<table class='table table-bordered'><thead><tr><th>Row No</th><th>Column Name</th><th>Excel Value</th><th>Error</th></tr></thead><tbody>";
                    foreach ($failures as $failure) {

                        $error['message'] .= "<tr><td>".$failure->row()."</td>";
                        $error['message'] .= "<td>".$failure->attribute()."</td>"; 
                        $error['message'] .= "<td>".$failure->values()[$failure->attribute()]."</td>"; 
                        $error['message'] .= "<td class='errorcolor'>".$failure->errors()[0]."</td></tr>"; 

                    }
                    $error['message'] .= "</tbody></table>";
                    return response()->json($error, 422);
            }catch(\Illuminate\Validation\ValidationException $e) {
                
                $error['message'] = "<table class='table table-bordered'><thead><tr><th>Error</th><td class='errorcolor'>".$e->errors()['task_point'][0]."</td></thead></table>";
                return response()->json($error, 422);
            }
                        
        }
    }

    /**
     * Store uploaded Excel to Storage.
     *
     * @param  \App\Project  $Project
     * @return \Illuminate\Http\Response
     */
    public function saveFile($request)
    {
        $validExtensions = ['vnd.openxmlformats-officedocument.spreadsheetml.sheet','zip','xlsx'];
        $file = $request->file('file');
        $file_ext = $file->getClientOriginalExtension();
        
        if(!in_array($file_ext, $validExtensions)) {
            $returnData = array(
                'status' => 'error',
                'message' => 'Please upload excel files only',
                'file_ext' => $file_ext
            );
            return $returnData;
        }
        
        $fileName = time().'_'.$file->getClientOriginalName();;
        Storage::disk('public')->makeDirectory('WBS/'.$request->get('userstory'));
        $destination_path = storage_path()."/app/public/WBS/".$request->get('userstory');
        // \File::put($filePath, $fileName);
        $upload_success = $file->move($destination_path, $fileName);
        return $destination_path."/".$fileName;
    }

    public function getUserstory($project_id)
    {
        // Authorize user requests to view all resource
        if(Gate::allows('View_Scope') != true)
        {
            return abort('403');
        }

        return Userstory::with('status')->with('priority')->where('is_deleted',0)->where('project_id', $project_id)->latest()->get();

    }

    public function getRemainingPoints($point, $task_id)
    {
        $points = Task::where('is_deleted',0)->where('task_heirarchy',2)->where('parent_id',$task_id)->sum('task_point');
        return abs($points - $point);
    }

    public function userTasks()
    {
        // Authorize user requests to view all resource
        if(Gate::allows('View_Task') != true)
        {
            return abort('403');
        }
        $user_id = auth()->user()->id;
        
        return Task::with('tasktype')->with('priority')->with('userstory')->with('status')->where('is_deleted',0)->where('task_heirarchy',2)->where('task_assignee', $user_id)->latest()->get();

    }

    /**
     * Store hours worked, progress and comment for single task.
     *
     * @param  \App\Project  $Project
     * @return \Illuminate\Http\Response
     */
    public function storeComment(Request $request, $taskid)
    {
        
        $attributes = request()->validate(['task_comment'=> 'required', 'task_hours'=> '
            required','task_completion'=> 'required']);

        //Commented: To valdiated hours with remainging hours for the task
        // if($attributes['task_hours'] > request('availableHours'))
        // {
        //     $message['errors']['task_hours'][] = "Hours added should be less than Available Hours"; 
        //     $message['errors']['message'] = 'The given data was invalid';
        //     return response()->json($message, 422); 
        // }
        $oldComment = TaskComment::where('task_id', $taskid)->latest()->limit(1)->first();
        if(!empty($oldComment) && $oldComment->task_completion >= $attributes['task_completion'])
        {
            $message['errors']['task_completion'][] = "Please you are required to update the task progress."; 
            $message['errors']['message'] = 'The given data was invalid';
            return response()->json($message, 422); 
        }
        $attributes['created_by'] = auth()->user()->id;
        $attributes['modified_by'] = auth()->user()->id;
        $attributes['task_id'] = $request->get('task_id');
        $task = TaskComment::create($attributes);

        //update Task completion too
        if($task)
        {
            $taskCompletion  = Task::where('id', $request->get('task_id'))->update(['task_completion' => $attributes['task_completion']]);
        }
        return $task;
    }

    /**
     * Fetch all comments related to task
     *
     * @param  \App\Task  $taskid
     * @return \Illuminate\Http\Response
     */
    public function fetchCommments($taskid)
    {
        $commentsArr = [];
        $comments = TaskComment::with('users')->where('task_id', $taskid)->orderBy('id', 'asc')->get();
        
        
        foreach($comments as $comment)
        {
            $commentArr['id'] = $comment->id;
            $commentArr['username'] = $comment->users[0]->first_name.' '.$comment->users[0]->last_name;
            $commentArr['text'] = $comment->task_comment;
            $commentArr['time'] = Carbon::createFromFormat('Y-m-d H:i:s',  $comment->created_at)->format('F j, Y');
            $commentArr['task_completion'] = null;
            if(!empty($comment->task_completion) && isset($comment->task_completion))
            {
                $commentArr['task_completion'] = $comment->task_completion;
            }
            $commentArr['task_hours'] = null;
            if(!empty($comment->task_hours) && isset($comment->task_hours))
            {
                $commentArr['task_hours'] = Carbon::parse($comment->task_hours)->format('H:i');
            }
            $commentsArr[] = $commentArr;

        }
        return $commentsArr;

    }
    
    /**
     * Calculate Remaining Hours for Each Day comment for user
     *
     * @param  \App\Task  $taskid, $point
     * @return \Illuminate\Http\Response
     */
    public function fetchAvailableHours($point, $taskid)
    {
        $userHours = TaskComment::where('task_id', $taskid)->selectRaw('sec_to_time(sum(time_to_sec(task_hours))) as hourstotal')->get();
        $hours = StoryPointMaster::pointtohours($point);
        if($userHours[0]->hourstotal == null)
        {
            return sprintf("%02d", $hours).':00';
        }

        $totalHours = $hours.':00:00';

        $query = "select time_format(timediff('".$totalHours."','".$userHours[0]->hourstotal."'), '%H:%i') as availablehours";
        
        $hoursremain = DB::select($query);
        return $hoursremain[0]->availablehours;
    }

    /**
     * Calculate Remaining Points for Each Day Task (Userpoint Point - (sum of all task +points in that userstory))
     *
     * @param  \App\Task  $taskid
     * @return \Illuminate\Http\Response
     */
    public function fetchAvailablePoints($id, $task_type, $server = 0)
    {   
        if($task_type == 1)
        {
            $userstorypoint = Userstory::where('id',$id)->pluck('userstory_point');
            $sumTaskPoints = Task::where('userstory_id', $id)->where('task_heirarchy', 1)->sum('task_point');
            return $userstorypoint[0] - $sumTaskPoints;
        }elseif($task_type == 2) {
            $childtask = Task::find($id);
            $parenttaskPoint = Task::find($childtask->parent_id);

            if($server == 1)
            {
                $points = Task::where('is_deleted',0)->where('task_heirarchy',2)->where('parent_id',$childtask['parent_id'])->where('id', '!=', $id)->sum('task_point');    
            }else{
                $points = Task::where('is_deleted',0)->where('task_heirarchy',2)->where('parent_id',$childtask['parent_id'])->sum('task_point');
            }


            // dd('asdasdasd');
            return $parenttaskPoint->task_point - $points;
        }
        
    }

    
}
