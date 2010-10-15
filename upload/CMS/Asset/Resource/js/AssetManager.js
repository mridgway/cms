CMS.Use([], function (CMS) {

    CMS.AssetManager = Class.extend({

        loaded: false,
        url: '/direct/asset/manager/index',

        domElement: null,
        modal: null,
        uploader: null,

        init: function (data) {
            $.extend(this, data);
            self = this;
            this.domElement.click(function () {
                self.open();
                return false;
            });
        },

        load: function () {
            self = this;
            $.get(this.url, function (data) {
                if (data.code.id <= 0) {
                    CMS.Use(['Asset/CMS.AssetList', 'Core/CMS.Modal', 'Asset/CMS.Uploader'], function () {
                        self.domElement = $(data.html);
                        self.modal = new CMS.Modal(self.domElement, {
                            title: 'Asset Manager',
                            fixed: true,
                            width: 450,
                            resizable: false,
                            modal: true
                        });
                        self.domElement.tabs();
                        self.uploader = new CMS.Uploader({
                            domElement: $('#UploadButton'),
                            assetList: new CMS.AssetList({
                                domElement: $('#new-file-list')
                            })
                        });
                        self.loaded = true;
                    });
                }
            }, 'json');
        },

        _setupUploader: function (html) {
            self.uploader = new CMS.Uploader({
                domElement: $('#UploadButton'),
                assetList: new CMS.AssetList({
                    domElement: $('#new-file-list')
                })
            });
            self.loaded = true;
        },

        open: function () {
            if (!this.loaded) {
                this.load();
                return;
            }
        }

    });

});