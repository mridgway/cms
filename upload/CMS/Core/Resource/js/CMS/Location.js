CMS.Use(['Core/CMS.Block'], function (CMS) {
    CMS.Location = Class.extend({

        sysname: null,

        domElement: null,

        blocks: [],

        init: function (data) {
            $.extend(this, data);
            this.domElement = $('#' + this.sysname);
            this._setupBlocks();
            this._setupSortable();
        },

        _setupBlocks: function () {
            for (var i in this.blocks) {
                this.blocks[i] = new CMS.Block(this.blocks[i]);
            }
        },

        _setupSortable: function () {
            this.domElement.sortable({
                items: 'div.block-wrapper',
                //containment: $('body'),
                cursor: 'move',
                placeholder: 'ui-state-highlight',
                scroll: true,
                scrollSensitivity: 50,
                tolerance: 'pointer',
                distance: 5,
                forceHelperSize: true,
                forcePlaceholderSize: true,
                helper: 'clone',
                revert: true,
                revertDuration: 400,
                handle: 'li.block-move'
            }).css('min-height', '50px');
        },

        updateBlocks: function () {
            for (i in this.blocks) {
                this.blocks[i].weight = this.blocks[i].getPosition();
            }
            this.blocks.sort(function (a, b) {
                return a.weight - b.weight;
            })
        }
    });
});