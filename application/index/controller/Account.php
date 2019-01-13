<?php
    // +----------------------------------------------------------------------
    // | 账户管理
    // +----------------------------------------------------------------------
    // | 作者：cjy
    // +----------------------------------------------------------------------
    // | 说明：包含所有涉及账户的操作，包括登录、注册、注销等
    // +----------------------------------------------------------------------
    // | 版本：1.0_0109
    // +----------------------------------------------------------------------

    namespace app\index\controller;

    use think\Controller;
    use think\Request;
    use think\Session;

    class Account extends Controller {
        // 登录操作
        public function login() {
            return $this->fetch();
        }

        // 登录信息提交和校验
        public function login_post() {
            //前端提交的必须是Ajax请求再进行验证与新增操作
            if(Request::isAjax()){
                //1.数据验证
                $data = Request::post();  //要验证的数据
                
            }
        }

        // 注册操作
        public function register() {
            return $this->fetch();
        }

        // 注册信息校验和提交
        public function register_post() {
            return $this->fetch();
        }

        // 注销操作
        public function logout() {
            return $this->fetch();
        }
    }
?>