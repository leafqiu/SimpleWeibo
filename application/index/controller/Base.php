<?php
    namespace app\index\controller;

    use think\Controller;

    class Base extends Controller{
        public function header(){
            return $this->assign('title', $title);
            return $this->fetch();
        }
    }
?>