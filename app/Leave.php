<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $table = 'tbl_intern_leave';
    protected $primaryKey = 'leave_id';
    protected $fillable = [
      'leave_int_id','int_leave_fdate','int_leave_tdate','leave_reason','leave_status',
   ];
    public function leave()
    {
        return $this->belongsTo(User::class,'leave_int_id','int_id');
    }
}
