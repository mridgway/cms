CMS.Use(['Asset/CMS.AssetList'], function (CMS) {

    CMS.AssetLibrary = Class.extend({

        domElement: null,
        form: null,

        assetList: null,
        assetListElement: null,

        onInsert: $.noop,

        init: function (data) {
            $.extend(this, data);
            if (null != this.assetListElement) {
                this.assetList = new CMS.AssetList({
                    domElement: this.assetListElement,
                    paginate: true,
                    onInsert: this.onInsert
                });
            }
            this._setupForm();
        },

        _setupForm: function () {
            var self = this;
            this.form = $('form', this.domElement);
            this.form.submit(function () {
                self.assetList.paginator.setPage(1);
                self.load();
                return false;
            });
        },

        load: function () {
            var data = {};
            $.each($(this.form).serializeArray(), function (index, value) {
                data[value.name] = value.value;
            });
            this.assetList.paginator.loadCurrentPage(data);
        },

        setInsertFunction: function (func) {
            this.onInsert = func;
            this.assetList.setInsertFunction(func);
            $.each(this.assetList.assets, function (index, value){
                value.setInsertFunction(func);
            });
        }

    });
});