CMS.Use([], function (CMS) {

    CMS.AssetList = Class.extend({

        domElement: null,

        paginator: null,
        paginate: false,

        assets: [],

        onInsert: $.noop,

        init: function (data) {
            $.extend(this, data);
            if (true === this.paginate) {
                var self = this;
                CMS.Use(['Core/CMS.Paginator'], function () {
                    self.paginator = new CMS.Paginator({
                        postback: '/direct/asset/manager/list',
                        responseManipulator: function (result) {
                            result.onInsert = self.onInsert;
                            return new CMS.Asset(result);
                        },
                        postLoad: function (paginator) {
                            self.assets = paginator.items;
                            self.render();
                        },
                        perPage: 4
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
                $('.asset-'+asset.id, this.domElement).effect('pulsate', {times: 2});
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
            var self = this;
            self.domElement.empty();
            if (this.assets.length > 0) {
                $.each(this.assets, function (index, asset) {
                    self.domElement.append(asset.domElement.hide());
                    asset.setupActions();
                    asset.domElement.show();
                });
                if (self.paginate) {
                    if ((self.paginator.currentPage - 1) * self.paginator.perPage > 0) {
                        $('<a>', {
                            href: '#',
                            text: 'Previous',
                            click: function (e) {
                                self.paginator.setPage(--self.paginator.currentPage);
                                var data = {};
                                $.each($('form#filter').serializeArray(), function (index, value) {
                                    data[value.name] = value.value;
                                });
                                self.paginator.loadCurrentPage(data);
                                return false;
                            }
                        }).appendTo(self.domElement);
                    }

                    if ((self.paginator.currentPage) * self.paginator.perPage < self.paginator.itemCount) {
                        $('<a>', {
                            href: '#',
                            text: 'Next',
                            click: function (e) {
                                self.paginator.setPage(++self.paginator.currentPage);
                                var data = {};
                                $.each($('form#filter').serializeArray(), function (index, value) {
                                    data[value.name] = value.value;
                                });
                                self.paginator.loadCurrentPage(data);
                                return false;
                            }
                        }).appendTo(self.domElement);
                    }
                }
            } else {
                $('<p>', {
                    text: 'No assets found matching your criteria.'
                }).appendTo(this.domElement);
            }
        },

        setInsertFunction: function (func) {
            this.onInsert = func;
        }

    });

});