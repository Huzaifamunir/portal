<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TblInternProjects extends Model
{
	 protected $table = 'tbl_intern_projects';
	  protected $primaryKey = 'int_proj_id';
      protected $fillable = [
      'int_proj_dropbox', 'int_proj_image', 'int_proj_endon','int_proj_description','int_proj_no','int_proj_data','int_proj_tasks','int_proj_title','int_proj_tech','int_proj_startedon','int_proj_duration','int_proj_internid','int_proj_status','int_proj_progress','int_proj_successrate',
       
       
    ];
    
      public function intern()
    {
        return $this->belongsTo(User::class,'int_proj_internid','int_id');
    }
}
