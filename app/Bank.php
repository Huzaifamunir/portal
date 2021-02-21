<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'tbl_bank_transaction';
    
    protected $fillable = [
      'bank_id','tra_date','tra_op','tra_desc','tra_amount',
   ];
}
