CMS.Use(['Core/CMS.BlockAction.Action'], function (CMS) {
    CMS.BlockAction.BlockDelete = CMS.BlockAction.Action.extend({

        caption: 'Delete',
        name: 'block-delete',
        color: '#cc1e4b',

        init: function (data) {
            this._super(data);
            var self = this;
            this.domElement.click(function (e) {
                self.deleteBlock();
                return false;
            });
        },

        deleteBlock: function () {
            var blockWrapper = this.domElement.parents('.block-wrapper:first');
            blockWrapper.hide(500);
            $.get(this.postback, {id: this.blockId}, function (data) {
                if (data.code.id <= 0) {
                    blockWrapper.parents('.location:first').remove(blockWrapper);
                } else {
                    CMS.alert(data.code.message);
                    blockWrapper.show(500);
                }
            }, 'json')
        }
    });
});