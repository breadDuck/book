<?php
namespace app\index\model;

use think\Model;

class Book extends Model
{
    protected $autoWriteTimestamp=true;
    protected $updateTime='updated_at';
    protected $createTime='created_at';
    public function author()
    {
        return $this->belongsTo('User','admin_id');
    }
    protected function scopeHotList($query)
    {
        $query->field('id,title,intro,pic,browse_times,is_upload')->order('browse_times','desc');
    }
    protected function scopeNewList($query)
    {
        $query->field('id,title')->order('id','desc');
    }
}