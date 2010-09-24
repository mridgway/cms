CMS.Use(['Core/CMS.BlockAction.Action'], function (CMS) {
    CMS.BlockAction.BlockEdit = CMS.BlockAction.Action.extend({

        caption: 'Edit',
        name: 'block-edit',
        color: '#0865c1',

        prevHtml: null,

        init: function (data) {
            this._super(data);
            var self = this;
            this.domElement.click(function (e) {
                self.showEditForm();
                return false;
            });
        },

        showEditForm: function () {
            var self = this;
            self.hideContainer();
            $.get(self.postback, function(data) {
                if (data.code.id <= 0) {
                    var block = self.domElement.parent().siblings('.block:first');
                    self.prevHtml = block.html();
                    var newBlock = $(data.html);
                    var form = newBlock.is('form') ? newBlock : newBlock.find('form:first');
                    // hook submit
                    form.submit(function (e) {
                        self.submitForm($(this).serialize());
                        return false;
                    });
                    // add cancel link
                    var cancelLink = $('<a>', {
                        click: function () {
                            self.cancelForm();
                            return false;
                        },
                        text: 'Cancel',
                        href: '#'
                    });
                    form.append(cancelLink);
                    self.setHtml(newBlock, true);
                } else {
                    CMS.alert(data.code.message);
                    self.showContainer();
                }
            }, 'json');
        },

        submitForm: function (data) {
            var self = this;
            $.post(this.postback, data, function(data) {
                if (data.code.id <= 0) {
                    self.setHtml(data.html);
                } else {
                    CMS.alert(data.code.message);
                }
            }, 'json');
        },

        cancelForm: function () {
            this.setHtml(this.prevHtml);
        },

        setHtml: function (html, hideContainer) {
            var self = this;
            var block = self.domElement.parent().siblings('.block:first');
            block.hide(500, function () {
                $(this).html(html);
                $(this).show(500, function() {
                    hideContainer ? self.hideContainer() : self.showContainer();
                });
            });
        }
    });
});