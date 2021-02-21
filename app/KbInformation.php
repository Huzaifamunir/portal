<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KbInformation extends Model
{
    protected $table = 'tbl_knowledge_base';
    protected $fillable = [
      'authority ','title ','attachment ',
   ];
}
