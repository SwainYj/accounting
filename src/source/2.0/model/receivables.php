<?php

!defined('IN_API') && exit('Access Denied');

class receivablesmodel {

    var $db;
    var $base;

    function __construct(&$base) {
        $this->receivablesmodel($base);
    }

    function receivablesmodel(&$base) {
        $this->base = $base;
        $this->db = $base->db;
    }

    function listreceivables($param, $list_type, $page, $count){

        $sql = API_DBTABLEPRE."receivables WHERE del='0' ";

        if ($param['com_a'] != NULL) {
            $sql .= ' AND com_a="'.$param['com_a'].'"';
        }
        if ($param['subject_type']) {
            $sql .= ' AND subject_type="'.$param['subject_type'].'"';
        }
        if ($param['receiv_year']) {
            $sql .= ' AND receiv_year="'.$param['receiv_year'].'"';
        }
        if ($param['receiv_month']!= NULL) {
            $sql .= ' AND receiv_month="'.$param['receiv_month'].'"';
        }
        if ($param['receiv_day']) {
            $sql .= ' AND receiv_day="'.$param['receiv_day'].'"';
        }
        if ($param['com_b_name']!= NULL) {
            $sql .= ' AND com_b_name="'.$param['com_b_name'].'"';
        }
        if ($param['invoice_num']!= NULL) {
            $sql .= ' AND invoice_num="'.$param['invoice_num'].'"';
        }
        if ($param['settlement_year']!= NULL) {
            $sql .= ' AND settlement_year="'.$param['settlement_year'].'"';
        }
        if ($param['settlement_month']!= NULL) {
            $sql .= ' AND settlement_month LIKE "%'.$param['settlement_month'].'%"';
        }
        // $order = "";
        $order = " ORDER BY com_a,subject_type,receiv_year,receiv_month,receiv_day DESC ";

        $total = 0;
        if ($list_type == 'page') {
            $total = $this->db->result_first('SELECT COUNT(*) AS total FROM '.$sql);
            $pages = $this->base->page_get_page($page, $count, $total);

            $sql .= $order .' LIMIT '.$pages['start'].','.$count;
            $result['page'] = $pages['page'];
        } else {
            $sql .= $order;
        }
        $lists = $this->db->fetch_all('SELECT * FROM '.$sql);
        $n = count($lists);

        $result['total'] = $total;
        $result['count'] = $n;
        $result['data'] = $lists;
        return $result;
    }

    function savereceivables($param){
    	$time = date("Ymd",time());
    	$set = " SET update_time = '".$time."'";
        $set.= ", com_b_name= '".$param['com_b_name']."'";
        $set.= ", com_a= '".$param['com_a']."'";
        $set.= ", subject_type= '".$param['subject_type']."'";
        $set.= ", receiv_year= '".$param['receiv_year']."'";
        $set.= ", receiv_month= '".$param['receiv_month']."'";
        $set.= ", receiv_day= '".$param['receiv_day']."'";
        $set.= ", invoice_num= '".$param['invoice_num']."'";
        $set.= ", settlement_year= '".$param['settlement_year']."'";
        $set.= ", settlement_month= '".$param['settlement_month']."'";
        $set.= ", receiv_amount= '".$param['receiv_amount']."'";
        $set.= ", remark= '".$param['remark']."'";

    	if($param['id']){ 
    		//更新
    		$this->db->query("UPDATE ".API_DBTABLEPRE."receivables ".$set." WHERE id=".$param['id']);
    		$id = $param['id'];
    	}else{
    		//插入
    		$set. ", create_time='".$time."'";
    		$this->db->query("INSERT INTO ".API_DBTABLEPRE."receivables ".$set);
    		$id = $this->db->insert_id();
    	}
        $res['id'] = $id;
    	return $res;
    }

    function receivables_detail($id){
        if(!$id)
            return false;

        $res = $this->db->fetch_first("SELECT * FROM ".API_DBTABLEPRE."receivables WHERE id = $id");
        $result['data'] = $res;
        return $result;
    }

    function delreceivables($id){
    	if(!$id)
    		return false;
    	$this->db->query("DELETE FROM ".API_DBTABLEPRE."receivables WHERE id= $id ");
    	return true;
    }

