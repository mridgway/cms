CMS.Use([], function (CMS) {
    CMS.AdminAction = {};
    
    CMS.AdminAction.Action = Class.extend({

        name: null,
        postback: null,
        page: null,

        actionClass: 'action',
        domElement: null,
        modal: null,

        init: function (data) {
            $.extend(this, data);
        }
    });
    
    CMS.AdminAction.Action.createAction = function (data) {
    }
});