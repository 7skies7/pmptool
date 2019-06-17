<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class acl extends Model
{
    //
	protected $table = "acls";

	protected $guarded = [];

	public function getAccessData($roleid)
	{
		$access = Acl::select(DB::raw("group_concat(concat(module_id,'_',action_id)) as access"))->where('role_id', $roleid)->where('access_status', 1)->get();
		return explode(',',$access[0]->access);
	}
}
