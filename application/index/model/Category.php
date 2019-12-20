<?php
namespace app\index\model;

use think\Model;

class Category extends Model
{
    public function bookList()
    {
        return $this->hasMany('Book','category_id','id');
    }
}