<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    protected $table = 'tbl_dboard';
    protected $fillable = [
      'use_id','db_message','db_file','db_type','project_id','date','db_sales',
   ];
}
