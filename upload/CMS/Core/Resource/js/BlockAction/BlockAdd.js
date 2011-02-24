CMS.Use(['Core/CMS.BlockAction.Action'], function (CMS) {
    CMS.BlockAction.BlockAdd = CMS.BlockAction.Action.extend({

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
            this.editors = [];
            this._super(data);
            this._initDomElement();
        },

        _initDomElement: function () {
            var addBlockMenu = $('<dl>', {'class': 'addBlockMenu'});
            addBlockMenu.append($('<dt>', {
                html: $('<a>', {
                    text: this.caption,
                    href: '#',
                    click: function () {
                        $(this).parents('dl').find('dd').toggle(500);
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
                    html.find('.wysiwyg').ckeditor(function() {
                            self.editors.push(this);
                        }, CMS.ckeditor.getConfig());
                    html.show(500);
                }
            }, 'json');
        },

        clickShared: function (sendData) {
            var self = this;
            $.get(this.postback, sendData, function (data) {
                if (data.code.id <= 0) {
                    var html = $(data.html);
                    html.find('a').click(function () {
                        $.get($(this).attr('href'), function (data) {
                            if (data.code.id <= 0) {
                                window.location.reload(true);
                            }
                        }, 'json');
                        return false;
                    });
                    self.modal = new CMS.Modal(html, {
                        title: 'Add Shared Block'
                    });
                }
            }, 'json')
        },

        clickDynamic: function (sendData) {
            var self = this;
            $.get(this.postback, sendData, function (data) {
                if (data.code.id <= 0) {
                    var html = $(data.html);
                    html.find('a').click(function () {
                        $.get($(this).attr('href'), function (data) {
                            if (data.code.id <= 0) {
                                window.location.reload(true);
                            }
                        }, 'json');
                        return false;
                    });
                    self.modal = new CMS.Modal(html, {
                        title: 'Add Dynamic Block'
                    });
                }
            }, 'json')
        },

        alterForm: function (form, data) {
            var self = this;
            // hook submit
            form.submit(function (e) {
                var formData = $(this).serializeArray();
                data.title = (function (formData) {
                    for (var i in formData)
                        if (formData[i].name=='title')
                            return formData[i].value;
                    return '';
                })(formData);
                data.content = (function (formData) {
                    for (var i in formData)
                        if (formData[i].name=='content')
                            return formData[i].value;
                    return '';
                })(formData);
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
                    $('#block-new-wrapper').hide(500, function (){
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
                    html.find('.wysiwyg').ckeditor(function() {
                        self.editors.push(this);
                    }, CMS.ckeditor.getConfig());
                }
            }, 'json');
        },

        cancelForm: function () {
            var self = this;
            $('#block-new-wrapper').hide(500, function (){
                self.destroyEditors();
                $('#block-new-wrapper').remove();
                self.showMenus();
            });
        },

        destroyEditors: function () {
            for (var i in this.editors) {
                this.editors[i].destroy();
            }
            this.editors = [];
        }
    });
});