CMS.Use([], function (CMS) {

    CMS.AssetList = Class.extend({

        domElement: null,

        assets: [],
        currentPage: 1,
        perPage: 0,
        rowCount: 0,

        templates: {
            Delete: '',
            Edit: '',
            Insert: '',
            asset: ''
        },

        init: function (data) {
            $.extend(this, data);
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
            this.assets.push(asset);
            this.domElement.append(asset.domElement);
        }

    });

});