<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supreply extends Model
{
    protected $table = 'tbl_supreply';
    protected $primaryKey = 'id';
    protected $fillable = [
        'sup_id', 'message', 'db_file','proj_id','noti_status','date','db_status',
     ];
}
