CMS.Use(['Core/CMS.Action.Action'], function (CMS) {
    CMS.Action.BlockMove = CMS.Action.Action.extend({

        caption: 'Move',
        name: 'block-move',
        color: '#878787',

        init: function (data) {
            this._super(data);
        }
    });
});