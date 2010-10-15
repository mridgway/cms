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
                this.loadCurrentPage();
                return this.items;
            }
            var offset = this.perPage * (this.currentPage - 1);
            return this.items.slice(offset, this.perPage);
        },

        loadCurrentPage: function () {
            var self = this;
            var postData = $.merge(this.extraPostData, {
                page: this.currentPage,
                perPage: this.perPage
            });
            $.get(this.postback, postData, function (results) {
                self.items = results.data;
            }, 'json');
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