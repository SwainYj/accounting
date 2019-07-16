<?php 
/**
 * Route配置
 */

// 文件类型
router::parseExtensions('xml','json','php');


// rest 2.0
router::connect('/:controller/:action', array(), array('controller'=>'[a-z0-9]+','action'=>'[a-z0-9]+'));
router::connect('/:controller', array('action' => getgpc('method')), array('controller'=>'[a-z0-9]+'));