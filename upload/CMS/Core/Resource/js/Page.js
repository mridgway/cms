CMS.Use(['Core/CMS.Location'], function (CMS) {
    CMS.Page = Class.extend({

        id: null,
        locations: [],
        actions: [],

        domElement: null,

        init: function (data) {
            $.extend(this, data);
            this.domElement = $('#page-' + this.id);
            this._setupLocations();
            if (this.actions.addBlock) {
                this._setupAddBlockActions();
            }
        },

        _setupLocations: function () {
            var self = this;
            for (var i in this.locations) {
                this.locations[i] = new CMS.Location(this.locations[i]);
            }
            for (i in this.locations) {
                this.locations[i].sortable.bind('sortupdate', function (event, ui) {
                    self.updateBlockLocations(event, ui);
                });
                for (var j in this.locations) {
                    if (i != j) {
                        this.locations[i].sortable.sortable('option', 'connectWith', this.locations[j].sortable);
                        this.locations[j].sortable.sortable('option', 'connectWith', this.locations[i].sortable);
                    }
                }
            }
        },

        _setupAddBlockActions: function () {
            var self = this;
            CMS.Use(['Core/CMS.BlockAction.BlockAdd'], function(CMS) {
                for (var i in self.locations) {
                    var addBlockAction = new CMS.BlockAction.BlockAdd({
                        page: self.id,
                        location: i
                    });
                    self.locations[i].actions.push(addBlockAction);
                    self.locations[i].domElement.append(addBlockAction.domElement);
                }
            });
        },

        // Updates block locations based on the current DOM
        updateBlockLocations: function (event, ui) {
            if (null != ui.sender) { // block changed locations
                var movedBlockElement = $(event.originalTarget).is('.block-actions') ?
                    $(event.originalTarget).siblings('.block:first') :
                    $(event.originalTarget).parents('.block-actions:first').siblings('.block:first');
                var movedBlockId = parseInt(movedBlockElement.attr('id').substring(6));
                var originalLocation = this.locations[$(ui.sender).parent().attr('id')];
                
                var movedBlock = null;
                for (i in originalLocation.blocks) {
                    if (originalLocation.blocks[i].id == movedBlockId) {
                        movedBlock = originalLocation.blocks.splice(i, 1);
                    }
                }
                this.locations[$(event.target).parent().attr('id')].blocks.push(movedBlock[0]);
                this.locations[$(ui.sender).parent().attr('id')].updateBlocks();
            }
            this.locations[$(event.target).parent().attr('id')].updateBlocks();

            if (null != ui.sender) {
                this.saveBlocks($(ui.sender));
            } else {
                this.saveBlocks($(event.target));
            }
        },

        /**
         * sends block order and location to the backend
         * this request is done synchronously so that we can ensure it succeded
         *
         * @todo make this fire only one ajax call for each block move
         */
        saveBlocks: function (sourceLocation) {
            var self = this;
            $.ajax({
                async: true,
                data: {
                    page: JSON.stringify({data: [this]})
                },
                dataType: 'json',
                success: function (result) {
                    if (result.code.id > 0) {
                        sourceLocation.sortable('cancel');
                        self.locations[sourceLocation.attr('id')].updateBlocks();
                    }
                },
                type: 'POST',
                url: this.actions['blockRearrange'].postback + '?id=' + self.id
            });
        },

        printBlockOrders: function () {
            for (var i in this.locations) {
                CMS.log(i);
                for (var j in this.locations[i].blocks) {
                    CMS.log(this.locations[i].blocks[j].id);
                }
            }
        }
    });
});