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

        assetListRowTemplate: '<li><a class="asset-action asset-action-${action}" href="#${action}">${action}</a></li>',
        url_template: null,
        templates: {},

        init: function (data) {
            $.extend(this, data);
            console.log(data);
            this.domElement = $(this.templates.asset).tmpl(this);
            var assetActions = this.domElement.find('.actions');
            console.log(assetActions);
            for (var i in this.actions) {
                assetActions.append($(this.assetListRowTemplate).tmpl({action: this.actions[i]}));
            }
        }

    });

});