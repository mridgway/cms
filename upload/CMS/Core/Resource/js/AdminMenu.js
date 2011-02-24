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
            knowhowArticleAdd: {
                postback: '/direct/content/add/?type=knowhowArticle',
                modalOverrides: {
                    title: 'Create Know How Article'
                },
                success: function (data) {
                    window.location = data.data.url;
                }
            },
            companyAdd: {
                postback: '/direct/content/add/?type=company',
                modalOverrides: {
                    title: 'Create Company',
                    width: 625
                },
                success: function (data) {
                    window.location = data.data.url;
                }
            },
            companyLocationAdd: {
                postback: '/direct/content/add/?type=companyLocation',
                modalOverrides: {
                    title: 'Create Company Location'
                },
                success: function (data) {
                    window.location = data.data.url;
                }
            },
            landingAdd: {
                postback: '/direct/content/add/?type=localeLocale',
                modalOverrides: {
                    title: 'Create Landing Page'
                },
                success: function (data) {
                    window.location = data.data.url;
                }
            },
            partnerAdd: {
                postback: '/direct/content/add/?type=residentialPartner',
                modalOverrides: {
                    title: 'Create Partner'
                },
                success: function (data) {
                    window.location = data.data.url;
                }
            },
            proAdd: {
                postback: '/direct/content/add/?type=pro',
                modalOverrides: {
                    title: 'Create Pro'
                },
                success: function (data) {
                    window.location = data.data.url;
                }
            },
            programAdd: {
                postback: '/direct/content/add/?type=residentialProgram',
                modalOverrides: {
                    title: 'Create Residential Program'
                },
                success: function (data) {
                    window.location = data.data.url;
                }
            },
            sharedBlockAdd: {
                postback: '/direct/content/add/?type=Text&isShared=true',
                modalOverrides: {
                    title: 'Create Text'
                },
                success: function (data) {
                    window.location = data.data.url;
                }
            },
            userAdd: {
                postback: '/direct/content/add/?type=user',
                modalOverrides: {
                    title: 'Create User'
                },
                success: function (data) {
                    window.location = data.data.url;
                }
            }
        },

        domElement: null,

        init: function (data) {
            $.extend(this, data);
            this.domElement = $('#actionbar');
//            this._setupPage();
            this._setupDropdowns();
            this._setupActions();
            this._setupEditButton();
        },

        _setupPage: function () {
            this.page = new CMS.Page(this._getPageInfo());
        },

        _setupEditButton: function () {
            var self = this;
            $('#editToggle', this.domElement).click(function () {
                $(this).toggleClass('browse').toggleClass('edit');
                var edit = $('a:first', this).text() == 'Edit';
                $('a:first', this).text(edit ? 'Browse' : 'Edit');
                self.setEditMode(edit);
                return false;
            });
        },

        _setupDropdowns: function () {
            var self = this;
            $('li.dropdown', this.domElement).hover(function () {
                $('ul:first', this).show();
                $(this).addClass('open');
            }, function () {
                $('ul:first', this).hide();
                $(this).removeClass('open');
            });

            // special case for the 'new' drop down
            $('.actionbar-new').parent().hover(function () {
                $('span', this).addClass('hover');
            }, function () {
                $('span', this).removeClass('hover');
            });
        },

        // @todo make this use actions that are passed from the backend
        _setupActions: function () {

            var self = this;

            CMS.Use(['Core/CMS.PageAction.PageAdd'], function (CMS) {
                self.actions.pageAdd.domElement = $('.addPage:first', self.domElement);
                self.actions.pageAdd.page = self._getPageInfo();
                self.actions.pageAdd = new CMS.PageAction.PageAdd(self.actions.pageAdd);
            });

            CMS.Use(['Core/CMS.PageAction.PageEdit'], function (CMS) {
                self.actions.pageEdit.domElement = $('.editPage:first', self.domElement);
                self.actions.pageEdit.page = self._getPageInfo();
                self.actions.pageEdit = new CMS.PageAction.PageEdit(self.actions.pageEdit);
            });

            if (self._getPageInfo().actions.pageDelete) {
                CMS.Use(['Core/CMS.PageAction.PageDelete'], function (CMS) {
                    self.actions.pageDelete.domElement = $('.deletePage:first', self.domElement);
                    self.actions.pageDelete.page = self._getPageInfo();
                    self.actions.pageDelete = new CMS.PageAction.PageDelete(self.actions.pageDelete);
                });
            } else {
                $('.deletePage:first', self.domElement).addClass('disabled');
            }

            CMS.Use(['Core/CMS.PageAction.Action'], function (CMS) {
                self.actions.knowhowArticleAdd.domElement = $('.knowhowArticle-add:first', self.domElement);
                self.actions.knowhowArticleAdd.page = self._getPageInfo();
                self.actions.knowhowArticleAdd = new CMS.PageAction.Action(self.actions.knowhowArticleAdd);

                self.actions.companyAdd.domElement = $('.company-add:first', self.domElement);
                self.actions.companyAdd.page = self._getPageInfo();
                self.actions.companyAdd = new CMS.PageAction.Action(self.actions.companyAdd);

                self.actions.companyLocationAdd.domElement = $('.companyLocation-add:first', self.domElement);
                self.actions.companyLocationAdd.page = self._getPageInfo();
                self.actions.companyLocationAdd = new CMS.PageAction.Action(self.actions.companyLocationAdd);

                self.actions.landingAdd.domElement = $('.landing-add:first', self.domElement);
                self.actions.landingAdd.page = self._getPageInfo();
                self.actions.landingAdd = new CMS.PageAction.Action(self.actions.landingAdd);

                self.actions.partnerAdd.domElement = $('.partner-add:first', self.domElement);
                self.actions.partnerAdd.page = self._getPageInfo();
                self.actions.partnerAdd = new CMS.PageAction.Action(self.actions.partnerAdd);

                self.actions.proAdd.domElement = $('.pro-add:first', self.domElement);
                self.actions.proAdd.page = self._getPageInfo();
                self.actions.proAdd = new CMS.PageAction.Action(self.actions.proAdd);

                self.actions.programAdd.domElement = $('.program-add:first', self.domElement);
                self.actions.programAdd.page = self._getPageInfo();
                self.actions.programAdd = new CMS.PageAction.Action(self.actions.programAdd);

                self.actions.sharedBlockAdd.domElement = $('.sharedBlock-add:first', self.domElement);
                self.actions.sharedBlockAdd.page = self._getPageInfo();
                self.actions.sharedBlockAdd = new CMS.PageAction.Action(self.actions.sharedBlockAdd);

                self.actions.userAdd.domElement = $('.user-add:first', self.domElement);
                self.actions.userAdd.page = self._getPageInfo();
                self.actions.userAdd = new CMS.PageAction.Action(self.actions.userAdd);
            });
        },

        _getPageInfo: function () {
            return PAGE_INFO.data[0];
        },

        setEditMode: function (edit) {
            if (edit) {
                if (null == this.page) {
                    this._setupPage();
                }
                $('body').addClass('editMode');
                this.page.showMenus();
            } else {
                if ($('.editCancel').length) {
                    if (!confirm('An editor is open. Continuing will lose any changes you have made without saving. Are you sure you want to continue?')) {
                        return;
                    }
                }
                $('.editCancel').click();
                $('body').removeClass('editMode');
                this.page.hideMenus();
            }
        }
    });
});