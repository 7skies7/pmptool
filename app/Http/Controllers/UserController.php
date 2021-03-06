<?php

namespace App\Http\Controllers;

use App\Program;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use App\ProgramManager;
use App\UserRole;
use App\UserCompany;
use App\Timecard;
use App\TaskComment;
use DB;
use Hash;
use Mail;
use App\Mail\SendTimecard;

class UserController extends Controller
{
    protected $program_role_id = 5;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Authorize user requests to view all resource
        if(Gate::allows('View_Users') != true)
        {
            return abort('403');
        }

        // If role is Super Admin / Admin, show list of all users
        if(User::isRole(1) || User::isRole(2))
        {
            // DB::enableQueryLog();
            $users = User::with('roles')->with('companies')->where('is_deleted',0)->get();
            
            // dd(DB::getQueryLog());
            // dd($user);
        }

        //Once authorized return all users associated to the program
        if(User::isRole(6) || User::isRole(7) || User::isRole(5))
        {
            return User::fetchCompanyUsers();
        }
        
        return $users->filter(function ($user) {
            return $user->companies->id == session('company_id');
        });
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
        if(Gate::allows('Create_Users') != true)
        {
            return abort('403');
        }

        //validate
        $attributes = request()->validate(['first_name'=> 'required', 
                                            'last_name' => 'required',
                                            'email' => 'required|email',
                                            'roles' => 'required',
                                            'password' => 'required|confirmed'
                                            // 'companies' => 'required',
                                        ]);
        
        $attributes['created_by'] = auth()->user()->id;
        $attributes['modified_by'] = auth()->user()->id;
        $attributes['alternate_email'] = request('alternate_email');
        $attributes['password'] = Hash::make($attributes['password']); 
        
        //store in database
        unset($attributes['roles']);
        unset($attributes['companies']);
        $user = User::create($attributes);

        foreach(request('roles') as $role)
        {
            //Assign Organization Manager role to all the company managers selected
            $roleArr['user_id'] = $user->id;
            $roleArr['role_id'] = $role['id'];
            UserRole::firstOrCreate($roleArr,$roleArr);
        }

        foreach(request('companies') as $company)
        {
            //Assign Organization Manager role to all the company managers selected
            $compArr['user_id'] = $user->id;
            $compArr['company_id'] = $company['id'];
            UserCompany::firstOrCreate($compArr,$compArr);
        }

        //save in session
        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Program  $Program
     * @return \Illuminate\Http\Response
     */
    public function show(Program $Program)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Program  $Program
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $program = Program::with('managers')->find($id);
        return json_encode($program);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\  $Program
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Authorize user requests to update resource details
        if(Gate::allows('Update_Users') != true)
        {
            return abort('403');
        }

        //validate
        $attributes = request()->validate(['first_name'=> 'required', 
                                            'last_name' => 'required',
                                            'email' => 'required|email',
                                            'roles' => 'required',
                                            'password' => 'confirmed'
                                        ]);

        $attributes['modified_by'] = auth()->user()->id;
        $attributes['alternate_email'] = request('alternate_email');
        
        unset($attributes['password']);
        if(!empty(request('password')) && request('password') != null)
        {
            $attributes['password'] = Hash::make(request('password'));    
        }
        
        
        //store in database
        unset($attributes['roles']);
        unset($attributes['companies']);
        $user = User::where('id', $id)->update($attributes);

        //Delete previous user records
        UserRole::where('user_id',$id)->delete();
        //Delete previous company role for userrecords
        //UserRole::where('role_id',$this->program_role_id)->delete();
        
        foreach(request('roles') as $role)
        {
            //Assign Organization Manager role to all the company managers selected
            $roleArr['user_id'] = $id;
            $roleArr['role_id'] = $role['id'];
            UserRole::firstOrCreate($roleArr,$roleArr);
        }

        //Delete previous users records
        UserCompany::where('user_id',$id)->delete();

        foreach(request('companies') as $company)
        {
            //Assign Organization Manager role to all the company managers selected
            $compArr['user_id'] = $id;
            $compArr['company_id'] = $company['id'];
            UserCompany::firstOrCreate($compArr,$compArr);
        }


