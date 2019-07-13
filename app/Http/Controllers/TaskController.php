<?php

namespace App\Http\Controllers;

use App\Scope;
use App\Userstory;
use App\User;
use App\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Storage;
use App\Imports\TasksImport;
use Maatwebsite\Excel\Facades\Excel;


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Authorize user requests to view all resource
        if(Gate::allows('View_Scope') != true)
        {
            return abort('403');
        }

        return Task::with('status')->with('priority')->with('userstory')->where('is_deleted',0)->latest()->get();

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
        if(Gate::allows('Create_Scope') != true)
        {
            return abort('403');
        }

        //validate
        $attributes = request()->validate(['crd_title'=> 'required', 
                                            'crd_desc' => 'required',
                                            'crd_status' => 'required',
                                        ]);
        
        $attributes['created_by'] = auth()->user()->id;
        $attributes['modified_by'] = auth()->user()->id;
        $attributes['crd_status'] = $attributes['crd_status']['id'];
        $attributes['project_id'] = request('project_id');
        $lastScopeId = Scope::orderBy('id', 'desc')->limit(1)->pluck('id');
        $attributes['crd_id'] = 'CRD_'.request('project_id').'_'.($lastScopeId[0] + 1);
        //store scope details in database
        $scope = Scope::create($attributes);
        //save in session
        return $scope;
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

        $scope = Scope::with('status')->find($id);        

        return json_encode($scope);
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
        if(Gate::allows('Update_Scope') != true)
        {
            return abort('403');
        }

        //validate
        $attributes = request()->validate(['crd_title'=> 'required', 
                                            'crd_desc' => 'required',
                                            'crd_status' => 'required',
                                        ]);

        $attributes['created_by'] = auth()->user()->id;
        $attributes['modified_by'] = auth()->user()->id;
        $attributes['crd_status'] = $attributes['crd_status']['id'];
            
        //store in database
        $project = Scope::where('id', $id)->update($attributes);

        //save in session
        return $project;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $Project
     * @return \Illuminate\Http\Response
     */
    public function destroy($scopeid)
    {
        // Authorize user requests to delete resource
        if(Gate::allows('Delete_Scope') != true)
        {
            return abort('403');
        }
        
        $attributes['is_deleted'] = 1;
        $attributes['deleted_by'] = auth()->user()->id;
        $attributes['deleted_at'] = (new Carbon)->toDateTimeString();
        
        $scope = Scope::where('id', $scopeid)->update($attributes);
        
        return $scope;  
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
            $taskimport = new TasksImport;
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
        // return Project::with('user')->latest()->get();
        // return Scope::with('status')->with('approveddocument')->where('is_deleted',0)->latest()->get();
        return Userstory::with('status')->with('priority')->where('is_deleted',0)->where('project_id', $project_id)->latest()->get();

    }
    
}
