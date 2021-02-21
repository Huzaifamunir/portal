<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Intnews extends Model
{
    protected $table = 'tbl_int_news';
    protected $fillable = [
      'int_message',
   ];
}
