<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TblIprojectStaff extends Model
{
    protected $table = 'tbl_iproject_staff';
     protected $fillable = [
       'tpsID','tpsProject','tpsStaff','tpsModule','tpsDate',
    ];
}
