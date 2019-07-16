<?php

!defined('IN_API') && exit('Access Denied');

class billmodel {

    var $db;
    var $base;

    function __construct(&$base) {
        $this->billmodel($base);
    }

    function billmodel(&$base) {
        $this->base = $base;
        $this->db = $base->db;
    }

    function listbill($param, $list_type, $page, $count){

        $sql = API_DBTABLEPRE."bill WHERE status='1' ";
        if ($param['org_id']) {
            $sql .= ' AND org_id="'.$param['org_id'].'"';
        }
        if ($param['type']) {
            $sql .= ' AND type="'.$param['type'].'"';
        }
        if ($param['year']) {
            $sql .= ' AND year="'.$param['year'].'"';
        }
        if ($param['month']) {
            $sql .= ' AND month="'.$param['month'].'"';
        }
        if ($param['com_name']) {
            $sql .= ' AND com_name="'.$param['com_name'].'"';
        }

        if ($list_type == 'page') {
            $total = $this->db->result_first('SELECT COUNT(*) AS total FROM '.$sql);
            $pages = $this->base->page_get_page($page, $count, $total);

            $sql .= ' ORDER BY create_time DESC LIMIT '.$pages['start'].','.$count;
            $result['page'] = $pages['page'];
        } else {
            $sql .= ' ORDER BY create_time DESC';
        }
        $bill = $this->db->fetch_all('SELECT * FROM '.$sql);
        $n = count($bill);

        $result['count'] = $n;
        $result['list'] = $bill;

        return $result;
    }

    function savebill($param){
    	$time = time();
    	$set = " SET update_time = '".$time."'";

    	if($param['org_id']){
    		$set.= ", org_id= '".$param['org_id']."'";
    	}
    	if($param['type']){
    		$set.= ", type= '".$param['type']."'";
    	}
    	if($param['comname']){
    		$set.= ", com_name= '".$param['comname']."'";
    	}
    	if($param['proname']){
    		$set.= ", pro_name= '".$param['proname']."'";
    	}
    	if($param['budget']){
    		$set.= ", budget= '".$param['budget']."'";
    	}
    	if($param['actual']){
    		$set.= ", actual= '".$param['actual']."'";
    	}
    	if($param['year']){
    		$set.= ", year= '".$param['year']."'";
    	}
    	if($param['month']){
    		$set.= ", month= '".$param['month']."'";
    	}
    	if($param['day']){
    		$set.= ", day= '".$param['day']."'";
    	}
    	if($param['remark']){
    		$set.= ", remark= '".$param['remark']."'";
    	}

    	if($param['id']){ 
    		//更新
    		$this->db->query("UPDATE ".API_DBTABLEPRE."bill ".$set." WHERE id='".$param['id']."'");
    		$id = $param['id'];
    	}else{
    		//插入
    		$set. ", create_time='".$time."'";
    		$this->db->query("INSERT INTO ".API_DBTABLEPRE."bill ".$set);
    		$id = $this->db->insert_id();
    	}
    	return $id;
    }

    function delbill($id){
    	if(!$id)
    		return false;
    	$this->db->query("DELETE FROM ".API_DBTABLEPRE."bill WHERE id='$id' ");
    	return true;
    }

    function listorg($parent_id = ""){
    	$where = ' WHERE 1=1';
        if ($parent_id)
            $where .= ' AND parent_id="'.$parent_id.'"';
    	$data = $this->db->fetch_all("SELECT * FROM ".API_DBTABLEPRE."org ".$where);
        return $data;
    }

    function listype(){
    	$data = $this->db->fetch_all("SELECT * FROM ".API_DBTABLEPRE."attr where type = 'xmlx' AND status=1");
        return $data;
    }

}
