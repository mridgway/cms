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
        },

        _setupLocations: function () {
            var self = this;
            for (var i in this.locations) {
                this.locations[i] = new CMS.Location(this.locations[i]);
            }
            for (i in this.locations) {
                this.locations[i].domElement.bind('sortupdate', function (event, ui) {
                    self.updateBlockLocations(event, ui);
                });
                for (var j in this.locations) {
                    if (i != j) {
                        this.locations[i].domElement.sortable('option', 'connectWith', this.locations[j].domElement);
                        this.locations[j].domElement.sortable('option', 'connectWith', this.locations[i].domElement);
                    }
                }
            }
        },

        // Updates block locations based on the current DOM
        updateBlockLocations: function (event, ui) {
            if (null != ui.sender) { // block changed locations
                var movedBlock = null;
                console.log(event);
                var movedBlockElement = $(event.originalTarget).parents('.block-actions:first').siblings('.block:first');
                var movedBlockId = parseInt(movedBlockElement.attr('id').substring(6));
                var originalLocation = this.locations[$(ui.sender).attr('id')];
                for (i in originalLocation.blocks) {
                    if (originalLocation.blocks[i].id == movedBlockId) {
                        movedBlock = originalLocation.blocks.splice(i, 1);
                    }
                }
                this.locations[$(event.target).attr('id')].blocks.push(movedBlock[0]);
                this.locations[$(ui.sender).attr('id')].updateBlocks();
            }
            this.locations[$(event.target).attr('id')].updateBlocks();

            if (!this.saveBlocks()) {
                if (null != ui.sender) {
                    $(ui.sender).sortable('cancel');
                    this.locations[$(ui.sender).attr('id')].updateBlocks();
                } else {
                    $(event.target).sortable('cancel');
                    this.locations[$(event.target).attr('id')].updateBlocks();
                }
            }
        },

        /**
         * sends block order and location to the backend
         * this request is done synchronously so that we can ensure it succeded
         */
        saveBlocks: function () {
            var isSuccessful = false;
            var self = this;
            $.ajax({
                async: false,
                data: {
                    page: JSON.stringify({data: [this]})
                },
                dataType: 'json',
                success: function (result) {
                    if (result.code.id == 0) {
                        isSuccessful = true;
                    }
                },
                type: 'POST',
                url: this.actions['blockRearrange'].postback
            });
            return isSuccessful;
        }
    });
});