        //save in session
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Program  $Program
     * @return \Illuminate\Http\Response
     */
    public function destroy($userid)
    {
        // Authorize user requests to delete resource
        if(Gate::allows('Delete_Users') != true)
        {
            return abort('403');
        }
        
        $attributes['is_deleted'] = 1;
        $attributes['deleted_by'] = auth()->user()->id;
        $attributes['deleted_at'] = (new Carbon)->toDateTimeString();
        
        $user = User::where('id', $userid)->update($attributes);
        
        return $user;  
    }

    /**
     * Face Capture Module for Face Log in and Log Out.
     * Takes the the log type and the request as parameters, wherein the request contains the image file to saved to database and log in and out time.
     * @param  \App\Timecard  $Timecard
     * @return \Illuminate\Http\Response
     */
    public function capture(Request $request, $logtype)
    {   
        date_default_timezone_set('Asia/Kolkata');    
        $attributes = request()->validate(['image'=> 'required']);
        $filename = $this->saveFile($request);
        
        $attributes['modified_by'] = auth()->user()->id;
        unset($attributes['image']);
        if($logtype == 1 && $filename != '')
        {
            $attributes['user_id'] = auth()->user()->id;
            $attributes['created_by'] = auth()->user()->id;
            $attributes['log_in_date'] = date('Y-m-d');
            $attributes['log_in_time'] = date('Y-m-d H:i:s');
            $attributes['log_in_image'] = $filename;
            
            $timecard = Timecard::create($attributes);
            if($timecard) {
                $this->sendLoginLogoutMail();
            }
            
        }elseif($logtype == 2 && $filename != ''){

            $attributes['log_out_time'] = date('Y-m-d H:i:s');
            $attributes['log_out_image'] = $filename;
            $timecard = Timecard::whereDate('created_at', DB::raw('CURDATE()'))->update($attributes);

        }

        
        return $timecard;
    }

    /**
     * This is used to save the file passed from the capture function, performs file validations 
     * Stores the file unto the directory and returns the filename
     * @param  \App\Program  $Program
     * @return \Illuminate\Http\Response
     */
    public function saveFile($request)
    {
        $validExtensions = ['jpg', 'jpeg', 'png'];
        $file = $request->get('image');
        $file_ext = explode('/', mime_content_type($file))[1];
        if(!in_array($file_ext, $validExtensions)) {
            $returnData = array(
                'status' => 'error',
                'message' => 'Please upload only jpg, png  files only',
                'file_ext' => $file_ext
            );
            return $returnData;
        }
        $file = preg_replace('/^data:.*php$/', '', $file);
        $file = str_replace(' ', '+', $file);
        $fileName = time().'_'.auth()->user()->id.'.'.$file_ext;
        
        \Storage::disk('public')->makeDirectory('Login/'.auth()->user()->id);
        \File::put(storage_path()."/app/public/Login/".auth()->user()->id.'/'. $fileName, base64_decode($file));
        return $fileName;
    }

    /**
     * Fetches users timecard ie face log in and out time.
     *
     * @param  \App\Program  $Program
     * @return \Illuminate\Http\Response
     */
    public function getTimecard()
    {
        $timecard = Timecard::where('log_in_date', date('Y-m-d'))->where('user_id', auth()->user()->id)->orderBy('id', 'desc')->take(1)->get();
        return $timecard;
    }

    /**
     * Fetches the today comment given by the user .
     *
     * @param  \App\Program  $Program
     * @return \Illuminate\Http\Response
     */
    public function getTodayComment()
    {
        date_default_timezone_set('Asia/Kolkata');    
        $currentdate = date('Y-m-d');
        $comment = TaskComment::where('created_by', auth()->user()->id)->whereDate('created_at', DB::raw('CURDATE()'))->count();
        return $comment;
    }

