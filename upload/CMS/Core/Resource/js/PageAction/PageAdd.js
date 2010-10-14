CMS.Use(['Core/CMS.PageAction.Action'], function (CMS) {
    CMS.PageAction.PageAdd = CMS.PageAction.Action.extend({

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
            $.get(self.postback, {id: self.page.id}, function(data) {
                if (data.code.id <= 0) {
                    self.receiveHtml(data.html);
                } else {
                    CMS.alert(data.code.message);
                }
            }, 'json');
        },

        receiveHtml: function (html) {
            html = $(html);
            var form = html.is('form') ? html : html.find('form:first');
            this.alterForm(form);
            if (null !== this.modal) {
                this.modal.setContent(html);
            } else {
                this.modal = new CMS.Modal(html, {
                    title: 'Add Page',
                    modal: true,
                    width: 450,
                    resizable: false
                });
                var self = this;
                this.modal.domElement.bind('dialogclose', function () {
                    self.cancelForm();
                });
            }
        },

        alterForm: function (form) {
            var self = this;
            // hook submit
            form.submit(function (e) {
                self.submitForm($(this).serialize());
                return false;
            });
        },

        submitForm: function (data) {
            var self = this;
            $.post(this.postback, data, function(data) {
                if (data.code.id <= 0) {
                    self.cancelForm();
                    window.location = data.data.url;
                } else {
                    self.receiveHtml(data.html);
                }
            }, 'json');
        },

        cancelForm: function () {
            this.modal.destroy();
            this.modal = null;
        }
    });
});