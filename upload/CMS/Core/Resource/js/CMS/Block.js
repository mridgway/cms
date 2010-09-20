CMS.Use([], function (CMS) {
    CMS.Block = Class.extend({

        id: null,
        weight: null,
        actions: [],

        domElement: null,

        init: function (data) {
            $.extend(this, data);
            this.domElement = $('#block-' + this.id);
            this._setupActions();
        },

        _setupActions: function () {
            var self = this;

            var pluginPaths = [];
            for (i in this.actions) {
                pluginPaths.push('Core/CMS.BlockAction.'+this.actions[i].plugin);
            }

            CMS.Use(pluginPaths, function () {
                var actionsBlock = $('<ul>', {
                    css: {
                        position: 'absolute',
                        right: 0,
                        top: 0
                    },
                    class: 'block-actions'
                });
                self.domElement.parent().css('position', 'relative').prepend(actionsBlock);
                for (i in self.actions) {
                        self.domElement.trigger('Action loaded');
                        self.actions[i] = CMS.BlockAction.Action.createAction(self.actions[i]);
                        actionsBlock.prepend(self.actions[i].domElement);
                }
            });
            
        },

        getPosition: function () {
            return this.domElement.parents('.block-wrapper:first').index();
        },

        getLayout: function () {
            return this.domElement.parents('.location:first').attr('id');
        }
    });
});