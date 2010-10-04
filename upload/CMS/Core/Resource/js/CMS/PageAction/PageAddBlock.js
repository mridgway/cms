CMS.Use(['Core/CMS.PageAction.Action'], function (CMS) {
    CMS.PageAction.PageAddBlock = CMS.PageAction.Action.extend({

        page: null, // page id
        location: null, // location id

        caption: 'Add Block',
        name: 'block-add',
        blockTypes: {
            standard: {
                title: 'Standard Text',
                sysname: 'standard'
            },
            shared: {
                title: 'Shared Text',
                sysname: 'shared'
            },
            dynamic: {
                title: 'Dynamic',
                sysname: 'dynamic'
            }
        },
        postback: '/direct/page/add-block',
        editors: [],

        init: function (data) {
            this._super(data);
            this._initDomElement();
        },

        _initDomElement: function () {
            var addBlockMenu = $('<dl>', {class: 'addBlockMenu'});
            addBlockMenu.append($('<dt>', {
                html: $('<a>', {
                    text: this.caption,
                    href: '#',
                    click: function () {
                        $(this).parents('dl').find('dd').toggle('fast');
                        return false;
                    }
                })
            }));
            var self = this;
            $.each(this.blockTypes, function(index, value) {
                var type = $('<dd>');
                type.append($('<a>', {
                    text: value.title,
                    href: '#',
                    click: function () {
                        self.processClick(index);
                        return false;
                    }
                }));
                addBlockMenu.append(type);
            });
            addBlockMenu.find('dd').hide();
            this.domElement = addBlockMenu;
        },

        processClick: function (type) {
            var data = {
                id: this.page,
                location: this.location,
                type: type
            }
            switch(type) {
                case 'standard' :
                    this.clickStandard(data);
                    break;
                case 'shared' :
                    this.clickShared(data);
                    break;
                case 'dynamic' :
                    this.clickDynamic(data);
                    break;
            }
        },

        clickStandard: function (sendData) {
            var self = this;
            this.hideMenus();
            $.get(this.postback, sendData, function (data) {
                if (data.code.id <= 0) {
                    var html = $(data.html);
                    var form = html.is('form') ? html : html.find('form:first');
                    self.alterForm(form, sendData);

                    html.hide().insertBefore(self.domElement);
                    html.find('.ckeditor').ckeditor(function() {
                            self.editors.push(this);
                        }, CMS.ckeditor.getConfig());
                    html.show('fast');
                }
            }, 'json');
        },

        clickShared: function (sendData) {
            var self = this;
            $.get(this.postback, sendData, function (data) {
                if (data.code.id <= 0) {
                    try {
                        self.modal.show();
                    } catch (e) {}
                    var html = $(data.html);
                    html.find('a').click(function () {
                        $.get($(this).attr('href'), function (data) {
                            if (data.code.id <= 0) {
                                window.location.reload(true);
                            }
                        }, 'json');
                        return false;
                    });
                    self.modal.setContent(html);
                }
            }, 'json')
        },

        clickDynamic: function (sendData) {
            var self = this;
            $.get(this.postback, sendData, function (data) {
                if (data.code.id <= 0) {
                    try {
                        self.modal.show();
                    } catch (e) {}
                    var html = $(data.html);
                    html.find('a').click(function () {
                        $.get($(this).attr('href'), function (data) {
                            if (data.code.id <= 0) {
                                window.location.reload(true);
                            }
                        }, 'json');
                        return false;
                    });
                    self.modal.setContent(html);
                }
            }, 'json')
        },

        alterForm: function (form, data) {
            var self = this;
            // hook submit
            form.submit(function (e) {
                data.title = $('#title', this).val();
                data.content = $('#content', this).val();
                self.submitForm(data);
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
                    $('#block-new-wrapper').hide('fast', function (){
                        $('#block-new-wrapper').remove();
                        self.showMenus();
                    });
                    window.location.reload(true);
                } else {
                    var html = $(data.html);
                    var form = html.is('form') ? html : html.find('form:first');
                    var sendData = {
                        id: self.page,
                        location: self.location,
                        type: 'standard'
                    }
                    self.alterForm(form, sendData);

                    self.destroyEditors();
                    $('#block-new-wrapper').replaceWith(html);
                    html.find('.ckeditor').ckeditor(function() {
                        self.editors.push(this);
                    }, CMS.ckeditor.getConfig());
                }
            }, 'json');
        },

        cancelForm: function () {
            var self = this;
            $('#block-new-wrapper').hide('fast', function (){
                self.destroyEditors();
                $('#block-new-wrapper').remove();
                self.showMenus();
            });
        },

        hideMenus: function () {
            $('.addBlockMenu').hide('fast');
        },

        showMenus: function () {
            $('.addBlockMenu').show('fast');
        },

        destroyEditors: function () {
            for (var i in this.editors) {
                this.editors[i].destroy();
            }
            this.editors = [];
        }
    });
});