    /**
     * Fetches the user details.
     *
     * @param  \App\Program  $Program
     * @return \Illuminate\Http\Response
     */
    public function fetchUserDetails()
    {
        $currentMonth = date('m');
        $user = User::with('roles')->find(auth()->user()->id);
        $hours_clocked = Timecard::where('user_id', auth()->user()->id)->where('log_in_time', '!=','')->where('log_out_time', '!=','')->selectRaw('time_format(sec_to_time(sum(time_to_sec(timediff(log_out_time, log_in_time)))), "%H:%i") as hours')->whereRaw('MONTH(log_in_date) = ?',[$currentMonth])->get();
        $user->hours_clocked = $hours_clocked[0]->hours;
        // dd($user);
        return $user;
    }

    /**
     * Fetches the User timesheet details to be displayed on activity calendar.
     *
     * @param  \App\Program  $Program
     * @return \Illuminate\Http\Response
     */
    public function fetchUserTimesheet()
    {
        $comments = (new User)->timesheet();
        
        $finalArr = [];
        if(!empty($comments))
        {
            foreach($comments as $comment)
            {   
                $tableContent = '';
                $recordArr['date'] = $comment->day;
                $recordArr['title'] = $comment->hours;

                $tableContent = '<table class="table table-bordered"><thead><th>Task Name</th><th>Comment</th><th>Task Hours</th><tbody>';
                $explodeComments = explode('!;', $comment->task_details);
                foreach($explodeComments as $singlecomment)
                {
                    $tableContent .= '<tr>';
                    $explodesingle = explode('!%', $singlecomment);
                    if(isset($explodesingle[0]))
                    {
                        $tableContent .= '<td>'.$explodesingle[0].'</td>';
                    }
                    if(isset($explodesingle[1]))
                    {
                        $tableContent .= '<td>'.$explodesingle[1].'</td>';
                    }
                    if(isset($explodesingle[2]))
                    {
                        $tableContent .= '<td>'.$explodesingle[2].'</td>';
                    }
                }
                $tableContent .= '</tbody></table>'; 
                $recordArr['comments'] = $tableContent;
                $recordArr['color'] = '#52c43d';

                            
                $finalArr[] = $recordArr;  
            }
        }
        
        $records = (new User)->timecard();
        foreach ($records as $timecard) {
            $recordArr['date'] = $timecard->log_in_date;
            $recordArr['title'] = ($timecard->total_time != null and $timecard->total_time > '00:00') ? $timecard->total_time : '00:00';
            if($recordArr['title'] == '838:59')
            {
                $recordArr['title'] = '00:00';
            } 
            $tableContent = '<table class="table table-bordered"><thead><th>Date</th><th>Log In</th><th>Log Out</th><th>Total</th><tbody>';
            $tableContent .= '<tr><td>'.$timecard->log_in_date.'</td><td>'.$timecard->log_in_time.'</td><td>'.$timecard->log_out_time.'</td><td>'.$recordArr['title'].'</td></tr>';
            $recordArr['comments'] = $tableContent;
            $recordArr['color'] = '#29B4E6';
            $finalArr[] = $recordArr;  
        }

        ksort($finalArr);
        // dd($finalArr);
        return json_encode($finalArr);
    }

     /**
     * Change user password on first login.
     *
     * @param  \App\Program  $Program
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        //validate
        $attributes = request()->validate(['password' => 'required|confirmed']);
        $updateArr['password'] = Hash::make($attributes['password']); 
        $updateArr['password_changed'] = 1;
        $attributes['modified_by'] = auth()->user()->id;
        $user = User::where('id', auth()->user()->id)->update($updateArr);
        return $user;
    }

    public function sendLoginLogoutMail()
    {
        $data['title'] = "This is Test Mail Tuts Make";
        $emails = [auth()->user()->email, auth()->user()->alternate_email];
        // Mail::send('emails.email', $data, function($message) use ($emails) {
        //     $message->to($emails, auth()->user()->first_name.' '.auth()->user()->last_name)
        //             ->subject('CiscoPMS| Log In Time ');
        // });
        Mail::to($emails)->send(new SendTimecard($data));
 
        // if (Mail::failures()) {
        //    return response()->Fail('Sorry! Please try again latter');
        //  }else{
        //    return response()->success('Great! Successfully send in your mail');
        //  }
    }
}
