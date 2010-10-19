CMS.Use([], function (CMS) {
    CMS.Modal = Class.extend({

        domElement: null,

        init: function (element, options) {
            options = $.extend({
                destroyOnClose: true,
                modal: true,
                width: 450,
                resizable: false
            }, options);
            this.domElement = $(element).dialog(options);
            if (options.destroyOnClose) {
                this.domElement.bind('dialogclose', function () {
                    $(this).dialog('destroy');
                    $(this).remove();
                });
            }
        },

        getDomElement: function () {
            return this.domElement;
        },

        setOptions: function (options) {
            this.domElement.dialog('options', options);
        },

        setContent: function (content) {
            this.domElement.html(content);
        },

        showLoading: function () {
            this.open();
            this.setContent('<h1>Loading...</h1>');
        },

        show: function (options) {
            if (options) {
                this.setOptions(options);
            }
            this.domElement.dialog('open');
        },

        hide: function () {
            this.domElement.dialog('close');
        },

        destroy: function () {
            this.domElement.dialog('destroy');
        }

    });
});