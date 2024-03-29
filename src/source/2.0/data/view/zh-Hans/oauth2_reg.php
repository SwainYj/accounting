<?php if(!defined('API_ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo API_CHARSET;?>" />
<meta content="iermu.com" name="Copyright" />
<title>与i耳目连接</title>
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<link href="<?php echo BASE_API;?>/images/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo BASE_API;?>/images/oauth2.css" rel="stylesheet" type="text/css" />
<script src="<?php echo BASE_API;?>/js/jquery-1.11.1.min.js"></script>
</head>
<body>
    
<header class="navbar navbar-static-top" id="top" role="banner">
  <div class="container iermu-top-nav">
      <a href="<?php echo $url;?>" class="btn btn-default btn-back">返回</a>
      <h3 class="iermu-logo-reg" style="padding-left:0;">注册i耳目帐号</h3>
  </div>
</header>

<div class="container iermu-login-box">
<form class="reg-form" autocomplete="off" method="post" action="<?php echo BASE_API;?>/oauth/2.0/reg" name="reg" onsubmit="return false;"> 
    <input type="hidden" name="cid" value="<?php echo API_CID;?>">
    <div class="alert alert-danger" role="alert" id="error-log" style="display:none;"></div>
    <div class="alert alert-success" role="alert" id="msg-log" style="display:none;"></div>
    <div class="input-group input-group-lg">
        <span class="input-group-addon ico-email" id="label-email">&nbsp;</span>
        <input type="text" class="form-control" placeholder="填写您的常用邮箱" onfocus="email_focus_change(1)" onblur="email_focus_change(0)" onchange="email_change()" onkeydown="email_change()" id="input-email">
        <span class="ico-del" style="display:none;" id="del-email" onclick="del_email()"></span>
    </div>
    <div class="input-group input-group-lg">
        <span class="input-group-addon ico-username" id="label-username">&nbsp;</span>
        <input type="text" class="form-control" placeholder="填写您的用户名" onfocus="username_focus_change(1)" onblur="username_focus_change(0)" onchange="username_change()" onkeydown="username_change()"  name="username" id="input-username">
        <span class="ico-del" style="display:none;" id="del-username" onclick="del_username()"></span>
    </div>
    <div class="input-group input-group-lg">
        <span class="input-group-addon ico-passwd" id="label-passwd">&nbsp;</span>
        <input type="password" class="form-control" placeholder="6-14位，建议数字字母符号组合" onfocus="passwd_focus_change(1)" onblur="passwd_focus_change(0)" onchange="passwd_change()" onkeydown="passwd_change()" name="password" id="input-passwd">
        <span class="ico-del" style="display:none;" id="del-passwd" onclick="del_passwd()"></span>
    </div>
    <button type="submit" class="btn btn-primary btn-lg btn-submit" onclick="reg_submit();return false;" id="btn-submit" disabled>注册</button>
</form>
</div>

<script>
function email_focus_change(f) {
    if(f) {
        $('#label-email').attr('class','input-group-addon ico-email-focus'); 
        if($('#input-email').val()=='') {
            $('#del-email').hide();
        } else {
            $('#del-email').show();
        }
    } else {
        $('#label-email').attr('class','input-group-addon ico-email'); 
    }
}
function email_change() {
    if($('#input-email').val()=='') {
        $('#del-email').hide();
    } else {
        $('#del-email').show();
    }
    check_submit();
}
function username_focus_change(f) {
    if(f) {
        $('#label-username').attr('class','input-group-addon ico-username-focus'); 
        if($('#input-username').val()=='') {
            $('#del-username').hide();
        } else {
            $('#del-username').show();
        }
    } else {
        $('#label-username').attr('class','input-group-addon ico-username'); 
    }
}
function username_change() {
    if($('#input-username').val()=='') {
        $('#del-username').hide();
    } else {
        $('#del-username').show();
    }
    check_submit();
}
function passwd_focus_change(f) {
    if(f) {
        $('#label-passwd').attr('class','input-group-addon ico-passwd-focus'); 
        if($('#input-passwd').val()=='') {
            $('#del-passwd').hide();
        } else {
            $('#del-passwd').show();
        }
    } else {
        $('#label-passwd').attr('class','input-group-addon ico-passwd'); 
    }
}
function passwd_change() {
    if($('#input-passwd').val()=='') {
        $('#del-passwd').hide();
    } else {
        $('#del-passwd').show();
    }
    check_submit();
}
function del_email() {
    $('#del-email').hide();
    $('#input-email').val('');
    $('#input-email').focus();
}
function del_username() {
    $('#del-username').hide();
    $('#input-username').val('');
    $('#input-username').focus();
}
function del_passwd() {
    $('#del-passwd').hide();
    $('#input-passwd').val('');
    $('#input-passwd').focus();
}
function show_err(msg) {
    $('#msg-log').hide();
    $('#error-log').html(msg);
    $('#error-log').show();
}
function show_msg(msg) {
    $('#error-log').hide();
    $('#msg-log').html(msg);
    $('#msg-log').show();
}
function reg_submit() {
    var email = $('#input-email').val();
    var username = $('#input-username').val();
    var password = $('#input-passwd').val();
    if(email=='') {
        show_err("请输入您的常用邮箱");
        $('#input-email').focus();
        return false;
    }
    if(username=='') {
        show_err("请输入您的帐号");
        $('#input-username').focus();
        return false;
    }
    if(password=='') {
        show_err("请输入您的密码");
        $('#input-passwd').focus();
        return false;
    }
    show_load();
    var post = $.ajax({
      type: "POST",
      url: "<?php echo BASE_API;?>/oauth/2.0/reg",
      data: {"email": email, "username": username, "password": password, "cid": "<?php echo API_CID;?>"}
    });
    post.done(function(data) {
        if(data==0) {
            $('#input-email').val('');
            $('#input-username').val('');
            $('#input-passwd').val('');
            show_msg("注册成功，请返回登录");
            setTimeout('window.location.href = "<?php echo $url;?>"', 500);
        } else {
            switch(data) {
                case "-4":
                    show_err("邮箱格式错误");
                    break;
                case "-5":
                    show_err("邮箱错误，请重新填写");
                    break;
                case "-6":
                    show_err("邮箱已存在");
                    break;
                case "-1":
                    show_err("用户名不符合规则，6-15位");
                    break;
                case "-2":
                    show_err("用户名不符合规则");
                    break;
                case "-3":
                    show_err("用户名已存在");
                    break;
                default:
                    show_err("注册失败，请刷新页面再试");
            }
        }
        hide_load();
    });
    post.fail(function() {
        show_err("注册失败，请刷新页面再试");
        hide_load();
    })
    return false;
}
function show_load() {
    $('#error-log').hide();
    $('#btn-submit').html("注册中..."); 
    $('#btn-submit').attr("disabled",true); 
}
function hide_load() {
    $('#btn-submit').html("注册"); 
    $('#btn-submit').attr("disabled",false); 
}
function check_submit() {
    var email = $('#input-email').val();
    var username = $('#input-username').val();
    var password = $('#input-passwd').val();
    if(email && username && password) {
        $('#btn-submit').attr("disabled",false); 
    } else {
        $('#btn-submit').attr("disabled",true); 
    }
}
</script>

</body>
</html>