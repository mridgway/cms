CMS.Use([], function (CMS) {
    CMS.BlockAction = {};
    CMS.BlockAction.Action = Class.extend({

        blockId: null,

        caption: '',
        name: null,
        color: '#cccccc',
        postback: null,
        img: '',

        actionClass: 'action',
        domElement: null,

        init: function (data) {
            $.extend(this, data);
            this.getDomElement();
        },

        getDomElement: function () {
            if (null == this.domElement) {
                this.domElement = $('<li>').addClass(this.name).css('background-color', this.color);
                if (this.img) {
                    this.domElement.css('background-image', 'url('+this.img+')');
                }
                var link = $('<a>', {
                    title: this.caption,
                    css: {
                        display: 'block',
                        height: 25,
                        width: 25
                    },
                    click: function (e) {
                        e.preventDefault();
                    }
                });
                this.domElement.append(link);
            }
            return this.domElement;
        },

        hideMenus: function () {
            $('.block-actions').children().hide(500);
            $('.addBlockMenu').hide(500);
        },

        showMenus: function () {
            $('.block-actions').children().show(500);
            $('.addBlockMenu').show(500);
        }
    });
    CMS.BlockAction.Action.createAction = function (data) {
        switch (data.plugin) {
            case 'BlockConfigure':
                return new CMS.BlockAction.BlockConfigure(data);
            case 'BlockDelete':
                return new CMS.BlockAction.BlockDelete(data);
            case 'BlockEdit':
                return new CMS.BlockAction.BlockEdit(data);
            case 'BlockMove':
                return new CMS.BlockAction.BlockMove(data);
            default:
                return new CMS.BlockAction.Action(data);
        }
    }
});