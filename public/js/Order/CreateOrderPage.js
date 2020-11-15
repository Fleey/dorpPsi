new Vue({
    el: '.createOrderPage',
    data: function () {
        return {
            isQuickCreate: false,
            isEditCustomerInfo: false,
            customerInfo: {
                customerid: 0,
                name: '',
                phone: ''
            }
        }
    },
    created: function () {
        setTimeout(function () {
            let time = new Date();
            let day = ("0" + time.getDate()).slice(-2);
            let month = ("0" + (time.getMonth() + 1)).slice(-2);
            let today = time.getFullYear() + "-" + (month) + "-" + (day);
            $('#createTime').val(today);
        }, 200);
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
        editProductCountChangeEvent() {
            let totalNum = parseInt($('input[name="editProductCount"]').val());

            if (isNaN(totalNum) || totalNum <= 0)
                return;

            editChangeEvent();
        },
        editProductPriceChangeEvent() {
            let productPrice = parseFloat($('input[name="editProductPrice"]').val())

            if (isNaN(productPrice) || productPrice <= 0)
                return;

            editChangeEvent();
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

            calcTableData();
        },
        saveEdit() {
            let productId = $('select[name="editProductid"]').val();
            let editProductPrice = $('input[name="editProductPrice"]').val();
            let editProductCount = $('input[name="editProductCount"]').val();
            let editDesc = $('input[name="editDesc"]').val();
            let editDiscountPrice = $('input[name="editDiscountPrice"]').val();

            let productName = $('select[name="editProductid"] option:selected').text();

            editRowDom.find('td:nth-child(1)').attr('data-product-id', productId).text(productName);
            editRowDom.find('td:nth-child(2)').text(editProductCount);
            editRowDom.find('td:nth-child(3)').text(editProductPrice);
            editRowDom.find('td:nth-child(4)').text(editDiscountPrice);
            editRowDom.find('td:nth-child(5)').text(editDesc);

            $('#editOrderInfoModal').modal('hide');

            calcTableData();
        },
        editCustomerInfoEvent() {
            this.isEditCustomerInfo = true;

            let _this = this;

            setTimeout(function () {
                $('select[name="customerInfo"]').select2({
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
                }).on('select2:select', function (e) {
                    let data = e.params['data'];

                    _this.customerInfo.customerid = data['id'];
                    _this.customerInfo.name = data['text'] + ' (' + data['address'] + ')';
                    _this.customerInfo.phone = data['phone'];

                    _this.isEditCustomerInfo = false;
                });
            }, 200);
        },
        saveOrderInfo() {
            let requestData = {
                _token: csrfToken,
                customerid: this.customerInfo.customerid,
                createTime: $('#createTime').val(),
                productList: []
            };

            $('.createOrderPage table>tbody>tr:not(.quick-create)').each(function (key, value) {
                let trDom = $(value);

                let productInfoDom = trDom.find('td:nth-child(1)');
                let productCountDom = trDom.find('td:nth-child(2)');
                let productPriceDom = trDom.find('td:nth-child(3)');
                let discountPriceDom = trDom.find('td:nth-child(4)');
                let productDescDom = trDom.find('td:nth-child(5)');

                requestData.productList.push({
                    productid: productInfoDom.attr('data-product-id'),
                    count: productCountDom.text(),
                    price: productPriceDom.text(),
                    discountPrice: discountPriceDom.text(),
                    desc: productDescDom.text()
                });
            });

            $.post('/admin/api/order/create', requestData, function (ret) {
                alert(ret['msg'])
                if (ret['status'] === false) {
                    return;
                }
                location.href = '/admin/orders';
            });
        }
    }
});

let editRowDom = undefined;

function openEditOrderInfoModal(productName, productId, productPrice, productCount, discountPrice, productDesc) {
    $('input[name="editProductCount"]').val(productCount);
    $('input[name="editProductPrice"]').val(productPrice);
    $('input[name="editDiscountPrice"]').val(discountPrice);
    $('input[name="editDesc"]').val(productDesc);


    let defaultOptionDom = $(document.createElement('option')).attr('value', productId).text(productName);

    $('select[name="editProductid"]').html(defaultOptionDom).select2({
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

        $('input[name="editProductPrice"]').val(price);

        editChangeEvent();
    });

    $('#editOrderInfoModal').modal(true);
}

function deleteOrderInfoRowEvent() {
    $(this).parent().parent().remove();
    calcTableData();
}

function editOrderInfoRowEvent() {
    editRowDom = $(this).parent().parent();

    let productInfoDom = editRowDom.find('td:nth-child(1)');
    let productCountDom = editRowDom.find('td:nth-child(2)');
    let productPriceDom = editRowDom.find('td:nth-child(3)');
    let discountPriceDom = editRowDom.find('td:nth-child(4)');
    let productDescDom = editRowDom.find('td:nth-child(5)');

    openEditOrderInfoModal(productInfoDom.text(), productInfoDom.attr('data-product-id'), productPriceDom.text(), productCountDom.text(), discountPriceDom.text(), productDescDom.text());
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
    }).text('编辑').bind('click', editOrderInfoRowEvent);

    let deleteButtonDom = $(document.createElement('a')).attr({
        'href': 'javascript:void(0);'
    }).css({
        'margin-left': '10px'
    }).text('删除').bind('click', deleteOrderInfoRowEvent);

    let customActionDom = $(document.createElement('td')).append(editButtonDom, deleteButtonDom);

    trDom.append(productNameDom, productCountDom, productPriceDom, discountPriceDom, productDescDom, customActionDom);

    tableBodyDom.append(trDom);
}

function editChangeEvent() {
    let productCount = $('input[name="editProductCount"]').val();
    let productPrice = $('input[name="editProductPrice"]').val();

    let totalMoney = parseInt(productCount) * parseFloat(productPrice);

    totalMoney = Number(totalMoney.toString().match(/^\d+(?:\.\d{0,2})?/));

    $('input[name="editDiscountPrice"]').val(totalMoney);
}

function quickCreateChangeEvent() {
    let productCount = $('input[name="total_num"]').val();
    let productPrice = $('input[name="product_price"]').val();

    let totalMoney = parseInt(productCount) * parseFloat(productPrice);

    totalMoney = Number(totalMoney.toString().match(/^\d+(?:\.\d{0,2})?/));

    $('input[name="discount_price"]').val(totalMoney);
}

function calcTableData() {
    let totalCount = 0;
    let totalMoney = 0;


    $('.createOrderPage table>tbody>tr').each(function (key, value) {
        let dom = $(value);

        if (dom.is('.quick-create'))
            return;


        totalCount += parseInt(dom.find('td:nth-child(2)').text());
        totalMoney += parseFloat(dom.find('td:nth-child(4)').text());
    });

    let tfootDom = $('.createOrderPage table>tfoot>tr');

    tfootDom.find('td:nth-child(2)').text(totalCount);
    tfootDom.find('td:nth-child(4)').text(totalMoney);
}
