CMS.Use([], function (CMS) {

    CMS.AssetList = Class.extend({

        domElement: null,

        paginator: null,
        paginate: false,

        assets: [],

        templates: {
            Delete: '',
            Edit: '',
            Insert: '',
            asset: ''
        },

        init: function (data) {
            $.extend(this, data);
            if (true === this.paginate) {
                var self = this;
                CMS.Use(['Core/CMS.Paginator'], function () {
                    self.paginator = new CMS.Paginator({
                        postback: '/direct/asset/manager/list',
                        manipulator: function (result) {
                            result.data.assets[0].templates = data.templates;
                            return new CMS.Asset(data.data.assets[0]);
                        }
                    });
                });
            }
            if ('undefined' !== typeof data.assets) {
                this.addAssets(data.assets);
            }
        },

        addAssets: function (assets) {
            for (var i in assets) {
                this.addAsset();
            }
        },

        addAsset: function (asset) {
            var self = this;
            if (this.inList(asset)) {
                $('#asset-'+asset.id, this.domElement).effect('pulsate', {times: 2});
                return;
            }
            asset.onDelete = function () {
                self.removeAsset(this);
            }
            this.assets.push(asset);
            if (this.assets.length < 4) {
                this.domElement.prepend(asset.domElement.hide());
                asset.setupActions();
                asset.domElement.show(500);
            }
        },

        removeAsset: function (asset) {
            for (var i in this.assets) {
                if (asset.id == this.assets[i].id) {
                    this.assets.splice(i, 1);
                }
            }
        },

        inList: function (asset) {
            var found = false;
            $.each(this.assets, function (index, value){
                if (value.id == asset.id) {
                    found = true;
                    return;
                }
            });
            return found;
        },

        render: function () {

        }

    });

});