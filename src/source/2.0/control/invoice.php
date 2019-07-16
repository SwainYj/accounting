<?php
!defined('IN_API') && exit('Access Denied');

class invoicecontrol extends base {

	function __construct() {
	    $this->invoicecontrol();
	}

	function invoicecontrol() {
	    parent::__construct();
	    $this->load('invoice');
	}

	//获取开票明细列表
    function onlist() {
    	$this->init_input();
        $param['com_a'] = $this->input("com_a");
    	$param['subject_type'] = $this->input("subject_type");
    	$param['settlement_year'] = $this->input("settlement_year");
    	$param['settlement_month'] = $this->input("settlement_month");
        $param['invoice_year'] = $this->input("invoice_year");
        $param['invoice_month'] = $this->input("invoice_month");
        $param['invoice_day'] = $this->input("invoice_day");
        $param['invoice_num'] = $this->input("invoice_num");

    	$list_type = $this->input('list_type');
        $page = intval($this->input('page'));
        $count = intval($this->input('count'));
        // $param = json_decode(file_get_contents('php://input'), true);
        if(!$list_type || !in_array($list_type, array('all', 'page'))) {
            $list_type = 'all';
        }

        $page = $page > 0 ? $page : 1;
        $count = $count > 0 ? $count : 10;

        $result = $_ENV['invoice']->listinvoice($param, $list_type, $page, $count);
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
    	$param['invoice_num'] = $this->input("invoice_num");
    	$param['invoice_year'] = $this->input("invoice_year");
    	$param['invoice_month'] = $this->input("invoice_month");
    	$param['invoice_day'] = $this->input("invoice_day");
    	$param['settlement_year'] = $this->input("settlement_year");
    	$param['settlement_month'] = $this->input("settlement_month");
    	$param['amount'] = $this->input("amount");
    	$param['com_b_name'] = $this->input("com_b_name");
    	$param['com_a'] = $this->input("com_a");
    	$param['subject_type'] = $this->input("subject_type");
    	$param['predict'] = $this->input("predict");
    	$result = $_ENV['invoice']->saveinvoice($param);
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

        $result = $_ENV['invoice']->invoice_detail($id);
        $result['code'] = 200;
        if (!$result)
            $this->error(API_HTTP_BAD_REQUEST, ERROR_INFO_FAILED);
        
        return $result;
    }
    //删除
    function ondel(){
    	$this->init_input();
    	$id = $this->input("id");
    	$result = $_ENV['invoice']->delinvoice($id);
    	if (!$result)
            $this->error(API_HTTP_BAD_REQUEST, ERROR_DEL_FAILED);
        
        return $result;
    }

    //导出
    function export(){
    	$this->init_input();
        $param['com_a'] = $this->input("com_a");
    	$param['subject_type'] = $this->input("subject_type");
    	$param['settlement_year'] = $this->input("settlement_year");
    	$param['settlement_month'] = $this->input("settlement_month");
        $param['invoice_year'] = $this->input("invoice_year");
        $param['invoice_month'] = $this->input("invoice_month");
        $param['invoice_day'] = $this->input("invoice_day");
        $param['invoice_num'] = $this->input("invoice_num");
        $result = $_ENV['invoice']->export($param, $list_type, $page, $count);
        return $result;
    }

}