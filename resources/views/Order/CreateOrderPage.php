<div class="box box-info createOrderPage">
    <div class="box-header with-border">
        <h3 class="box-title">单位抬头 <span style="font-size: 12px;color:#000000;">(双击编辑,不填默认单位名称)</span></h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal">
        <div class="box-body">
            <table class="table table-hover">
                <tbody>
                <tr>
                    <th>商品名称</th>
                    <th>数量</th>
                    <th>单价</th>
                    <th>金额</th>
                    <th>备注</th>
                    <th>操作</th>
                </tr>
                <tr class="quick-create">
                    <td colspan="7"
                        style="height: 47px;padding-left: 57px;background-color: #f9f9f9; vertical-align: middle;">
                        <span class="create" style="color: #bdbdbd;cursor: pointer;" v-if="!isQuickCreate"
                              @click="quickCreateEvent">
                            <i class="fa fa-plus"></i>&nbsp;快速创建
                        </span>

                        <div class="form-inline create-form" v-if="isQuickCreate">

                            &nbsp;
                            <!--商品选择框-->
                            <div class="input-group input-group-sm">
                                <select class="form-control quick-create" name="productid"
                                        style="width: 150px;"></select>
                            </div>
                            <!--商品选择框-->
                            &nbsp;
                            <!--商品数量-->
                            <div class="input-group input-group-sm">
                                <input style="width: 120px; text-align: right;" type="text" id="total_num"
                                       @input="totalNumChangeEvent"
                                       name="total_num" value="" class="form-control total_num quick-create"
                                       placeholder="输入 下单数量"></div>
                            <!--商品数量-->

                            <!--商品单价-->
                            <div class="input-group input-group-sm">
                                <input style="width: 120px; text-align: right;" type="text" id="product_price"
                                       @input="productPriceChangeEvent"
                                       name="product_price" value="" class="form-control product_price quick-create"
                                       placeholder="输入 商品单价"></div>
                            <!--商品单价-->

                            &nbsp;
                            <!--商品总金额-->
                            <div class="input-group input-group-sm">
                                <input style="width: 200px" type="text" id="discount_price" name="discount_price"
                                       value="" class="form-control discount_price quick-create"
                                       placeholder="请输入 总计金额 不填则自动计算"></div>
                            <!--商品总金额-->
                            &nbsp;
                            <!--商品备注-->
                            <div class="input-group input-group-sm">
                                <input style="width: 200px" type="text" id="product_desc" name="product_desc"
                                       value="" class="form-control product_desc quick-create"
                                       placeholder="请输入 备注信息 可不填"></div>
                            <!--商品备注-->

                            <button class="btn btn-primary btn-sm" type="button" @click="saveQuickCreate">提交</button>&nbsp;
                            <a href="javascript:void(0);" class="cancel" @click="cancelQuickCreate">取消</a>
                            <input type="hidden" name="_token" value="XjGTC1A1V1qzwHsfY1jHLwVBjEuTfBn3HxqX8uxu">
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="button" class="btn btn-default" @click="cancelSave">取消</button>
            <button type="submit" class="btn btn-info pull-right">保存</button>
        </div>
        <!-- /.box-footer -->
    </form>
</div>
<div class="modal fade in" id="editOrderInfoModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">编辑订单商品信息</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>商品类型</label>
                    <select name="" id=""></select>
                    <input type="text" class="form-control" placeholder="Enter ...">
                </div>
                <div class="form-group">
                    <label>商品数量</label>
                    <input type="text" class="form-control" placeholder="请输入商品数量">
                </div>
                <div class="form-group">
                    <label>商品单价</label>
                    <input type="text" class="form-control" placeholder="请输入商品单价">
                </div>
                <div class="form-group">
                    <label>商品总金额</label>
                    <input type="text" class="form-control" placeholder="请输入商品总金额">
                </div>
                <div class="form-group">
                    <label>备注信息</label>
                    <input type="text" class="form-control" placeholder="请输入备注信息">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary">保存修改</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
