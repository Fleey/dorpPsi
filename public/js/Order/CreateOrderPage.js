new Vue({
    el: '.createOrderPage',
    data: function () {
        return {
            isQuickCreate: false
        }
    },
    methods: {
        cancelSave() {
            history.go(-1);
        },
        cancelQuickCreate() {
            this.isQuickCreate = false;

            $('input[name="total_num"]').val('');
            $('input[name="product_price"]').val('');
            $('input[name="discount_price"]').val('');
            $('input[name="product_desc"]').val('');
        },
        quickCreateEvent() {
            this.isQuickCreate = true;

            let _this = this;

            setTimeout(function () {
                $('select[name="productid"]').select2({
                    placeholder: "请选择商品",
                    ajax: {
                        delay: 250,
                        url: '/admin/api/product/search',
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
                }).on('select2:select', function (e) {
                    let data = e.params['data'];
                    let price = data['price'] / 100;

                    $('input[name="product_price"]').val(price);

                    _this.totalNumChangeEvent();
                });
            }, 200);
        },
        totalNumChangeEvent() {
            let totalNum = parseInt($('input[name="total_num"]').val());

            if (isNaN(totalNum) || totalNum <= 0)
                return;

            quickCreateChangeEvent();
        },
        productPriceChangeEvent() {
            let productPrice = parseFloat($('input[name="product_price"]').val())

            if (isNaN(productPrice) || productPrice <= 0)
                return;

            quickCreateChangeEvent();
        },
        saveQuickCreate() {
            let productCount = parseInt($('input[name="total_num"]').val());
            let productPrice = parseFloat($('input[name="product_price"]').val());

            let discountPrice = parseFloat($('input[name="discount_price"]').val());
            let productDesc = $('input[name="product_desc"]').val();

            if (isNaN(productCount) || productCount <= 0) {
                alert('商品数量不能为零或其他特殊字符');
                return;
            }

            if (isNaN(productPrice) || productPrice <= 0) {
                alert('商品价格不能为零或其他特殊字符');
                return;
            }

            if (isNaN(discountPrice) || discountPrice <= 0) {
                alert('商品总金额不能为零或其他特殊字符');
                return;
            }


            let productInfo = $('select[name="productid"] option:selected');

            addOrderInfo(productInfo.text(), productInfo.val(), productPrice, productCount, discountPrice, productDesc);

            this.cancelQuickCreate();
        }
    }
});

function deleteOrderInfoRowEvent() {
    $(this).parent().parent().remove();
}

function addOrderInfo(productName, productId, productPrice, productCount, discountPrice, productDesc) {
    let tableBodyDom = $('div.createOrderPage table>tbody');

    let productNameDom = $(document.createElement('td')).text(productName).attr('data-product-id', productId);
    let productPriceDom = $(document.createElement('td')).text(productPrice);
    let productCountDom = $(document.createElement('td')).text(productCount);
    let discountPriceDom = $(document.createElement('td')).text(discountPrice);
    let productDescDom = $(document.createElement('td')).text(productDesc);

    let trDom = $(document.createElement('tr'));

    let editButtonDom = $(document.createElement('a')).attr({
        'href': 'javascript:void(0);'
    }).text('编辑');

    let deleteButtonDom = $(document.createElement('a')).attr({
        'href': 'javascript:void(0);'
    }).text('删除').bind('click', deleteOrderInfoRowEvent);

    let customActionDom = $(document.createElement('td')).append(editButtonDom, deleteButtonDom);

    trDom.append(productNameDom, productCountDom, productPriceDom, discountPriceDom, productDescDom, customActionDom);

    tableBodyDom.append(trDom);
}

function quickCreateChangeEvent() {
    let productCount = $('input[name="total_num"]').val();
    let productPrice = $('input[name="product_price"]').val();

    let totalMoney = parseInt(productCount) * parseFloat(productPrice);

    totalMoney = Number(totalMoney.toString().match(/^\d+(?:\.\d{0,2})?/));

    $('input[name="discount_price"]').val(totalMoney);
}
