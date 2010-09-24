CMS.Use([], function (CMS) {
    CMS.AdminAction = {};
    
    CMS.AdminAction.Action = Class.extend({

        name: null,
        postback: null,

        actionClass: 'action',
        domElement: null,

        init: function (data) {
            $.extend(this, data);
            this.getDomElement();
        }
    });
    
    CMS.AdminAction.Action.createAction = function (data) {
    }
});