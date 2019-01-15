<?php
    // +----------------------------------------------------------------------
    // | 好友管理
    // +----------------------------------------------------------------------
    // | 作者：qy
    // +----------------------------------------------------------------------
    // | 说明：包含查看已关注用户和粉丝，以及关注其他用户和取消关注等操作
    // +----------------------------------------------------------------------
    // | 版本：1.0_0115
    // +----------------------------------------------------------------------

namespace app\index\controller

use think\Controller;
use think\Request;
use think\Session;
use app\index\model\Item

class User extends Controller {
   // 显示关注用户列表
    public function view_fellow() {
    	$request = Request::instance();
    	// TODO: how to get user id?
    	$uid = '';
    	$prefix = [
    		"vocab"=> "<http://localhost:2020/vocab/>"
    	];
    	$select = "?id ?name ?followersnum ?fellowsnum";
    	$where = [
    		"?relation vocab:userrelation_suid \"$uid\" .",
    		"?relation vocab:userrelation_tuid ?id .",
    		"?user vocab:user_uid ?id .", 
    		"?user vocab:user_name ?name .",
    		"?user vocab:user_followersnum ?followersnum .",
    		"?user vocab:user_frinedsnum ?fellowsnum ."
    	];
    	$result = (new Item)->get($select, $where, $prefix);
    	return $result;
    }

    // 显示粉丝列表
    public function view_follower() {
    	$request = Request::intance();
    	// TODO: get user id
    	$uid = '';
    	$prefix = [
    		"vocab"=> "<http://localhost:2020/vocab/>"    		
    	];
    	$select = "?id name ?followersnum ?fellowsnum";
    	$where = [
    		"?relation vocab:userrelation_tuid \"$uid\" .",
    		"?relation vocab:userrelation_suid ?id .",
    		"?user vocab:user_uid ?id .", 
    		"?user vocab:user_name ?name .",
    		"?user vocab:user_followersnum ?followersnum .",
    		"?user vocab:user_frinedsnum ?fellowsnum ."
    	];
    	$result = (new Item)->get($select, $where, $prefix);
		return $result;
    }
	// 关注
	public function follow() {
		$request = Request::instance();
		// TODO: how to get user id and fellow id
		$uid = '';
		$id = '';
		$prefix = [
			"vocab" => "<http://localhost:2020/vocab/>"
		];
		$relation = "<http://localhost:2020/userrelation/$uid/$id>";
		$data = [
			"$relation vocab:userrelation_suid \"$uid\" .",
			"$relation vocab:userrelation_tuid \"$id\" ."
		];
		$result = (new Item)->add($data, $prefix);
		return $result;
		}
	}

	// 取消关注
	public function cancel_follow() {
		$request = Request::instance();
		// TODO: get user and fellow id
		$uid = '';
		$id = '';
		$prefix = [
			"vocab" => "<http://localhost:2020/vocab/>"
		];
		$relation = "<http://localhost:2020/userrelation/$uid/$id>";
		$data = [
			"$relation vocab:userrelation_suid \"$uid\" .",
			"$relation vocab:userrelation_tuid \"$id\" ."
		];
		$result = (new Item)->delete($data, $prefix);
		return $result;
		}
	}
}
?>