<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Suplogin extends Model
{
    protected $table = 'tbl_sup';
    protected $primaryKey = 'sup_id';
    protected $fillable = [
        'sup_id', 'sup_email', 'sup_password',
     ];
}
