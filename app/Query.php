<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    protected $table = 'tbl_int_query';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 'query_title', 'query_dis','query_tags','date',
     ];
}
