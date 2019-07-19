<?php
!defined('IN_API') && exit('Access Denied');

class predictcontrol extends base {

	function __construct() {
	    $this->predictcontrol();
	}

	function predictcontrol() {
	    parent::__construct();
	    $this->load('predict');
	}

	//获取预估明细列表
    function onlist() {
    	$this->init_input();
        $param['com_a'] = $this->input("com_a");
    	$param['subject_type'] = $this->input("subject_type");
        $param['predict_year'] = $this->input("predict_year");
        $param['predict_month'] = $this->input("predict_month");
        $param['charger'] = $this->input("charger");
        $param['com_b_name'] = $this->input("com_b_name");

    	$list_type = $this->input('list_type');
        $page = intval($this->input('page'));
        $count = intval($this->input('count'));
        // $param = json_decode(file_get_contents('php://input'), true);
        if(!$list_type || !in_array($list_type, array('all', 'page'))) {
            $list_type = 'all';
        }

        $page = $page > 0 ? $page : 1;
        $count = $count > 0 ? $count : 10;

        $result = $_ENV['predict']->listpredict($param, $list_type, $page, $count);
        $result['code'] = 200;
        if (!$result)
            $this->error(API_HTTP_BAD_REQUEST, ERROR_LIST_FAILED);

        return $result;
    }

//保存/修改
    function onsave(){
    	$this->init_input();
    	$param = array();
        $param['id'] = $this->input("id");
    	$param['remark'] = $this->input("remark");
    	$param['charger'] = $this->input("charger");
    	$param['com_b_name'] = $this->input("com_b_name");
    	$param['com_a'] = $this->input("com_a");
    	$param['subject_type'] = $this->input("subject_type");
        $param['predict_year'] = $this->input("predict_year");
        $param['predict_month'] = $this->input("predict_month");
    	$param['amount'] = $this->input("amount");
    	$result = $_ENV['predict']->savepredict($param);
        $result['code'] = 200;

        if (!$result)
            $this->error(API_HTTP_BAD_REQUEST, ERROR_SAVE_FAILED);
        
        return $result;
    }
    //获取明细
    function ondetail(){
        $this->init_input();
        $id = $this->input("id");
        if(!$id)
            $this->error(API_HTTP_BAD_REQUEST, ERROR_PARAM_FAILED);

        $result = $_ENV['predict']->predict_detail($id);
        $result['code'] = 200;
        if (!$result)
            $this->error(API_HTTP_BAD_REQUEST, ERROR_INFO_FAILED);
        
        return $result;
    }
    //删除
    function ondel(){
    	$this->init_input();
    	$id = $this->input("id");
    	$result = $_ENV['predict']->delpredict($id);
    	if (!$result)
            $this->error(API_HTTP_BAD_REQUEST, ERROR_DEL_FAILED);
        
        return $result;
    }

    //导出
    function onexport(){
    	$this->init_input();
        $param['com_a'] = $this->input("com_a");
        $param['subject_type'] = $this->input("subject_type");
        $param['predict_year'] = $this->input("predict_year");
        $param['predict_month'] = $this->input("predict_month");
        $param['charger'] = $this->input("charger");
        $param['com_b_name'] = $this->input("com_b_name");
        $result = $_ENV['predict']->export($param, $list_type, $page, $count);
        return $result;
    }

}