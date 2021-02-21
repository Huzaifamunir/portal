<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'tbl_feedback';
    protected $fillable = [
      'role','auth_id','feedback',
   ];
}
