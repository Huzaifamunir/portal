<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TblAmountSal extends Model
{
    protected $table = 'tbl_month_sal';
    protected $primaryKey = 'id';
   
   protected $fillable = [
     'month', 'total_amount','sal', 
  ];
}
