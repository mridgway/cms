CMS.Use(['Core/CMS.BlockAction.Action'], function (CMS) {
    CMS.BlockAction.BlockMove = CMS.BlockAction.Action.extend({

        caption: 'Move',
        name: 'block-move',
        color: '#878787',
        img: '/resources/core/img/block-button-move.png',

        init: function (data) {
            this._super(data);
        }
    });
});