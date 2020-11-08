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
                            &nbsp;<div class="input-group input-group-sm">
                                <select class="form-control productid quick-create select2-hidden-accessible"
                                        style="width: 100%;" name="productid" data-value="" tabindex="-1"
                                        aria-hidden="true">
                                    <option value=""></option>
                                </select><span class="select2 select2-container select2-container--default" dir="ltr"
                                               style="width: 100%;"><span class="selection"><span
                                            class="select2-selection select2-selection--single" role="combobox"
                                            aria-haspopup="true" aria-expanded="false" tabindex="0"
                                            aria-labelledby="select2-productid-gl-container"><span
                                                class="select2-selection__rendered" id="select2-productid-gl-container"><span
                                                    class="select2-selection__placeholder">商品名称</span></span><span
                                                class="select2-selection__arrow" role="presentation"><b
                                                    role="presentation"></b></span></span></span><span
                                        class="dropdown-wrapper" aria-hidden="true"></span></span></div>
                            &nbsp;<div class="input-group input-group-sm">
                                <input style="width: 120px; text-align: right;" type="text" id="total_num"
                                       name="total_num" value="" class="form-control total_num quick-create"
                                       placeholder="输入 下单数量"></div>
                            &nbsp;<div class="input-group input-group-sm">
                                <input style="width: 200px" type="text" id="discount_price" name="discount_price"
                                       value="" class="form-control discount_price quick-create"
                                       placeholder="请输入 总计金额 不填则自动计算"></div>
                            &nbsp;
                            <button class="btn btn-primary btn-sm">提交</button>&nbsp;
                            <a href="javascript:void(0);" class="cancel" @click="isQuickCreate = false">取消</a>
                            <input type="hidden" name="_token" value="XjGTC1A1V1qzwHsfY1jHLwVBjEuTfBn3HxqX8uxu">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>183</td>
                    <td>John Doe</td>
                    <td>11-7-2014</td>
                    <td><span class="label label-success">Approved</span></td>
                    <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                    <td>
                        <button class="btn btn-primary btn-sm" type="button">编辑</button>
                        <button class="btn btn-danger btn-sm" type="button">删除</button>
                    </td>
                </tr>
                <tr>
                    <td>219</td>
                    <td>Alexander Pierce</td>
                    <td>11-7-2014</td>
                    <td><span class="label label-warning">Pending</span></td>
                    <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                    <td>
                        <button class="btn btn-primary btn-sm" type="button">编辑</button>
                        <button class="btn btn-danger btn-sm" type="button">删除</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-default">取消</button>
            <button type="submit" class="btn btn-info pull-right">保存</button>
        </div>
        <!-- /.box-footer -->
    </form>
</div>
