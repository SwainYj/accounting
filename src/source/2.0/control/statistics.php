<?php
!defined('IN_API') && exit('Access Denied');

class statisticscontrol extends base {

	function __construct() {
	    $this->statisticscontrol();
	}

	function statisticscontrol() {
	    parent::__construct();
	    $this->load('statistics');
	}

	//应收账款
	function onaccount(){

	}


	//开票收款
	function oninvoice(){

	}

	//应收账龄
	function onaccountage(){
		
	}

}