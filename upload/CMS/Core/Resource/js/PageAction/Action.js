CMS.Use([], function (CMS) {
    CMS.PageAction = {};
    
    CMS.PageAction.Action = Class.extend({

        modalOverrides: {

        },

        modalOptions: {
            title: 'Action',
            modal: true,
            resizable: false
        },

        success: $.noop,

        name: null,
        postback: null,
        page: null,

        actionClass: 'action',
        domElement: null,
        modal: null,

        init: function (data) {
            $.extend(this, data);
            this.modalOptions = $.extend({}, this.modalOptions, this.modalOverrides);
            var self = this;
            this.domElement.click(function (e) {
                self.showForm();
                return false;
            });
        },

        receiveHtml: function (html) {
            html = $(html);
            var form = html.is('form') ? html : html.find('form:first');
            this.alterForm(form);
            if (null !== this.modal) {
                this.modal.setContent(html);
            } else {
                this.modal = new CMS.Modal(html, this.modalOptions);
                var self = this;
                this.modal.domElement.bind('dialogclose', function () {
                    self.cancel();
                });
            }
        },

        showForm: function () {
            var self = this;
            $.get(self.postback, {id: self.page.id}, function(data) {
                if (data.code.id <= 0) {
                    self.receiveHtml(data.html);
                } else {
                    CMS.alert(data.code.message);
                }
            }, 'json');
        },

        alterForm: function (form) {
            var self = this;
            if (!form)
                return;
            
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
                    if (false !== self.success(data)) {
                        self.cancel();
                    }
                } else {
                    self.receiveHtml(data.html);
                }
            }, 'json');
        },

        cancel: function () {
            this.modal.destroy();
            this.modal = null;
        }
    });
});