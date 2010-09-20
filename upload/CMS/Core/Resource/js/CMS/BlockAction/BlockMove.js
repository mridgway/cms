CMS.Use(['Core/CMS.BlockAction.Action'], function (CMS) {
    CMS.BlockAction.BlockMove = CMS.BlockAction.Action.extend({

        caption: 'Move',
        name: 'block-move',
        color: '#878787',

        init: function (data) {
            this._super(data);
        }
    });
});