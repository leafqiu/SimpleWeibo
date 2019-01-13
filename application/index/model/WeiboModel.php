<?php
    // +----------------------------------------------------------------------
    // | 数据模型
    // +----------------------------------------------------------------------
    // | 作者：qy
    // +----------------------------------------------------------------------
    // | 说明：负责数据库的操作
    // +----------------------------------------------------------------------
    // | 版本：1.0_0113
    // +----------------------------------------------------------------------

namespace app\index\model;

require "GstoreConnector.php";

class WeiboModel {
	private $username = "root";
	private $password = "123456";
	private $ip = "localhost";
	private $port = "2020";
	private $databse = "weibodata";
	private $gc;
	
	// 构造函数，初始化GC对象
	public function __construct() {
		gc = new GstoreConnector($ip, $port);
	}

	// 查询记录
	public function get($sparql) {
		if (!$sparql) {
			// empty query or null
			return null;
		}
		$json = $this->gc->query($this->username, $this->password, $this->databse, $sparql);
		$res = json_decode($json);
		if ($res->StatusCode == 0) {
			// how to return: data format tranformation?
			// TODO
		}
		else
			return null;
	}

	// 新增记录
	public function add($sparql) {
		if (!$sparql) {
			return false;
		}
		$json = $this->gc->query($this->username, $this->password, $this->database, $sparql);
		// $this->gc->checkpoint($this->database, $this->username, $this->password);
		$res = json_decode($json);
		return ($res->StatusCode == 0);
	}

	// 删除记录
	public function delete($sparql) {
		if (!$sparql)
			return false;
		$json = $this->gc->query($this->username, $this->password, $this->database, $sparql);
		// $this->gc->checkpoint($this->database, $this->username, $this->password);
		$res = json_decode($json);
		return ($res->StatusCode == 0);
	}
}
?>