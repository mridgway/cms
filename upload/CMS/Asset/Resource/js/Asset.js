CMS.Use([], function (CMS) {

    CMS.Asset = Class.extend({

        domElement: null,

        actions: [],
        id: null,
        name: null,
        sizes: [],
        thumb: null,
        type: null,
        upload_date: null,

        url_template: null,
        templates: {},

        init: function (data) {
            $.extend(this, data);
            this.domElement = $.tmpl(this.templates.asset, this);
        },

        setupActions: function () {
            $('#actions-' + this.id, this.domElement).tabs({
                collapsible: true,
                selected: -1
            });
        }

    });

});