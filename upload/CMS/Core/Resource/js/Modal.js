CMS.Use([], function (CMS) {
    CMS.Modal = Class.extend({

        domElement: null,

        init: function (data) {
            this.domElement = $('<div>', {
                class: 'jqmWindow'
            });
            $('body').append(this.domElement);
            this.domElement.jqm(data);
        },

        setOptions: function (options) {
            this.domElement.jqm(options);
        },

        setContent: function (content) {
            this.domElement.html(content);
        },

        showLoading: function () {
            this.show();
            this.setContent('<h1>Loading...</h1>');
        },

        show: function (options) {
            if (options) {
                this.setOptions(options);
                this.domElement.jqmShow();
            } else {
                this.domElement.jqmShow();
            }
        },

        hide: function () {
            this.domElement.jqmHide();
        }

    });
});