    function export($param){
        $sql = API_DBTABLEPRE."receivables WHERE del='0' ";

        if ($param['com_a'] != NULL) {
            $sql .= ' AND com_a="'.$param['com_a'].'"';
        }
        if ($param['subject_type']) {
            $sql .= ' AND subject_type="'.$param['subject_type'].'"';
        }
        if ($param['receiv_year']) {
            $sql .= ' AND receiv_year="'.$param['receiv_year'].'"';
        }
        if ($param['receiv_month']!= NULL) {
            $sql .= ' AND receiv_month="'.$param['receiv_month'].'"';
        }
        if ($param['receiv_day']) {
            $sql .= ' AND receiv_day="'.$param['receiv_day'].'"';
        }
        if ($param['com_b_name']!= NULL) {
            $sql .= ' AND com_b_name="'.$param['com_b_name'].'"';
        }
        if ($param['invoice_num']!= NULL) {
            $sql .= ' AND invoice_num="'.$param['invoice_num'].'"';
        }
        if ($param['settlement_year']!= NULL) {
            $sql .= ' AND settlement_year="'.$param['settlement_year'].'"';
        }
        if ($param['settlement_month']!= NULL) {
            $sql .= ' AND settlement_month LIKE "%'.$param['settlement_month'].'%"';
        }
        // $order = "";
        $order = " ORDER BY com_a,subject_type,receiv_year,receiv_month,receiv_day DESC ";
        $sql .= $order;
        $result = $this->db->fetch_all("SELECT * FROM ".$sql);
        $this->downexcel($result);

    }

    function downexcel($data){
        error_reporting(0);
        $name = '收款明细'.date('Ymd');
        include_once API_SOURCE_ROOT.'lib/PHPExcel.php';
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '回款日期')
            ->setCellValue('D1', '公司信息')
            ->setCellValue('H1', '其它');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
        $objPHPExcel->getActiveSheet()->mergeCells('D1:G1');
        $objPHPExcel->getActiveSheet()->mergeCells('H1:K1');
        $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray(
            array(
                'font' => array (
                    'bold' => true
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
                ),
                'borders' => array(
                    'allborders' => array(
                        //'style' => PHPExcel_Style_Border::BORDER_THICK,//边框是粗的  
                        'style' => PHPExcel_Style_Border::BORDER_THIN//细边框  
                        //'color' => array('argb' => 'FFFF0000'),  
                    )
                )
            )
        );

        $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(22);
        $objPHPExcel->getDefaultStyle()->getFont()->setName('宋体');
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);

// Add some data
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', '年')
            ->setCellValue('B2', '月')
            ->setCellValue('C2', '日')
            ->setCellValue('D2', '回款金额')
            ->setCellValue('E2', '单位名称')
            ->setCellValue('F2', '所属公司')
            ->setCellValue('G2', '收入类型')
            ->setCellValue('H2', '发票号')
            ->setCellValue('I2', '结算年份')
            ->setCellValue('J2', '合计结算月份')
            ->setCellValue('K2', '备注');

        //加粗居中
        $objPHPExcel->getActiveSheet()->getStyle('A2:K2')->applyFromArray(
            array(
                'font' => array (
                    'bold' => true
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
                ),
                'borders' => array(
                    'allborders' => array(
                        //'style' => PHPExcel_Style_Border::BORDER_THICK,//边框是粗的  
                        'style' => PHPExcel_Style_Border::BORDER_THIN//细边框  
                        //'color' => array('argb' => 'FFFF0000'),  
                    )
                )
            )
        );
        // $objPHPExcel->getActiveSheet()->freezePane('A1');
        if ($data){
            $n = 3;
            foreach ($data as $k => $receivables){
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$n, $receivables['receiv_year'])
                ->setCellValue('B'.$n, $receivables['receiv_month'])
                ->setCellValue('C'.$n, $receivables['receiv_day'])
                ->setCellValue('D'.$n, $receivables['receiv_amount'])
                ->setCellValue('E'.$n, $receivables['com_b_name'])
                ->setCellValue('F'.$n, $this->getcomname($receivables['com_a']))
                ->setCellValue('G'.$n, $this->getsubtype($receivables['subject_type']))
                ->setCellValue('H'.$n, $receivables['invoice_num'])
                ->setCellValue('I'.$n, $receivables['settlement_year'])
                ->setCellValue('J'.$n, $receivables['settlement_month'])
                ->setCellValue('K'.$n, $receivables['remark']);
                # code...
                $n++;
            }
        }
// Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle("收款明细");
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$name.'.xls"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;


        // $write = new PHPExcel_Writer_Excel2007($objPHPExcel);
        // header("Pragma: public");
        // header("Expires: 0");
        // header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        // header("Content-Type:application/force-download");
        // header("Content-Type:application/vnd.ms-execl");
        // header("Content-Type:application/octet-stream");
        // header("Content-Type:application/download");
        // header('Content-Disposition:attachment;filename="'.$name.'.xlsx"');
        // header("Content-Transfer-Encoding:binary");
        // $write->save('php://output');
        // exit;
    }

    function getcomname($com){
        $name = $this->db->fetch_first("SELECT name FROM ".API_DBTABLEPRE."org WHERE id = $com");
        if($name)
            return $name['name'];
        return "";
    }
    function getsubtype($type){
        $name = $this->db->fetch_first("SELECT value FROM ".API_DBTABLEPRE."attr WHERE code = '$type' AND type = 'class'");
        if($name)
            return $name['value'];
        return "";
    }


}
