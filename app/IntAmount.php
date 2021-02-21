<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IntAmount extends Model
{
    protected $table = 'tbl_internamount';
    protected $fillable = [
      'int_no','int_amount','am_date',
   ];
}
