CMS.Use(['Core/CMS.Block'], function (CMS) {
    CMS.Location = Class.extend({

        sysname: null,

        domElement: null,
        sortable: null,

        blocks: [],
        actions: [],

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
            var wrap = $('<div>', {'class': 'locationInnerWrap'});
            var children = this.domElement.children();
            children.find('script').remove();  // prevent javascript from executing again...
            if (children.length) {
                children.wrapAll(wrap);
            } else {
                this.domElement.append(wrap);
            }
            this.sortable = $('.locationInnerWrap:first', this.domElement);
            this.sortable.sortable({
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
                this.blocks[i].location = this.sysname;
            }
            this.blocks.sort(function (a, b) {
                return a.weight - b.weight;
            });
        }
    });
});