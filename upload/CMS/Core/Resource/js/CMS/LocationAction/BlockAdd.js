CMS.Use(['Core/CMS.LocationAction.Action'], function (CMS) {
    CMS.LocationAction.BlockAdd = CMS.LocationAction.Action.extend({

        caption: 'Add Block',
        name: 'block-add'
    });
});