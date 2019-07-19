

layui.use(['table', 'form', 'laypage', 'laydate', 'layer', 'jquery'], function () {
    window.table = layui.table;
    window.laypage = layui.laypage;
    window.layer = layui.layer;
    var form = layui.form;
    var laydate = layui.laydate;
    window.expOption = {};


    $('#vsEnter').on('click', function () {
        openFrame('', {
            layerOpts: {
                content: [('invoice_add.html')]
            }
        });
    });

	  //监听提交
	  form.on('submit(formDemo)', function(data){
	    // layer.msg(JSON.stringify(data.field));
	    renderTable(table,laypage,data.field);
	    return false;
	  });

      // 导出
    form.on('submit(export)', function (data) {
        var url = '/v2/invoice/export';
        if (data.field) {
            url = url + '?' + COMMON.jsonToQuery(data.field);
        }
        url = COMMON.filterURL(url);
        window.location.href=url;

        // COMMON.exportToFile(url, {
        //     fileName: '开票明细_' + (+new Date()) + '.xls'
        // });
        return false;
    });

    renderTable(table, laypage, {});
})

function renderTable(table, laypage, formData) {
	var url = COMMON.filterURL('/v2/invoice/list');
    table.render(COMMON.renderSetup({
        elem: '#invoiceTable',
        url: url,
        method: 'post',
        where: formData,
        cols: [[ //表头
	      {field: 'invoice_year', title: '开票年', width:80}
	      ,{field: 'invoice_month', title: '开票月', width:80, sort: true}
	      ,{field: 'invoice_day', title: '开票日', width:80, sort: true} 
	      ,{field: 'invoice_num', title: '发票号', width: 80,}
	      ,{field: 'settlement_year', title: '结算年份', width: 70}
	      ,{field: 'settlement_month', title: '结算月份', width: 70}
	      ,{field: 'amount', title: '每月金额', width: 110, sort: true}
	      ,{field: 'com_b_name', title: '单位名称', width: 200}
	      ,{field: 'com_a', title: '所属公司', width: 80}
	      ,{field: 'subject_type', title: '收入类型', width: 80}
	      ,{field: 'predict', title: '同月预估数', width: 110}
	      ,{field: 'predict_diff', title: '预估差额', width: 100}
          ,{field: 'remark', title: '备注', width: 130}
          ,{field: 'handle', title: '操作', width: 200}
	    ]],

        // 格式化返回数据
        response: {
            // statusName: 'code',
            statusCode: 200
        },
        parseData: function (res) {
            // 处理服务异常
            if (parseInt(res.code) !== 200) {
                return {
                    code: (parseInt(res.code) || -1),
                    msg: res.msg
                }
            }

            // var data = invoiceList(res.data);
            return {
                code: (parseInt(res.code) || -1),
                count: res.count,
                total: res.total,
                data: invoiceList(res.data)
            };
        },
        done: function (res, curr, count) {
            // 修改
            $('.layui-table input[data-type="update"]').on('click', function () {
                var treId = $(this).data('id');
                // location.href = '../admin/article-create.html?id=' + artId;
                layer.open({
                    type: 2,
                    title: '开票明细记录修改',
                    offset: 'auto',
                    area: ['1100px', '600px'],
                    shadeClose: true,
                    content: 'invoice_add.html?id=' + treId
                });
            });
            // 删除
            $('.layui-table input[data-type="delete"]').on('click', function () {
                if (!confirm('确认删除该条记录吗?')) {
                    return;
                }
                var treId = $(this).data('id');
                $.ajax({
                    url: COMMON.filterURL('/v2/invoice/del'),
                    type: 'get',
                    data: {
                        id: treId
                    },
                    success: function (res) {
                        layer.msg('操作成功');
                        // 重载数据
                        renderTable(table, laypage, formData);
                    },
                    error: function (res) {
                        layer.msg('操作异常，请稍后再试!');
                    }
                });
            });
        }
    }));
}

function invoiceList(data, $) {
    var newData = (data || []).map(function (item) {
        // item.category = getCategory(item.categoryId);
        // item.createTime = item.createdDate;
        item.com_a = CONFIG.com_a[item.com_a];
        item.subject_type = CONFIG.subject[item.subject_type];
        item.predict_diff = parseFloat(parseFloat(item.amount) - parseFloat(item.predict)).toFixed(2);
        item.handle = buttons(item.id);
        return item;
    });
    return newData;
}
// 文章操作
function buttons( id) {
    var html;
    html = '<input type="button" value="修改" data-id="' + id + '" data-type="update" class="layui-btn"><input type="button" value="删除"  data-id="' + id + '" data-type="delete" class="layui-btn layui-btn-warm">';
    return html;
}

function openFrame(str, options) {
    var dom = window.parent.document.getElementById('frameTrigger');
    $(dom).attr('data-param', str).attr('data-options', JSON.stringify(options));
    // dom.click();
    frameTriggerClick();
}

function frameTriggerClick(){
	console.log($('#frameTrigger')[0].dataset.options);
    var options = JSON.parse($('#frameTrigger')[0].dataset.options);
    windOpen(layer, $(this).data('param'), options.layerOpts);
}
function windOpen(layer, params, opts) {
    opts = (opts ? opts : {});
    layer = layer || window.layer;
    layer.open({
        type: (opts.type || 2),
        offset: (opts.offset || 'auto'),
        area: (opts.area || ['1100px', ($(window).height() - 40) + 'px']),
        title: (opts.title || '开票信息录入'),
        content: (opts.content || [('invoice_add.html' + params)])
    });
}

// 新增|修改 刷新机制 (供子窗口调用)
function formSave(opts) {
    renderTable(table, laypage, { pageable: { rows: 20, page: 1 } });
    opts = opts ? opts : {};
    formClose(opts);
}

// 关闭弹层 （供子窗口调用）
function formClose(opts) {
    if (opts && opts.msg) {
        layer.msg(opts.msg);
    }
    layer.closeAll('iframe');
}

