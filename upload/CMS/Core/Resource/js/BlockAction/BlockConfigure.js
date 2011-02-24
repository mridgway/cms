CMS.Use(['Core/CMS.BlockAction.Action'], function (CMS) {
    CMS.BlockAction.BlockConfigure = CMS.BlockAction.Action.extend({

        caption: 'Configure',
        name: 'block-configure',
        color: '#59c866',
        img: '/resources/core/img/block-button-configure.png'
    });
});