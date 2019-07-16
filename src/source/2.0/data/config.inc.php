<?php

/**
 * API配置
 */

// API是否开放
define('API_OPEN', 1);

// API返回默认数据格式
define('API_RESPONSE_DEFAULT_TYPE', 'json');

// 错误输出默认数据格式
define('API_ERROR_DEFAULT_TYPE', 'json');

// 错误输入是否输出详细信息
define('API_ERROR_DISPLAY_ERROR', 0);

// 数据库
define('API_DBHOST', 'localhost');
define('API_DBUSER', 'root');
define('API_DBPW', 'root');
define('API_DBNAME', 'kanshu');
define('API_DBCHARSET', 'utf8');
define('API_DBTABLEPRE', 'xl_');
define('API_DBCONNECT', 0);

// redis
define('API_RDHOST', '172.16.1.22');
define('API_RDPORT', '6379');
define('API_RDPW', '');
define('API_RDKEYPRE', '');

// 应用
define('API_COOKIEPATH', '/');
define('API_COOKIEDOMAIN', 'iermu.com');
define('API_CHARSET', 'utf-8');

define('API_COOKIE_SID', 'IERMUSID');
define('API_COOKIE_RID', 'IERMURID');
define('API_COOKIE_CID', 'IERMUCID');
define('API_COOKIE_UNAME', 'IERMUUNAME');
define('API_COOKIE_UID', 'IERMUUID');
define('API_COOKIE_WX_NICKNAME', 'wx_nickname');

// HTTP状态码
define("API_HTTP_OK", "200 OK");
define("API_HTTP_FOUND", "302 Found");
define("API_HTTP_BAD_REQUEST", "400 Bad Request");
define("API_HTTP_UNAUTHORIZED", "401 Unauthorized");
define("API_HTTP_FORBIDDEN", "403 Forbidden");
define("API_HTTP_NOT_FOUND", "404 Not Found");
define("API_HTTP_INTERNAL_SERVER_ERROR", "500 Internal Server Error");
define("API_HTTP_BAD_GATEWAY", "502 Bad Gateway");
define("API_HTTP_SERVICE_UNAVAILABLE", "503 Service unavailable");

// log
define('LOG_DEBUG_PATH', 'accounting/online');
define('LOG_ACCESS_PATH', 'accounting/access_log');


// qrcode
define('QR_TMP_PATH', API_ROOT.'./data/tmp/');

// weixin
define('WX_MP_URL', '');

// upload
define('API_UPLOAD_DIR', '/usr/data/upload/');

