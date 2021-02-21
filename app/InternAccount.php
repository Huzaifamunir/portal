<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InternAccount extends Model
{
    protected $table = 'tbl_bank_account';
    protected $primaryKey = 'id';
    protected $fillable = [
      'code_id','bank_name','bank_balance',
   ];
}
