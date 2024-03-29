<?php if(!defined('API_ROOT')) exit('Access Denied');?>
<!doctype html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo API_CHARSET;?>" />
    <title>miss 杨的系统</title>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <link rel="shortcut icon" href="<?php echo BASE_API;?>/front/images/favicon.ico" type="image/x-icon">
    <link rel="icon" sizes="any" mask="" href="<?php echo BASE_API;?>/front/images/favicon.ico">
    <script type="text/javascript" src="<?php echo BASE_API;?>/front/common/jquery-3.3.1.min.js"></script>
    <!-- <script type="text/javascript" src="<?php echo BASE_API;?>/front/common/common.js"></script> -->
    <script type="text/javascript" src="<?php echo BASE_API;?>/front/common/layui/layui.js"></script>
    <!-- <script type="text/javascript" src="<?php echo BASE_API;?>/front/common/moment.js"></script> -->

    <link rel="stylesheet" type="text/css" href="<?php echo BASE_API;?>/front/css/common.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_API;?>/front/common/layui/css/layui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_API;?>/front/css/admin.css">
</head>

<body>
    <div class="main">
        <div class="bar">
            <div class="left"></div>
            <div class="buttonBox">
                <!-- <div id="home" class="home" title="返回首页"></div> -->
                <div id="change" class="change" title="修改密码"></div>
                <div id="logout" class="logout" title="退出"></div>
            </div>
        </div>
        <div id="menu" class="menu">
            <div id="sbgl" class="sMenu" data-page="manage-visitor" data-href="manage-visitor.html">
                设备管理
                <div class="icon lfdj"></div>
                <div class="sLine"></div>
            </div>
            <div id="sbtj" data-page="chart" data-href="chart.html" class="pMenu">
                设备统计
                <div class="icon lftj"></div>
                <div class="sLine"></div>
            </div>
            <div for="xtgl" class="pMenu">系统管理<div class="icon xtgl"></div>
                <div class="arrow down"></div>
                <div class="sLine"></div>
            </div>
            <div id="xtgl" class="">
                <div id="jggl" class="sMenu" data-page="manage-org" data-href="manage-org.html">
                    机构管理
                    <div class="sLine"></div>
                    <div class="icon item"></div>
                </div>
                <div id="jsgl" class="sMenu" data-page="manage-role" data-href="manage-role.html">
                    角色管理
                    <div class="sLine"></div>
                    <div class="icon item"></div>
                </div>
                <div id="yhgl" class="sMenu" data-page="manage-user" data-href="manage-user.html">
                    用户管理
                    <div class="sLine"></div>
                    <div class="icon item"></div>
                </div>
            </div>
        </div>
        <div class="content">
            <iframe src="about:blank" id="contentFrame" frameborder="0"></iframe>
        </div>
    </div>

    <div id="frameTrigger" data-param="" style="display: none"></div>
    <script type="text/javascript" src="<?php echo BASE_API;?>/front/js/admin.js"></script>
</body>