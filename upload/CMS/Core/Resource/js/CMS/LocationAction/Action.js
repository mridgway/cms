CMS.Use([], function (CMS) {
    CMS.LocationAction = {};
    CMS.LocationAction.Action = Class.extend({

        caption: '',
        name: null,
        postback: null,

        actionClass: 'action',
        domElement: null,

        init: function (data) {
            $.extend(this, data);
            this.getDomElement();
        },

        getDomElement: function () {
            if (null == this.domElement) {
                this.domElement = $('<li>').addClass(this.name).css('background-color', this.color);
                var link = $('<a>', {
                    title: this.caption,
                    css: {
                        display: 'block',
                        height: 15,
                        width: 15
                    },
                    click: function (e) {
                        e.preventDefault();
                    }
                });
                this.domElement.append(link);
            }
            return this.domElement;
        },

        hideContainer: function () {
            $('.block-actions').children().hide(500);
        },

        showContainer: function () {
            $('.block-actions').children().show(500);
        }
    });
    CMS.LocationAction.Action.createAction = function (data) {
        switch (data.plugin) {
            case 'BlockAdd':
                return new CMS.LocationAction.BlockAdd(data);
            default:
                return new CMS.LocationAction.Action(data);
        }
    }
});