<?php
if (isset($_GET['_pjax']))
    echo '<script>location.reload()</script>';
?>
<div class="row">
    <div class="col-md-12">
        <div class="box grid-box">

            <div class="box-header with-border">
                <div class="pull-right">
                    <div class="btn-group pull-right" style="margin-right: 10px">
                        <a href="javascript:alert('当前功能维护中');" class="btn btn-sm btn-twitter" title="打印">
                            <i class="fa fa-download"></i>
                            <span class="hidden-xs"> 打印当前订单</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="box-header with-border filter-box" id="filter-box">
                <div class="form-horizontal">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-body">
                                <div class="fields-group">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"> 客户名称</label>
                                        <div class="col-sm-8">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-pencil"></i>
                                                </div>

                                                <select class="form-control" id="customerName"
                                                        style="width: 100%;"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">创建时间</label>
                                        <div class="col-sm-8" style="width: 390px">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control" id="created_at_start"
                                                       placeholder="开始时间" name="created_at[start]" value=""
                                                       autocomplete="off"><span class="input-group-addon"
                                                                                style="border-left: 0; border-right: 0;">-</span>

                                                <input type="text" class="form-control" id="created_at_end"
                                                       placeholder="结束时间" name="created_at[end]" value=""
                                                       autocomplete="off"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-2"></div>
                                <div class="col-md-8">
                                    <div class="btn-group pull-left">
                                        <button class="btn btn-info submit btn-sm" id="SearchData">
                                            <i class="fa fa-search"></i>&nbsp;&nbsp;搜索
                                        </button>
                                    </div>
                                    <div class="btn-group pull-left " style="margin-left: 10px;">
                                        <a href="javascript:location.reload();"
                                           class="btn btn-default btn-sm"><i class="fa fa-undo"></i>&nbsp;&nbsp;重置</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover grid-table" id="orderInfoList">
                    <thead>
                    <tr>
                        <th class="column-name">商品名称</th>
                        <th class="column-unit">数量</th>
                        <th class="column-price">单价</th>
                        <th class="column-discount-price">累计金额</th>
                        <th class="column-desc">备注</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <td>合计：</td>
                        <td></td>
                        <td></td>
                        <td>0</td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
            </div>

            <!-- /.box-body -->
        </div>
    </div>
</div>
<script>
    $(function () {
        let csrf = '<?php echo $csrf; ?>';

        $('#created_at_start').datetimepicker({"format": "YYYY-MM-DD HH:mm:ss", "locale": "zh-CN"});
        $('#created_at_end').datetimepicker({"format": "YYYY-MM-DD HH:mm:ss", "locale": "zh-CN", "useCurrent": false});
        $("#created_at_start").on("dp.change", function (e) {
            $('#created_at_end').data("DateTimePicker").minDate(e.date);
        });
        $("#created_at_end").on("dp.change", function (e) {
            $('#created_at_start').data("DateTimePicker").maxDate(e.date);
        });

        $('#customerName').select2({
            placeholder: "请选择客户",
            ajax: {
                delay: 250,
                url: '/admin/api/customer/search',
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;

                    return {
                        results: data.data,
                        pagination: {
                            more: (params.page * 20) < data.total
                        }
                    };
                }
            }
        });
        $('#SearchData').click(function () {
            let customerid = $('#customerName').val();
            let createTime = $('#created_at_start').val();
            let endTime = $('#created_at_end').val();

            if (customerid === undefined || customerid === 0 || customerid === null) {
                alert('必须选中客户，才能够进行查询');
                return;
            }

            $.getJSON('/admin/api/order/customer/' + customerid, {
                createTime: createTime,
                endTime: endTime
            }, function (data) {
                if (data['status'] === false) {
                    alert(data['msg']);
                    return;
                }

                renderOrderInfoData(data['data']);
            });

        });

        function renderOrderInfoData(data) {
            let tbodyDom = $('#orderInfoList tbody').html('')

            let totalMoney = 0;

            $.each(data, function (key, content) {
                let trDom = $(document.createElement('tr'));

                let productNameDom = $(document.createElement('td')).text(content['ProductName']);
                let orderProductCountDom = $(document.createElement('td')).text(content['OrderProductTotalNum']);
                let OrderDiscountPriceDom = $(document.createElement('td')).text(content['OrderDiscountPrice']);
                let productPrice = Number(((content['OrderDiscountPrice']) / content['OrderProductTotalNum']).toString().match(/^\d+(?:\.\d{0,2})?/));
                let orderProductPriceDom = $(document.createElement('td')).text(productPrice);
                let orderInfoDescDom = $(document.createElement('td')).text(content['ProductDesc']);

                totalMoney += content['OrderDiscountPrice'];

                trDom.append(productNameDom, orderProductCountDom, orderProductPriceDom, OrderDiscountPriceDom, orderInfoDescDom);

                tbodyDom.append(trDom);
            });

            $('#orderInfoList tfoot>tr>td:nth-child(4)').text(totalMoney);
        }
    });
</script>
