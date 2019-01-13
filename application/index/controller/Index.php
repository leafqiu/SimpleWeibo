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

    use think\Controller;
    use think\Request;
    use think\Session;

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

        // 显示关注用户列表
        public function fellow() {
            return $this->fetch();
        }

        // 显示粉丝列表
        public function follower() {
            return $this->fetch();
        }
    }
?>