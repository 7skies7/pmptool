<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Report extends Model
{
	/**
     * Fetches the Resource Timecard with userstory, scope and project details
     *
     * @param  \App\Program  $Program
     * @return \Illuminate\Http\Response
     */
	public function getResourceReport()
	{
		return DB::select("select 
			concat(u.first_name,' ',u.last_name) as resource_name,
			p.project_name,
			s.crd_title,
			us.userstory_desc,
			t1.task_title,
			t.task_title as sub_task_title,
			task_comment as comments, 
			date_format(tc.created_at,'%Y-%m-%d') as created_at, 
			time_format(task_hours, '%H:%i') as task_hours,
			TIME_FORMAT(TIMEDIFF(log_out_time, log_in_time), '%H:%i') as timecard
		 from task_comments tc 
			inner join users u on u.id = tc.created_by
			left join tasks t on t.id = tc.task_id
			left join tasks t1 on t1.id = t.parent_id
			left join userstory us on us.id = t.userstory_id
			left join scope s on s.id = t.cr_id
			left join project p on p.id = t.project_id
			left join timecard tm on tm.user_id = tc.created_by and tm.log_in_date = date_format(tc.created_at,'%Y-%m-%d');
		");
	}    
   
   public function allProjectReport()
   {
   		return DB::select("select cast(time_to_sec(sec_to_time(sum(time_to_sec(task_hours)))) / (60 * 60) as decimal(10, 1)) as hours, MONTH(tc.created_at) as month, project_id, project_name from task_comments tc inner join tasks tk on tk.id = tc.task_id inner join project prj on prj.id = tk.project_id group by  project_id ASC, MONTH(tc.created_at) ASC");
   }

   public function allProjectResourceReport($request)
   {
   		// dd($request->project[0]['id']);
   		return DB::select("select cast(time_to_sec(sec_to_time(sum(time_to_sec(task_hours)))) / (60 * 60) as decimal(10, 1)) as hours, MONTH(tc.created_at) as month, project_id, tc.created_by, concat(first_name,' ', last_name ) as resource_name from task_comments tc inner join tasks tk on tk.id = tc.task_id inner join users u on u.id = tc.created_by where project_id = {$request->project['id']} group by tc.created_by, MONTH(tc.created_at) ASC");
   }

   public function allResourceReport()
   {
   		return DB::select("select cast(time_to_sec(sec_to_time(sum(time_to_sec(task_hours)))) / (60 * 60) as decimal(10, 1)) as hours, MONTH(tc.created_at) as month, tc.created_by, concat(u.first_name, ' ', u.last_name) as resource_name from task_comments tc inner join users u on u.id = tc.created_by group by  tc.created_by ASC, MONTH(tc.created_at) ASC");
   }

   public function allResourceProjectReport($request)
   {
   		return DB::select("select cast(time_to_sec(sec_to_time(sum(time_to_sec(task_hours)))) / (60 * 60) as decimal(10, 1)) as hours, MONTH(tc.created_at) as month, project_id, project_name from task_comments tc inner join tasks tk on tk.id = tc.task_id inner join project prj on prj.id = tk.project_id where tc.created_by = {$request->resource['id']} group by  tk.project_id ASC, MONTH(tc.created_at) ASC");
   }
}
