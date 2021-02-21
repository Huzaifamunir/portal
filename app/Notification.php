<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'tbl_sup_notification';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 'message', 'db_file','proj_id','noti_status','date','db_status',
     ];
}
