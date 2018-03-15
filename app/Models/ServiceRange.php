<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ServiceRange extends Model
{
    protected $table = 'service_range';


    protected $fillable = ['sid', 'school_name', 'did', 'dorm_name'];

    public function user()
    {
        return $this->belongsToMany(User::class, 'delivery_uid');
    }
}
