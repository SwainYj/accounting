<?php
!defined('IN_API') && exit('Access Denied');

class receivablescontrol extends base {

	function __construct() {
	    $this->receivablescontrol();
	}

	function receivablescontrol() {
	    parent::__construct();
	    $this->load('receivables');
	}

	//获取收款明细列表
    function onlist() {
    	$this->init_input();
        $param['com_a'] = $this->input("com_a");
        $param['com_b_name'] = $this->input("com_b_name");
    	$param['subject_type'] = $this->input("subject_type");
        $param['receiv_year'] = $this->input("receiv_year");
        $param['receiv_month'] = $this->input("receiv_month");
        $param['receiv_day'] = $this->input("receiv_day");
        $param['invoice_num'] = $this->input("invoice_num");
        $param['settlement_year'] = $this->input("settlement_year");
        $param['settlement_month'] = $this->input("settlement_month");

    	$list_type = $this->input('list_type');
        $page = intval($this->input('page'));
        $count = intval($this->input('count'));
        // $param = json_decode(file_get_contents('php://input'), true);
        if(!$list_type || !in_array($list_type, array('all', 'page'))) {
            $list_type = 'all';
        }

        $page = $page > 0 ? $page : 1;
        $count = $count > 0 ? $count : 10;

        $result = $_ENV['receivables']->listreceivables($param, $list_type, $page, $count);
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
        $param['com_b_name'] = $this->input("com_b_name");
    	$param['com_a'] = $this->input("com_a");
    	$param['subject_type'] = $this->input("subject_type");
    	$param['receiv_year'] = $this->input("receiv_year");
    	$param['receiv_month'] = $this->input("receiv_month");
        $param['receiv_day'] = $this->input("receiv_day");
        $param['invoice_num'] = $this->input("invoice_num");
    	$param['settlement_year'] = $this->input("settlement_year");
    	$param['settlement_month'] = $this->input("settlement_month");
    	$param['receiv_amount'] = $this->input("receiv_amount");
    	$param['remark'] = $this->input("remark");
    	$result = $_ENV['receivables']->savereceivables($param);
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

        $result = $_ENV['receivables']->receivables_detail($id);
        $result['code'] = 200;
        if (!$result)
            $this->error(API_HTTP_BAD_REQUEST, ERROR_INFO_FAILED);
        
        return $result;
    }
    //删除
    function ondel(){
    	$this->init_input();
    	$id = $this->input("id");
    	$result = $_ENV['receivables']->delreceivables($id);
    	if (!$result)
            $this->error(API_HTTP_BAD_REQUEST, ERROR_DEL_FAILED);
        
        return $result;
    }

    //导出
    function onexport(){
    	$this->init_input();
        $param['com_a'] = $this->input("com_a");
        $param['com_b_name'] = $this->input("com_b_name");
    	$param['subject_type'] = $this->input("subject_type");
        $param['receiv_year'] = $this->input("receiv_year");
        $param['receiv_month'] = $this->input("receiv_month");
        $param['receiv_day'] = $this->input("receiv_day");
        $param['invoice_num'] = $this->input("invoice_num");
        $param['settlement_year'] = $this->input("settlement_year");
        $param['settlement_month'] = $this->input("settlement_month");
        $result = $_ENV['receivables']->export($param);
        return $result;
    }

}