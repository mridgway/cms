CMS.Use(['Core/CMS.PageAction.Action'], function (CMS) {
    CMS.PageAction.PageEdit = CMS.PageAction.Action.extend({

        modalOverrides: {
            title: 'Edit Page'
        },

        success: function (data) {
            window.location = data.data.url;
        }
    });
});