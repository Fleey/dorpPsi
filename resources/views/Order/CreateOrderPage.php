<div id="app">
    <el-card class="box-card">
        <div slot="header" class="clearfix">
            <span>
                客户：
                <span>Test66</span>
            </span>
            <el-button style="float: right; padding: 3px 0;" type="text">取消</el-button>
            <el-button style="float: right; padding: 3px 0;margin-right: 15px;" type="text">保存</el-button>
        </div>

        <el-table
            :data="tableData"
            border
            height="750"
            :summary-method="getSummaries"
            show-summary
            style="width: 100%;">
            <el-table-column
                prop="id"
                label="序号"
                width="100">
            </el-table-column>
            <el-table-column
                prop="name"
                label="商品名称">
            </el-table-column>
            <el-table-column
                prop="amount1"
                label="单价">
            </el-table-column>
            <el-table-column
                prop="amount2"
                label="金额">
            </el-table-column>
            <el-table-column
                prop="amount3"
                label="备注" width="200">
            </el-table-column>
        </el-table>
    </el-card>
</div>
