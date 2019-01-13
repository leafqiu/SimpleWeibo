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

class Weibo extends Controller {

	// 发微博
	public function addWeibo() {
		$request = Request::instance();
		// Info: mid, date, text, uid and so on
		$text = $request->param("weibo");
		$date = $request->header('Date');  // need to be transform

		return $this->fetch();
	}

	// 删除微博
	public function deleteWeibo() {
		return $this->fetch();
	}

	// 关注
	public function follow() {
		return $this->fetch();
	}

	// 取消关注
	public function cancelFollow() {
		return $this->fetch();
	}
}
?>