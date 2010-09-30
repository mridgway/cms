CMS.Use(['Core/CMS.Modal'], function (CMS) {
    CMS.AdminMenu = Class.extend({

        page: null,
        actions: {
            pageAdd: {
                plugin: 'PageAdd',
                postback: '/direct/page/add'
            },
            pageEdit: {
                plugin: 'PageEdit',
                postback: '/direct/page/edit'
            },
            pageDelete: {
                plugin: 'PageDelete',
                postback: '/direct/page/delete'
            }
        },

        domElement: null,
        modal: null,

        init: function (data) {
            $.extend(this, data);
            this.domElement = $('#adminMenu');
            this.modal = new CMS.Modal();
            this._setupActions();
        },

        // @todo make this use actions that are passed from the backend
        _setupActions: function () {

            var self = this;

            CMS.Use(['Core/CMS.AdminAction.PageAdd'], function (CMS) {
                self.actions.pageAdd.domElement = $('.addPage:first', self.domElement);
                self.actions.pageAdd.modal = self.modal;
                self.actions.pageAdd.page = self.page;
                self.actions.pageAdd = new CMS.AdminAction.PageAdd(self.actions.pageAdd);
            });

            CMS.Use(['Core/CMS.AdminAction.PageEdit'], function (CMS) {
                self.actions.pageEdit.domElement = $('.editPage:first', self.domElement);
                self.actions.pageEdit.modal = self.modal;
                self.actions.pageEdit.page = self.page;
                self.actions.pageEdit = new CMS.AdminAction.PageEdit(self.actions.pageEdit);
            });

            CMS.Use(['Core/CMS.AdminAction.PageDelete'], function (CMS) {
                self.actions.pageDelete.domElement = $('.deletePage:first', self.domElement);
                self.actions.pageDelete.modal = self.modal;
                self.actions.pageDelete.page = self.page;
                self.actions.pageDelete = new CMS.AdminAction.PageDelete(self.actions.pageDelete);
            });
        }
    });
});