CMS.Use(['Core/CMS.BlockAction.Action'], function (CMS) {
    CMS.BlockAction.BlockEdit = CMS.BlockAction.Action.extend({

        caption: 'Edit',
        name: 'block-edit',
        color: '#0865c1',

        prevHtml: null,
        editors: [],

        init: function (data) {
            this.editors = [];
            this._super(data);
            var self = this;
            this.domElement.click(function (e) {
                self.showEditForm();
                return false;
            });
        },

        showEditForm: function () {
            var self = this;
            console.log(this.editors);
            self.hideMenus();
            $.get(self.postback, {id: this.blockId}, function(data) {
                if (data.code.id <= 0) {
                    var block = self.domElement.parent().siblings('.block:first');
                    self.prevHtml = block.html();
                    self.receiveHtml(data.html);
                } else {
                    CMS.alert(data.code.message);
                    self.showMenus();
                }
            }, 'json');
        },

        receiveHtml: function (html) {
            html = $(html);
            var form = html.is('form') ? html : html.find('form:first');
            this.alterForm(form);
            this.setHtml(html, true);
        },

        alterForm: function (form) {
            var self = this;
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
        },

        submitForm: function (data) {
            var self = this;
            $.post(this.postback, data, function(data) {
                if (data.code.id <= 0) {
                    self.destroyEditors();
                    self.setHtml(data.html);
                } else {
                    var html = $(data.html);
                    var form = html.is('form') ? html : html.find('form:first');
                    self.alterForm(form);
                    var block = self.domElement.parent().siblings('.block:first');
                    self.destroyEditors();
                    block.html(html);
                    block.find('.ckeditor').ckeditor(function() {
                        self.editors.push(this);
                    }, CMS.ckeditor.getConfig());
                }
            }, 'json');
        },

        cancelForm: function () {
            this.destroyEditors();
            this.setHtml(this.prevHtml);
        },

        setHtml: function (html, hideContainer) {
            var self = this;
            var block = self.domElement.parent().siblings('.block:first');
            block.hide(500, function () {
                $(this).html(html);
                $(this).find('.ckeditor').ckeditor(function() {
                    self.editors.push(this);
                }, CMS.ckeditor.getConfig());
                $(this).show(500, function() {
                    hideContainer ? self.hideMenus() : self.showMenus();
                });
            });
        },

        destroyEditors: function () {
            console.log(this.editors);
            var self = this;
            $.each(this.editors, function (index, value) {
                self.editors[index].destroy();
            });
            this.editors = [];
            console.log(this.editors);
        }
    });
});