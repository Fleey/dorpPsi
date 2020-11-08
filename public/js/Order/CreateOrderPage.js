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
        }
    }
})
