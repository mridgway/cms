CMS.Use(['Core/CMS.AdminAction.Action'], function (CMS) {
    CMS.AdminAction.PageEdit = CMS.AdminAction.Action.extend({

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
                    var html = $(data.html);
                    var form = html.is('form') ? html : html.find('form:first');
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
                    self.modal.setContent(html);
                    self.modal.show();
                } else {
                    CMS.alert(data.code.message);
                    self.modal.hide();
                }
            }, 'json');
        },

        submitForm: function (data) {
            var self = this;
            $.post(this.postback, data, function(data) {
                if (data.code.id <= 0) {
                    self.modal.hide();
                    window.location = '/';
                } else {
                    self.modal.setContent(data.html);
                }
            }, 'json');
        },

        cancelForm: function () {
            this.modal.hide();
        }
    });
});