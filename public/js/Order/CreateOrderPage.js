new Vue({
    el: '.createOrderPage',
    data: function () {
        return {
            isQuickCreate: false
        }
    },
    methods: {
        quickCreateEvent() {
            this.isQuickCreate = true;

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
                });
            }, 200);
        }
    }
});


$('select[name="productid"]').on('select2:select', function (e) {
    var data = e.params.data;
    console.log(data);
});
