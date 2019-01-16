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

require_once "GstoreConnector.php";

class Item {
	private $username = "root";
	private $password = "123456";
	private $ip = "localhost";
	private $port = "2020";
	private $database = "weibodata";
	private $gc;
	
	// 构造函数，初始化GC对象
	public function __construct() {
		$this->gc = new GstoreConnector($this->ip, $this->port);
	}

	// 查询记录
	public function get($select, $where, $prefix = [], $other = []) {
		if (!$select || !$where) {
			// empty query or null
			return null;
		}
		$sparql = '';
		foreach ($prefix as $key=>$value) {
			$sparql .= "prefix $key: $value\n";
		}
		$sparql .= "select $select from {$this->database} where {\n";
		foreach ($where as $value) {
			$sparql .= $value . "\n";
		}
		$sparql .= "}\n";
		foreach ($other as $value) {
			$sparql .= $value . "\n";
		}

		$json = $this->gc->query($this->username, $this->password, $this->database, $sparql);
		$res = json_decode($json);
		if ($res->StatusCode == 0) {
			$json_res = $this->result2json($res);
			return $json_res;
		}
		else {
			return null;
		}
	}

	// 新增记录
	public function add($data, $prefix = []) {
		if (!$data) {
			return false;
		}
		$sparql = '';
		foreach ($prefix as $key=>$value) {
			$sparql .= "prefix $key: $value\n";
		}
		$sparql .= "insert into graph {$this->database} {\n";
		foreach ($data as $value) {
			$sparql .= $value . "\n";
		}
		$sparql .= "}";

		$json = $this->gc->query($this->username, $this->password, $this->database, $sparql);
		// $this->gc->checkpoint($this->database, $this->username, $this->password);
		$res = json_decode($json);
		return ($res->StatusCode == 0);
	}

	// 删除记录
	public function delete($data, $prefix = []) {
		if (!$data)
			return false;
		$sparql = '';
		foreach ($prefix as $key => $value) {
			$sparql .= "prefix $key: $value\n";
		}
		$sparql .= "delete from graph {$this->databse} {\n";
		foreach ($data as $value) {
			$sparql .= $value . "\n";
		}
		$sparql .= "}";
		$json = $this->gc->query($this->username, $this->password, $this->database, $sparql);
		// $this->gc->checkpoint($this->database, $this->username, $this->password);
		$res = json_decode($json);
		return ($res->StatusCode == 0);
	}

	// 数据格式转换
	private function result2json($result) {
		$result_array = array();
		$bindings = $result->results->bindings;
		foreach ($bindings as $item) {
			$item_array = array();
			foreach ($item as $vars => $values) {
				$item_array[$vars] = $values->value;
			}
			$result_array[] = $item_array;
		}
		return json_encode($result_array);
	}
}
?>