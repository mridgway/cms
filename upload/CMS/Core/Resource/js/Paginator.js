CMS.Use([], function (CMS) {

    CMS.Paginator = Class.extend({

        postback: null,
        responseManipulator: null,
        extraPostData: null,

        items: [],
        itemCount: 0,
        currentPage: 1,
        perPage: 0,

        init: function (data) {
            $.extend(this, data);
        },

        getCurrentItems: function () {
            if (null !== this.postback) {
                return this.items;
            }
            var offset = this.perPage * (this.currentPage - 1);
            return this.items.slice(offset, this.perPage);
        },

        loadCurrentPage: function (extraData) {
            var self = this;
            var postData = {
                page: this.currentPage,
                perPage: this.perPage
            };
            $.get(this.postback, $.merge(postData, extraData), function (results) {
                self.itemCount = results.data.rowCount;
                self.items = [];
                $.each(results.data.assets, function (index, value) {
                    self.items.push(self.responseManipulator(value));
                });
            }, 'json');

            return this;
        },

        setItems: function (items) {
            this.postback = null;
            this.items = items;
        },

        addItem: function (item) {
            this.postback = null;
            this.items.push(item);
        },

        setPage: function (page) {
            this.currentPage = page;
        }

    });

});