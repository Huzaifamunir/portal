<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_intern_attend extends Model
{
	  protected $table = 'tbl_intern_attend';
	  protected $primaryKey = 'att_id';
	 
     protected $fillable = [
       'att_intern', 'att_date', 'att_marked_on', 'att_in','att_out','att_work','int_lng','int_address','int_lat','att_holiday',
    ];
}
