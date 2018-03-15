<?php
/**
 * Created by PhpStorm.
 * Date: 2018/3/8
 * Time: 14:17
 */
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use App\Models\Dorm;

use Encore\Admin\Auth\Database\Administrator;

class School extends Model
{
    protected $table = 'school';
    protected $primaryKey = 'id';


    // 定义关联关系
    public function dorm()
    {
        return $this->hasMany(Dorm::class, 'sid', 'id');
    }
    
}