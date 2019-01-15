<?php
    // +----------------------------------------------------------------------
    // | 微博管理
    // +----------------------------------------------------------------------
    // | 作者：qy
    // +----------------------------------------------------------------------
    // | 说明：包含查看、发布和删除微博等操作
    // +----------------------------------------------------------------------
    // | 版本：1.0_0113
    // +----------------------------------------------------------------------

namespace app\index\controller

use think\Controller;
use think\Request;
use think\Session;
use app\index\model\Item

class Weibo extends Controller {

	// 查看关注用户的微博
	public function view_fellow_publish() {
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
		return $result;
	}
	// 发微博
	public function quick_publish() {
		$request = Request::instance();
		// data: mid, date, text, uid
		// TODO: how to get user id?
		$uid = '';
		$text = $request->post("quick-publish");
		$time = strtotime($request->header("Date"));
		$date = date("Y-m-d H:i:s", $time);
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
		$result = (new Item)->add($data, $prefix);
		return $result;
	}

	// 删除微博
	public function delete_weibo() {
		// TODO
		return $this->fetch();
	}

}
?>