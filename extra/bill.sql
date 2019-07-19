##属性表
CREATE TABLE `xl_attr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `code` varchar(20) NOT NULL COMMENT '代码',
  `value` varchar(255) DEFAULT NULL COMMENT '值',
  `type` varchar(50) NOT NULL COMMENT '类别',
  `remark` varchar(255) DEFAULT NULL COMMENT '说明',
  `status` varchar(10) DEFAULT '1' COMMENT '状态',
  `create_time` varchar(50) DEFAULT NULL COMMENT '创建时间',
  `operator_id` varchar(50) DEFAULT NULL COMMENT '操作人员id',
  `operator_name` varchar(255) DEFAULT NULL COMMENT '操作人员姓名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='数据字典';

###开票明细
CREATE TABLE `xl_invoice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `invoice_num` varchar(100) NOT NULL COMMENT '发票号',
  `invoice_year` varchar(20) DEFAULT '' COMMENT '开票年',
  `invoice_month` varchar(20) DEFAULT '' COMMENT '开票月',
  `invoice_day` varchar(20) DEFAULT '' COMMENT '开票日',
  `settlement_year` varchar(20) DEFAULT '' COMMENT '结算年',
  `settlement_month` varchar(20) DEFAULT '' COMMENT '结算月',
  `amount` varchar(30) DEFAULT '' COMMENT '结算金额',
  `com_b_name` varchar(200) DEFAULT '' COMMENT '单位名称',
  `com_a` varchar(10) DEFAULT '' COMMENT '所属公司',
  `subject_type` varchar(4) DEFAULT '' COMMENT '收入类型',
  `predict` varchar(20) DEFAULT '' COMMENT '预估',
  `remark` varchar(500) DEFAULT '' COMMENT '备注',
  `del` varchar(4) DEFAULT '0' COMMENT '删除状态',
  `create_time` varchar(10) DEFAULT NULL COMMENT '创建时间',
  `update_time` varchar(10) DEFAULT NULL COMMENT '更新时间',
  `operator_id` varchar(50) DEFAULT NULL COMMENT '操作人员id',
  `operator_name` varchar(255) DEFAULT NULL COMMENT '操作人员姓名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='开票明细';

###预估明细
CREATE TABLE `xl_predict` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `com_b_name` varchar(200) DEFAULT '' COMMENT '单位名称',
  `com_a` varchar(10) DEFAULT '' COMMENT '所属公司',
  `subject_type` varchar(4) DEFAULT '' COMMENT '收入类型',
  `predict_year` varchar(20) DEFAULT '' COMMENT '预估年',
  `predict_month` varchar(20) DEFAULT '' COMMENT '预估月',
  `amount` varchar(30) DEFAULT '' COMMENT '预估金额',
  `charger` varchar(100) DEFAULT '' COMMENT '负责人',
  `remark` varchar(500) DEFAULT '' COMMENT '备注',
  `del` varchar(4) DEFAULT '0' COMMENT '删除状态',
  `create_time` varchar(10) DEFAULT NULL COMMENT '创建时间',
  `update_time` varchar(10) DEFAULT NULL COMMENT '更新时间',
  `operator_id` varchar(50) DEFAULT NULL COMMENT '操作人员id',
  `operator_name` varchar(255) DEFAULT NULL COMMENT '操作人员姓名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='预估明细';

###收款明细
CREATE TABLE `xl_receivables` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `com_b_name` varchar(200) DEFAULT '' COMMENT '单位名称',
  `com_a` varchar(10) DEFAULT '' COMMENT '所属公司',
  `subject_type` varchar(4) DEFAULT '' COMMENT '收入类型',
  `receiv_year` varchar(20) DEFAULT '' COMMENT '回款年',
  `receiv_month` varchar(20) DEFAULT '' COMMENT '回款月',
  `receiv_day` varchar(20) DEFAULT '' COMMENT '回款日',
  `invoice_num` varchar(100) NOT NULL COMMENT '发票号',
  `settlement_year` varchar(20) DEFAULT '' COMMENT '结算年',
  `settlement_month` varchar(20) DEFAULT '' COMMENT '结算月',
  `receiv_amount` varchar(30) DEFAULT '' COMMENT '回款金额',
  `remark` varchar(500) DEFAULT '' COMMENT '备注',
  `del` varchar(4) DEFAULT '0' COMMENT '删除状态',
  `create_time` varchar(10) DEFAULT NULL COMMENT '创建时间',
  `update_time` varchar(10) DEFAULT NULL COMMENT '更新时间',
  `operator_id` varchar(50) DEFAULT NULL COMMENT '操作人员id',
  `operator_name` varchar(255) DEFAULT NULL COMMENT '操作人员姓名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='回款明细';

##组织表
CREATE TABLE `xl_org` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `grade` varchar(10) DEFAULT '1' COMMENT '级别',
  `parent_id` varchar(10) DEFAULT '0' COMMENT '父级id',
  `remark` varchar(2000) DEFAULT '' COMMENT '说明',
  `address` varchar(500) DEFAULT '' COMMENT '地址',
  `person_charge` varchar(50) DEFAULT '' COMMENT '负责人',
  `person_phone` varchar(50) DEFAULT '' COMMENT '负责人联系方式',
  `create_time` varchar(50) DEFAULT '' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='组织机构';

INSERT INTO xl_org (`name`, `grade`, `parent_id`) VALUES ("书香" , "1" , "1"),
("古羌" , "1" , "1"),
("丹鼎" , "1" , "1"),
("聚点" , "1" , "1");

INSERT INTO xl_attr (`code`, `value`, `type`) VALUES ("1" , "阅读" , "class"),
("2" , "有声" , "class"),
("3" , "自媒体" , "class"),
("4" , "动漫" , "class");












