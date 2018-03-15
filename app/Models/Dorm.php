<?php
/**
 * Created by PhpStorm.
 * Date: 2018/3/8
 * Time: 14:17
 */
namespace App\Models;
use App\User;
use Illuminate\Database\Eloquent\Model;

use Encore\Admin\Auth\Database\Administrator;
// use App\Models\School;

class Dorm extends Model
{
    protected $table = 'dorm';
    protected $primaryKey = 'id';
    protected $fillable = ['sid', 'dorm_name','create_user','update_user'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'service_range', 'did', 'delivery_uid');
    }
}