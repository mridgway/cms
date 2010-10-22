CMS.Use([], function (CMS) {

    CMS.Asset = Class.extend({

        domElement: null,
        actionTabs: null,

        actions: [],
        id: null,
        name: null,
        sizes: [],
        thumb: null,
        type: null,
        upload_date: null,

        url_template: null,
        templates: {},
        idPrefix: '',

        onDelete: $.noop,
        onInsert: $.noop,

        init: function (data) {
            $.extend(this, data);
            this.domElement = $.tmpl(this.templates.asset, this);
        },

        getUrl: function (size) {
            if (null === size) {
                size = 'original';
            }
            return $.tmpl(this.url_template, {
                'size': size
            }).text();
        },

        setupActions: function () {
            var self = this;
            this.actionTabs = $('.actions-' + this.id, this.domElement).tabs({
                collapsible: true,
                selected: -1
            });

            // asset edit actions
            (function () {
                $('.asset-insert form', self.domElement).submit(function (e) {
                    e.preventDefault();
                    var data = $(this).serializeArray();
                    var size = null;
                    $.each(data, function (index, value) {
                        if (value.name === 'size') {
                            size = value.value;
                            return false;
                        }
                    });
                    self.onInsert(self, size);
                    return false;
                });

                $('.asset-edit form', self.domElement).submit(function(e) {
                    e.preventDefault();
                    var postback = this.getAttribute('action');
                    $.post(postback, $(this).serialize(), function (data) {
                        if (data.code.id <= 0) {
                            self.name = data.data.assets[0].name;
                            self.caption = data.data.assets[0].caption;
                            $('<p>', {
                                class: 'notification positive',
                                text: 'Asset saved successfully.'
                            }).hide().prependTo(self.domElement.find('.asset-edit')).show(1000).delay(3000).hide(1000, function () {
                                $(this).remove();
                            });
                        } else {
                            $('<p>', {
                                class: 'notification negative',
                                text: 'Asset could not be saved.'
                            }).hide().prependTo(self.domElement.find('.asset-edit')).show(1000).delay(3000).hide(1000, function () {
                                $(this).remove();
                            });
                        }
                    }, 'json');
                    return false;
                });
                
                $('.asset-delete .submit', self.domElement).click(function() {
                    $.get($(this).parents('form:first').attr('action'), function (data) {
                        if (data.code.id <= 0) {
                            self.domElement.hide(500, function () {
                                self.domElement.remove();
                                self.onDelete();
                            });
                        }
                    }, 'json');
                    return false;
                });

                $('.asset-delete .cancel', self.domElement).click(function() {
                    self.actionTabs.tabs('select', -1);
                    return false;
                });
            })();

            // from url action
            (function () {
                $('.from-url form', self.domElement).submit(function () {
                    // insert into ckeditor here
                    return false;
                });
            })();
        },

        setInsertFunction: function (func) {
            this.onInsert = func;
        }

    });

});