CMS.Use([], function (CMS) {
    CMS.PageAction = {};
    
    CMS.PageAction.Action = Class.extend({

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
});