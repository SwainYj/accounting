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


    function listinvoice($param, $list_type, $page, $count){

        $sql = API_DBTABLEPRE."invoice WHERE del='0' ";

        if ($param['com_a'] != NULL) {
            $sql .= ' AND com_a="'.$param['com_a'].'"';
        }
        if ($param['subject_type']) {
            $sql .= ' AND subject_type="'.$param['subject_type'].'"';
        }
        if ($param['settlement_year']) {
            $sql .= ' AND settlement_year="'.$param['settlement_year'].'"';
        }
        if ($param['settlement_month']!= NULL) {
            $sql .= ' AND settlement_month="'.$param['settlement_month'].'"';
        }
        if ($param['invoice_year']) {
            $sql .= ' AND invoice_year="'.$param['invoice_year'].'"';
        }
        if ($param['invoice_month']!= NULL) {
            $sql .= ' AND invoice_month="'.$param['invoice_month'].'"';
        }
        if ($param['invoice_day']!= NULL) {
            $sql .= ' AND invoice_day="'.$param['invoice_day'].'"';
        }
        if ($param['invoice_num']!= NULL) {
            $sql .= ' AND invoice_num="'.$param['invoice_num'].'"';
        }
        // $order = "";
        $order = " ORDER BY invoice_num,com_a,com_b_name ASC ";

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

    function saveinvoice($param){
    	$time = date("Ymd",time());
    	$set = " SET update_time = '".$time."'";
        $set.= ", invoice_num= '".$param['invoice_num']."'";
        $set.= ", invoice_year= '".$param['invoice_year']."'";
        $set.= ", invoice_month= '".$param['invoice_month']."'";
        $set.= ", invoice_day= '".$param['invoice_day']."'";
        $set.= ", settlement_year= '".$param['settlement_year']."'";
        $set.= ", settlement_month= '".$param['settlement_month']."'";
        $set.= ", amount= '".$param['amount']."'";
        $set.= ", com_b_name= '".$param['com_b_name']."'";
        $set.= ", com_a= '".$param['com_a']."'";
        $set.= ", subject_type= '".$param['subject_type']."'";
        $set.= ", predict= '".$param['predict']."'";
        $set.= ", remark= '".$param['remark']."'";

    	if($param['id']){ 
    		//更新
    		$this->db->query("UPDATE ".API_DBTABLEPRE."invoice ".$set." WHERE id=".$param['id']);
    		$id = $param['id'];
    	}else{
    		//插入
    		$set. ", create_time='".$time."'";
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

    function export($param){
        $sql = API_DBTABLEPRE."invoice WHERE del='0' ";

        if ($param['com_a'] != NULL) {
            $sql .= ' AND com_a="'.$param['com_a'].'"';
        }
        if ($param['subject_type']) {
            $sql .= ' AND subject_type="'.$param['subject_type'].'"';
        }
        if ($param['settlement_year']) {
            $sql .= ' AND settlement_year="'.$param['settlement_year'].'"';
        }
        if ($param['settlement_month']!= NULL) {
            $sql .= ' AND settlement_month="'.$param['settlement_month'].'"';
        }
        if ($param['invoice_year']) {
            $sql .= ' AND invoice_year="'.$param['invoice_year'].'"';
        }
        if ($param['invoice_month']!= NULL) {
            $sql .= ' AND invoice_month="'.$param['invoice_month'].'"';
        }
        if ($param['invoice_day']!= NULL) {
            $sql .= ' AND invoice_day="'.$param['invoice_day'].'"';
        }
        if ($param['invoice_num']!= NULL) {
            $sql .= ' AND invoice_num="'.$param['invoice_num'].'"';
        }
        // $order = "";
        $order = " ORDER BY invoice_num,com_a,com_b_name ASC ";
        $sql .= $order;
        $result = $this->db->fetch_all("SELECT * FROM ".$sql);
        $result = $this->groupby($result);

        $this->downexcel($result);

    }

    function groupby($result){
        $res = array();
        foreach ($result as $key => $value) {
            $res[$value['invoice_num']][] = $value;
            # code...
        }
        return $res;
    }

    function downexcel($data){
        error_reporting(0);
        $name = '开票明细'.date('Ymd');
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
            ->setCellValue('A1', '开票日期')
            ->setCellValue('D1', '发票信息')
            ->setCellValue('K1', '其它');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
        $objPHPExcel->getActiveSheet()->mergeCells('D1:J1');
        $objPHPExcel->getActiveSheet()->mergeCells('K1:O1');
        $objPHPExcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray(
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(30);

        

// Add some data
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', '年')
            ->setCellValue('B2', '月')
            ->setCellValue('C2', '日')
            ->setCellValue('D2', '发票号')
            ->setCellValue('E2', '结算年份')
            ->setCellValue('F2', '结算月份')
            ->setCellValue('G2', '每月金额')
            ->setCellValue('H2', '合计结算月份')
            ->setCellValue('I2', '合计开票金额')
            ->setCellValue('J2', '单位名称')
            ->setCellValue('K2', '所属公司')
            ->setCellValue('L2', '收入类型')
            ->setCellValue('M2', '同月预估数')
            ->setCellValue('N2', '预估差额')
            ->setCellValue('O2', '备注');

        //加粗居中
        $objPHPExcel->getActiveSheet()->getStyle('A2:O2')->applyFromArray(
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
            foreach ($data as $k => $v){
                $hjy = "";
                $hja = 0;
                $c = count($v);
                $start = $n;
                foreach ($v as $invoice) {
                    $ygce = floatval($invoice['amount']) - floatval($invoice['predict']);
                    $hja = $hja + floatval($invoice['amount']);
                    $hjy .= $invoice['settlement_month'].", ";
                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$n, $invoice['invoice_year'])
                    ->setCellValue('B'.$n, $invoice['invoice_month'])
                    ->setCellValue('C'.$n, $invoice['invoice_day'])
                    ->setCellValue('D'.$n, $invoice['invoice_num'])
                    ->setCellValue('E'.$n, $invoice['settlement_year'])
                    ->setCellValue('F'.$n, $invoice['settlement_month'])
                    ->setCellValue('G'.$n, $invoice['amount'])
                    ->setCellValue('J'.$n, $invoice['com_b_name'])
                    ->setCellValue('K'.$n, $this->getcomname($invoice['com_a']))
                    ->setCellValue('L'.$n, $this->getsubtype($invoice['subject_type']))
                    ->setCellValue('M'.$n, $invoice['predict'])
                    ->setCellValue('N'.$n, $ygce)
                    ->setCellValue('O'.$n, $invoice['remark']);
                    # code...
                    $n++;
                }
                $end = $n-1;
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('H'.$start, $hjy)
                ->setCellValue('I'.$start, $hja);
                $objPHPExcel->getActiveSheet()->mergeCells("H".$start.":H".$end."");
                $objPHPExcel->getActiveSheet()->mergeCells("I".$start.":I".$end."");
            }
        }
// Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle("开票明细");
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
