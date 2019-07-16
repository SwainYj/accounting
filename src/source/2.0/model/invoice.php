<?php

!defined('IN_API') && exit('Access Denied');

class invoicemodel {

    var $db;
    var $base;

    function __construct(&$base) {
        $this->invoicemodel($base);
    }

    function invoicemodel(&$base) {
        $this->base = $base;
        $this->db = $base->db;
    }

    function listinvoices($param, $list_type, $page, $count){

        $sql = API_DBTABLEPRE."invoice WHERE del=0 ";
        if ($param['key'] != NULL) {
            $sql .= ' AND (code="'.$param['key'].'" or name = "'.$param['key'].'")';
        }
        if ($param['st']) {
            $sql .= ' AND buy_day>="'.$param['st'].'"';
        }
        if ($param['et']) {
            $sql .= ' AND buy_day<="'.$param['et'].'"';
        }
        if ($param['status']!= NULL) {
            $sql .= ' AND status="'.$param['status'].'"';
        }
        $order = "";
        if($param['order']){
            $order = " ORDER BY ".$order;
        }

        if ($list_type == 'page') {
            $total = $this->db->result_first('SELECT COUNT(*) AS total FROM '.$sql);
            $pages = $this->base->page_get_page($page, $count, $total);

            $sql .= $order .' DESC LIMIT '.$pages['start'].','.$count;
            $result['page'] = $pages['page'];
        } else {
            $sql .= $order;
        }
        $shares = $this->db->fetch_all('SELECT * FROM '.$sql);
        $n = count($shares);

        //statistics
        $total_buy = 0;
        $total_sell = 0;
        $total_profit = 0;
        $total_pre = 0;
        $total_cost = 0;
        foreach ($shares as $key => $value) {
            $total_buy += intval($value['buy_amount']);
            $total_sell += intval($value['sell_amount']);
            // $total_profit += intval($value['total_profit']);
            $total_cost +=intval($value['cost']);
            # code...
        }
        if($total_buy>0){
            $total_pre = ($total_sell - $total_buy - $total_cost) * 100 * 100 / $total_buy;
            $total_profit = $total_sell - $total_buy - $total_cost;
        }
        
        $total['buy'] = $total_buy;
        $total['sell'] = $total_sell;
        $total['profit'] = $total_profit;
        $total['pre'] = $total_pre;
        $total['cost'] = $total_cost;

        $result['total'] = $total;
        $result['count'] = $n;
        $result['data'] = $shares;
        return $result;
    }

    function saveinvoice($param){
    	$time = date("Ymd",time());
    	$set = " SET updatetime = '".$time."'";

    	if($param['code'] != NULL){
    		$set.= ", code= '".$param['code']."'";
    	}
    	if($param['name'] != NULL){
    		$set.= ", name= '".$param['name']."'";
    	}
    	if($param['buy_price'] != NULL){
    		$set.= ", buy_price= '".$param['buy_price']."'";
    	}
    	if($param['buy_amount'] != NULL){
    		$set.= ", buy_amount= '".$param['buy_amount']."'";
    	}
    	if($param['sell_price'] != NULL){
    		$set.= ", sell_price= '".$param['sell_price']."'";
    	}
    	if($param['sell_amount'] != NULL){
    		$set.= ", sell_amount= '".$param['sell_amount']."'";
    	}
    	if($param['cost'] != NULL){
    		$set.= ", cost= '".$param['cost']."'";
    	}
    	if($param['day'] != NULL){
    		$set.= ", day= '".$param['day']."'";
    	}
    	if($param['buy_day'] != NULL){
    		$set.= ", buy_day= '".$param['buy_day']."'";
    	}
    	if($param['sell_day'] != NULL){
    		$set.= ", sell_day= '".$param['sell_day']."'";
    	}
        if($param['times'] != NULL){
            $set.= ", times= '".$param['times']."'";
        }
        if(!$param['sell_amount']){
            $status = 1;
        }else{
            $status = 0;
        }
        $set.= ", status= '".$status."'";
        $profit = $param['sell_amount'] - $param['buy_amount'] - $param['cost'];
        $profit_pro = $profit/$param['buy_amount'] * 100;
        $set.= ", profit= '".$profit."'";
        $set.= ", profit_pro= '".$profit_pro."'";

    	if($param['id']){ 
    		//更新
    		$this->db->query("UPDATE ".API_DBTABLEPRE."invoice ".$set." WHERE id=".$param['id']);
    		$id = $param['id'];
    	}else{
    		//插入
    		$set. ", dateline='".$time."'";
    		$this->db->query("INSERT INTO ".API_DBTABLEPRE."invoice ".$set);
    		$id = $this->db->insert_id();
    	}
        $res['id'] = $id;
    	return $res;
    }

    function invoice_detail($id){
        if(!$id)
            return false;

        $res = $this->db->fetch_first("SELECT * FROM ".API_DBTABLEPRE."invoice WHERE id = $id");
        $result['data'] = $res;
        return $result;
    }

    function delinvoice($id){
    	if(!$id)
    		return false;
    	$this->db->query("DELETE FROM ".API_DBTABLEPRE."invoice WHERE id= $id ");
    	return true;
    }

}
