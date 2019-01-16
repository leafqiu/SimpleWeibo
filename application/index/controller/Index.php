<?php
    // +----------------------------------------------------------------------
    // | 主页管理
    // +----------------------------------------------------------------------
    // | 作者：cjy
    // +----------------------------------------------------------------------
    // | 说明：包含主页列表、查看详情等功能
    // +----------------------------------------------------------------------
    // | 版本：1.0_0109
    // +----------------------------------------------------------------------

    namespace app\index\controller;

    //require_once "application/index/model/Item.php";

    use think\Controller;
    use think\Request;
    use think\Session;
    use app\index\model\Item;

    class Index extends Controller {
        // 显示首页
        public function index() {
            return $this->fetch();
        }

        // 显示微博信息列表
        public function item_list() {
            // 列表需要按照用户是否登录进行区分
            if(is_login()) {
                // 已登录用户的列表
                // TODO
            } else {
                // 未登录用户的列表
                // TODO
            }
        }

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
        public function add_follow() {
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

        // 取消关注
        public function delete_follow() {
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
?>