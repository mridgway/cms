CMS.Use(['Core/CMS.PageAction.Action'], function (CMS) {
    CMS.PageAction.PageAdd = CMS.PageAction.Action.extend({

        modalOverrides: {
            title: 'Add Page'
        },

        success: function (data) {
            window.location = data.data.url;
        }
    });
});