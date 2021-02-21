<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TblProjectsData extends Model
{
     protected $table = 'tbl_projects_data';
      protected $primaryKey = 'data_id';
     protected $fillable = [
       'data_related_to','data_located','data_delivered_on',
    ];
}
