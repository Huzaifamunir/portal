<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'tbl_intern';
    protected $primaryKey = 'int_id';
    
    protected $fillable = [
       'int_id', 'int_name', 'int_email', 'int_password','int_photo','int_no','int_join_date','created_at','int_cell','int_cell2','int_cnic','int_dob','int_degree','int_technology','int_duration','int_status','from_date','to_date','weak_mem','mon_mem','int_job_status','int_hiredby','int_video','int_paid_status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'int_password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
