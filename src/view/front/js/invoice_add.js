layui.use(['form', 'layedit', 'layer'], function () {
    var form = layui.form;
    var layedit = layui.layedit;
    var layer = layui.layer;


    var index = layedit.build('artCont', {
        tool: ['strong', 'italic', 'left', 'center', 'right', '|', 'face', 'link', 'image', 'underline']
    });

    // 公司初始
    var comaType = $('.layui-form select[lay-filter="com_aType"]').parent();
    for (var k in CONFIG.com_a) {
        comaType.find('select').append('<option value="' + k + '">' + CONFIG.com_a[k] + '</option>');
        comaType.find('dl').append('<dd lay-value="' + k + '">' + CONFIG.com_a[k] + '</dd>')
    };

    // 项目类型初始
    var subjectType = $('.layui-form select[lay-filter="subject_typeType"]').parent();
    for (var k in CONFIG.subject) {
        subjectType.find('select').append('<option value="' + k + '">' + CONFIG.subject[k] + '</option>');
        subjectType.find('dl').append('<dd lay-value="' + k + '">' + CONFIG.subject[k] + '</dd>')
    };

    form.render('select', 'invoiceCreateForm');

    form.verify({

        invoice_num: function (value, item) {
            if ($.trim(value).length < 1) {
                return '发票号不能为空';
            }
        },

        com_aSelect: function (value, item) {
            if (value.length === 0) {
                return '请选择所属公司';
            }
        },
        subject_typeSelect: function (value, item) {
            if (value.length === 0) {
                return '请选择收入类型';
            }
        }
    });

    // 修改初始
    var editId = COMMON.getUrlParams(location.search, 'id');
    reqUrl = '/v2/invoice/save';
    $('#createBanner').hide();
    if (editId) {
        $('#createBanner').hide();
        reqUrl = '/v2/invoice/save';

        $.ajax({
            url: COMMON.filterURL('/v2/invoice/detail'),
            type: 'get',
            data: { id: editId },
            success: function (res) {
                if (res.code === 200 && res.data) {

                    var coma = res.data.com_a;
                    var subj = res.data.subject_type;
                    $('#com_a').val(coma);
                    $('#subject_type').val(subj);
                    $('#invoice_num').val((res.data.invoice_num || ''));
                    $('#invoice_year').val((res.data.invoice_year || ''));
                    $('#invoice_month').val((res.data.invoice_month || ''));
                    $('#invoice_day').val((res.data.invoice_day || ''));
                    $('#settlement_year').val((res.data.settlement_year || ''));
                    $('#settlement_month').val((res.data.settlement_month || ''));
                    $('#amount').val((res.data.amount || ''));
                    $('#com_b_name').val((res.data.com_b_name || ''));
                    $('#predict').val((res.data.predict || ''));
                    $('#remark').val((res.data.remark || ''));
                    form.render();
                    layedit.setContent(index, res.data);
                }
            },
            error: function (res) {
                layer.msg('拉取失败，请稍后再试!');
            }
        });
    }

    form.on('submit(createInvoice)', function (data) {
        var formData = data.field;
        console.log(formData);
        if (editId) {
            formData.id = editId;
        }
        $.ajax({
            url: COMMON.filterURL(reqUrl),
            type: 'post',
            data: formData,
            success: function (res) {
                if (res.code === 200) {
                    // if (editId) {
                        parent.formSave({ msg: '操作成功！' });
                        return false;
                    // }
                    // layer.msg('操作成功');
                    // window.setTimeout(function () {
                    //     location.href = 'index.html';
                    // }, 300);
                    // return false;
                }

                err(res.msg);
            },
            error: function (res) {
                err(res.msg);
            }
        });

        return false;
    });

});

function err(editId, msg) {
    if (editId) {
        parent.formClose({ msg: ('操作异常:' + msg) });
        return false;
    }
    layer.msg('操作异常:' + msg);
}
