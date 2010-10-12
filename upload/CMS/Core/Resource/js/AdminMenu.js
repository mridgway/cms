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
            },
            assetManager: {

            }
        },

        domElement: null,
        modal: null,

        init: function (data) {
            $.extend(this, data);
            this.domElement = $('#adminMenu');
            this.modal = this.page.modal;
            this._setupActions();
        },

        // @todo make this use actions that are passed from the backend
        _setupActions: function () {

            var self = this;

            CMS.Use(['Core/CMS.PageAction.PageAdd'], function (CMS) {
                self.actions.pageAdd.domElement = $('.addPage:first', self.domElement);
                self.actions.pageAdd.modal = self.modal;
                self.actions.pageAdd.page = self.page;
                self.actions.pageAdd = new CMS.PageAction.PageAdd(self.actions.pageAdd);
            });

            CMS.Use(['Core/CMS.PageAction.PageEdit'], function (CMS) {
                self.actions.pageEdit.domElement = $('.editPage:first', self.domElement);
                self.actions.pageEdit.modal = self.modal;
                self.actions.pageEdit.page = self.page;
                self.actions.pageEdit = new CMS.PageAction.PageEdit(self.actions.pageEdit);
            });

            CMS.Use(['Core/CMS.PageAction.PageDelete'], function (CMS) {
                self.actions.pageDelete.domElement = $('.deletePage:first', self.domElement);
                self.actions.pageDelete.modal = self.modal;
                self.actions.pageDelete.page = self.page;
                self.actions.pageDelete = new CMS.PageAction.PageDelete(self.actions.pageDelete);
            });

            CMS.Use(['Asset/CMS.AssetManager'], function (CMS) {
                self.actions.assetManager.domElement = $('.assetManager:first', self.domElement);
                self.actions.assetManager = new CMS.AssetManager(self.actions.assetManager);
            });
        }
    });
});