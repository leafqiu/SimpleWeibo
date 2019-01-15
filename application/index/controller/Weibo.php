<?php
    // +----------------------------------------------------------------------
    // | 微博和好友管理
    // +----------------------------------------------------------------------
    // | 作者：qy
    // +----------------------------------------------------------------------
    // | 说明：包含微博和好友的管理
    // +----------------------------------------------------------------------
    // | 版本：1.0_0113
    // +----------------------------------------------------------------------
namespace app\index\controller

use think\Controller;
use think\Request;
use think\Session;
use app\index\model\Item
class Weibo extends Controller {

	// 查看所有微博
	public function view_all() {
		$request = Request::instance();
		// TODO: how to get user id?
		$uid = '';

		$prefix = [
			"weibo" => "<http://localhost:2020/weibo/>", 
			"vocab" => "<http://localhost:2020/vocab/>",
			"xsd" => "<http://www.w3.org/2001/XMLSchema#>"
		];
		$select = "?mid ?name ?text ?date ?repostsnum ?commentsnum ?attitudesnum";
		$uid  = "<http://localhost:2020/user/{$uid}>";
		$where = [
			"?mid <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://localhost:2020/vocab/weibo> .",
			"?relation vocab:userrelation_suid $uid .",
			"?relation vocab:userrelation_tuid ?id .",
			"?mid vocab:weibo_uid $uid .", 
			"?mid vocab:weibo_uid ?id .",
			"?mid vocab:weibo_text ?text .", 
			"?mid vocab:weibo_date ?date .",
			"?mid vocab:weibo_repostsnum ?repostsnum .",
			"?mid vocab:weibo_commentsnum ?commentsnum .", 
			"?mid vocab:weibo_attitudesnum ?attitudesnum ."
		];
		$other = ["order by desc(?date)"];
		$result = (new Item)->get($select, $where, $prefix, $other);
		if (!$result)
			return json_encode(array("网络错误，请重试"));
		else
			return $result;
	}
	// 发微博
	public function quick_publish() {
		$request = Request::instance();
		// data: mid, date, text, uid
		// TODO: how to get user id?
		$uid = '';
		$text = $request->post("quick-publish");
		$time = strtotime($request->header("Date"))
		$date = date("Y-m-dTH:i:s", $time);
		$mid = $uid . $time;
		
		$prefix = [
			"weibo" => "<http://localhost:2020/weibo/>", 
			"vocab" => "<http://localhost:2020/vocab/>",
			"xsd" => "<http://www.w3.org/2001/XMLSchema#>"
		];
		$data = [ 
			"weibo:$mid vocab:weibo_text \"$text\" .",
			"weibo:$mid vocab:weibo_date \"$date\"^^xsd:dateTime .",
			"weibo:$mid vocab:weibo_uid \"$uid\".",
			"weibo:$mid vocab:repostsnum \"0\"^^xsd:integer .",
			"weibo:$mid vocab:commentsnum \"0\"^^xsd:integer .",
			"weibo:$mid vocab:attitudesnum \"0\"^^xsd:integer .",
			"weibo:$mid <http://www.w3.org/2000/01/rdf-schema#label> \"weibo #$mid\" .",
			"weibo:$mid <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://localhost:2020/vocab/weibo> ."
		];
		$result = (new Item)->add($prefix, $data);
		if ($result) {
			return json_encode(array("微博发布成功！"));
		}
		else {
			return json_encode(array("微博发布失败！"));
		}
	}

	// 删除微博
	public function deleteWeibo() {
		// TODO
		return $this->fetch();
	}

   // 显示关注用户列表
    public function fellow() {
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
		if (!$result)
			return json_encode(array("网络错误，请重试"));
		else
			return $result;
    }

    // 显示粉丝列表
    public function follower() {
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
		if (!$result)
			return json_encode(array("网络错误，请重试"));
		else
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
		if ($result) {
			return json_encode(array("关注成功！"));
		}
		else {
			return json_encode(array("关注失败！"));
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
		if ($result) {
			return json_encode(array("取消关注成功！"));
		}
		else {
			return json_encode(array("取消关注失败！"));
		}
	}
}
?>