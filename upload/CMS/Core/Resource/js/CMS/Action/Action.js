CMS.Use([], function (CMS) {
    CMS.Action = {};
    CMS.Action.Action = Class.extend({
        
        name: null,
        color: '#cccccc',
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
            }
        }
    });
    CMS.Action.Action.createAction = function (data) {
        switch (data.plugin) {
            case 'BlockConfigure':
                return new CMS.Action.BlockConfigure(data);
            case 'BlockDelete':
                return new CMS.Action.BlockDelete(data);
            case 'BlockEdit':
                return new CMS.Action.BlockEdit(data);
            case 'BlockMove':
                return new CMS.Action.BlockMove(data);
            default:
                return new CMS.Action.Action(data);
        }
    }
});