<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TblInternProjectsTask extends Model
{
     protected $table = 'tbl_intern_projects_tasks';
	  protected $primaryKey = 'task_id';
      protected $fillable = [
       'task_no','task_title','task_start_date','task_project_id','task_description','task_status','task_end_date','task_duration','task_delivered_on',
    ];
}
