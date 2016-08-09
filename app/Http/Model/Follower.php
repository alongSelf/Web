<?php

namespace App\http\Model;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    //
    protected $table='follower';
    protected $primaryKey='id';
    public $timestamps=false;
    protected $guarded=[];

    private function getMaxLayer()
    {
        return 3;
    }

    //获取自己的信息
    public function getMy($userID)
    {
        return $this->where('userid', $userID)->first();
    }

    //3级粉丝数
    public function getFollowerCount($userID)
    {
        $myFollow = $this->getMy($userID);
        if (!$myFollow){
            return 0;
        }

        $followerCount = $this->where('groupid', $myFollow['groupid'])->
            where('leftweight', '>', $myFollow['leftweight'])->
            where('rightweight', '<', $myFollow['rightweight'])->
            where('layer', '>', $myFollow['layer'])->where('layer', '<=', $myFollow['layer'] + $this->getMaxLayer())
            ->count();

        return $followerCount;
    }

    //3级粉丝
    public function getFollower($userID)
    {
        $myFollow = $this->getMy($userID);
        if (!$myFollow){
            return $this->where('groupid', -1)->paginate(10);
        }

        $follower = $this->where('groupid', $myFollow['groupid'])->
            where('leftweight', '>', $myFollow['leftweight'])->
            where('rightweight', '<', $myFollow['rightweight'])->
            where('layer', '>', $myFollow['layer'])->where('layer', '<=', $myFollow['layer'] + $this->getMaxLayer())
            ->orderBy('layer')->paginate(10);

        return $follower;
    }

    //上3级
    public function getChief($userID)
    {
        $myFollow = $this->getMy($userID);
        if (!$myFollow){
            return false;
        }
        
        $chief = $this->where('groupid', $myFollow['groupid'])->
            where('leftweight', '<', $myFollow['leftweight'])->
            where('rightweight', '>', $myFollow['rightweight'])->
            where('layer', '<', $myFollow['layer'])->where('layer', '>=', $myFollow['layer'] - $this->getMaxLayer())
            ->orderBy('layer', 'desc')->get();

        return $chief;
    }

    public function getChiefPage($userID)
    {
        $myFollow = $this->getMy($userID);
        if (!$myFollow){
            return false;
        }

        $chief = $this->where('groupid', $myFollow['groupid'])->
        where('leftweight', '<', $myFollow['leftweight'])->
        where('rightweight', '>', $myFollow['rightweight'])->
        where('layer', '<', $myFollow['layer'])->where('layer', '>=', $myFollow['layer'] - $this->getMaxLayer())
            ->orderBy('layer', 'desc')->paginate(10);

        return $chief;
    }

    //根粉丝
    public function addRoot($userID)
    {
        $input = [
            'groupid'=>$userID,
            'leftweight'=>1,
            'rightweight'=>2,
            'userid'=>$userID,
            'layer'=>0,
        ];

        $this->create($input);
    }

    public function getRoot()
    {
        $root = $this->where('leftweight', 1)->paginate(10);
        foreach ($root as $key=>$val){
            $count =  $this->where('groupid', $val['groupid'])->count();
            $root[$key]->count=$count;
        }

        return $root;
    }

    //添加粉丝
    public function addFollower($userID, $followerID)
    {
        $myFollow = $this->getMy($userID);
        
        $input = [
            'groupid'=>$myFollow['groupid'],
            'leftweight'=>$myFollow['rightweight'],
            'rightweight'=>$myFollow['rightweight'] + 1,
            'userid'=>$followerID,
            'layer'=>$myFollow['layer'] + 1,
        ];

        $this->where('groupid', $myFollow['groupid'])->where('leftweight', '>', $myFollow['rightweight'])->increment('leftweight', 2);
        $this->where('groupid', $myFollow['groupid'])->where('rightweight', '>=', $myFollow['rightweight'])->increment('rightweight', 2);
        $this->create($input);
    }
}
