COMMON = {
	// 获取服务URL
    filterURL: function (url) {
        return COMMON.getRequestStamp(CONFIG.serverURL + url);
    }
    , getRequestStamp: function (url) {
        var date = new Date();
        var ss = date.getMilliseconds();
        if (url.indexOf('?') != -1) {
            return url + '&_stamp=' + (ss + '_' + Math.random());
        } else {
            return url + '?_stamp=' + (ss + '_' + Math.random());
        }
    },

	renderSetup: function (options) {
	    options.method = (options.method ? options.method : 'post');

	    // options.contentType = 'application/json;charset=UTF-8';

	    // options.headers = (options.headers ? options.headers : {
	    //     'content-type': 'application/json;charset=UTF-8',
	    //     'accept': 'application/json'
	    // });

	    var callbackParseData = (typeof options.parseData === "function") ? options.parseData : function () { };
	    options.parseData = function (res) {
	    return callbackParseData(res);
	    };

	    return options;
	},
	getUrlParams: function (url, name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = url.substr(1).match(reg);
        if (r != null) return encodeURI(r[2]); return null;
    },
    jsonToQuery: function (params) {
        var query = [];

        for (var k in params) {
            query.push(k + '=' + params[k]);
        }

        return query.join('&');
    },
}