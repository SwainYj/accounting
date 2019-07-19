<?php

!defined('IN_API') && exit('Access Denied');

class predictmodel {

    var $db;
    var $base;

    function __construct(&$base) {
        $this->predictmodel($base);
    }

    function predictmodel(&$base) {
        $this->base = $base;
        $this->db = $base->db;
    }

    function listpredict($param, $list_type, $page, $count){

        $sql = API_DBTABLEPRE."predict WHERE del='0' ";

        if ($param['com_a'] != NULL) {
            $sql .= ' AND com_a="'.$param['com_a'].'"';
        }
        if ($param['subject_type']) {
            $sql .= ' AND subject_type="'.$param['subject_type'].'"';
        }
        if ($param['predict_year']) {
            $sql .= ' AND predict_year="'.$param['predict_year'].'"';
        }
        if ($param['predict_month']!= NULL) {
            $sql .= ' AND predict_month="'.$param['predict_month'].'"';
        }
        if ($param['charger']) {
            $sql .= ' AND charger="'.$param['charger'].'"';
        }
        if ($param['com_b_name']!= NULL) {
            $sql .= ' AND com_b_name="'.$param['com_b_name'].'"';
        }
        // $order = "";
        $order = " ORDER BY com_a,subject_type,predict_month,com_b_name ASC ";

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

    function savepredict($param){
    	$time = date("Ymd",time());
    	$set = " SET update_time = '".$time."'";
        $set.= ", predict_year= '".$param['predict_year']."'";
        $set.= ", predict_month= '".$param['predict_month']."'";
        $set.= ", amount= '".$param['amount']."'";
        $set.= ", com_b_name= '".$param['com_b_name']."'";
        $set.= ", com_a= '".$param['com_a']."'";
        $set.= ", subject_type= '".$param['subject_type']."'";
        $set.= ", charger= '".$param['charger']."'";
        $set.= ", remark= '".$param['remark']."'";

    	if($param['id']){ 
    		//更新
    		$this->db->query("UPDATE ".API_DBTABLEPRE."predict ".$set." WHERE id=".$param['id']);
    		$id = $param['id'];
    	}else{
    		//插入
    		$set. ", create_time='".$time."'";
    		$this->db->query("INSERT INTO ".API_DBTABLEPRE."predict ".$set);
    		$id = $this->db->insert_id();
    	}
        $res['id'] = $id;
    	return $res;
    }

    function predict_detail($id){
        if(!$id)
            return false;

        $res = $this->db->fetch_first("SELECT * FROM ".API_DBTABLEPRE."predict WHERE id = $id");
        $result['data'] = $res;
        return $result;
    }

    function delpredict($id){
    	if(!$id)
    		return false;
    	$this->db->query("DELETE FROM ".API_DBTABLEPRE."predict WHERE id= $id ");
    	return true;
    }

    function export($param){
        $sql = API_DBTABLEPRE."predict WHERE del='0' ";

        if ($param['com_a'] != NULL) {
            $sql .= ' AND com_a="'.$param['com_a'].'"';
        }
        if ($param['subject_type']) {
            $sql .= ' AND subject_type="'.$param['subject_type'].'"';
        }
        if ($param['predict_year']) {
            $sql .= ' AND predict_year="'.$param['predict_year'].'"';
        }
        if ($param['predict_month']!= NULL) {
            $sql .= ' AND predict_month="'.$param['predict_month'].'"';
        }
        if ($param['charger']) {
            $sql .= ' AND charger="'.$param['charger'].'"';
        }
        if ($param['com_b_name']!= NULL) {
            $sql .= ' AND com_b_name="'.$param['com_b_name'].'"';
        }
        // $order = "";
        $order = " ORDER BY com_a,subject_type,predict_month,com_b_name ASC ";
        $sql .= $order;
        $result = $this->db->fetch_all("SELECT * FROM ".$sql);
        $this->downexcel($result);

    }

    function downexcel($data){
        error_reporting(0);
        $name = '预估明细'.date('Ymd');
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
            ->setCellValue('A1', '公司信息')
            ->setCellValue('D1', '预估日期')
            ->setCellValue('G1', '其它');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
        $objPHPExcel->getActiveSheet()->mergeCells('D1:F1');
        $objPHPExcel->getActiveSheet()->mergeCells('G1:H1');
        $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray(
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);

// Add some data
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', '所属公司')
            ->setCellValue('B2', '收入类型')
            ->setCellValue('C2', '单位名称')
            ->setCellValue('D2', '年')
            ->setCellValue('E2', '月')
            ->setCellValue('F2', '预估金额')
            ->setCellValue('G2', '负责人')
            ->setCellValue('H2', '备注');

        //加粗居中
        $objPHPExcel->getActiveSheet()->getStyle('A2:H2')->applyFromArray(
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
            foreach ($data as $k => $predict){
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$n, $this->getcomname($predict['com_a']))
                ->setCellValue('B'.$n, $this->getsubtype($predict['subject_type']))
                ->setCellValue('C'.$n, $predict['com_b_name'])
                ->setCellValue('D'.$n, $predict['predict_year'])
                ->setCellValue('E'.$n, $predict['predict_month'])
                ->setCellValue('F'.$n, $predict['amount'])
                ->setCellValue('G'.$n, $predict['charger'])
                ->setCellValue('H'.$n, $predict['remark']);
                # code...
                $n++;
            }
        }
// Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle("预估明细");
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
