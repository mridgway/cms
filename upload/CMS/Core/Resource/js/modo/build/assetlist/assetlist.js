YUI().add('assetlist', function (Y) {
    var AssetList = function(config) {
        AssetList.superclass.constructor.apply(this, arguments);
    };

    AssetList.NAME = 'assetlist';

    AssetList.ATTRS = {
        node : {
           value : null,
           validator : function (val) {
               if (Y.Lang.isString(val) && Y.one(val)) {
                   return true;
               }

               return val instanceof Y.Node;
           },
           setter : function (val) {
               if (Y.Lang.isString(val)) {
                   return Y.one(val);
               }

               return val;
           }
        },

        assets : {
            value : []
        }
    }

    AssetList.HTML_PARSER = {

    };

    Y.extend(AssetList, Y.Widget, {
        initializer: function(config) {
        },

        addAsset: function (asset) {
            this.get('assets').add(asset);
        },

        receiveResponse: function (e) {
            for(x in e.response.results) {
                var asset = e.response.results[x];

                // create asset node
                var assetNode = Y.Node.create(
                    Y.substitute(e.response.meta.templates.asset, asset, function (key, value, meta) {
                        if (key == 'actions') {
                            var actionlist = '';
                            for(x in value) {
                                actionlist += Y.substitute('<li><a id="{action}" class="asset-action" href="#{action}">{action}</a></li>', {
                                    action: value[x]
                                });
                            }
                            return actionlist;
                        }

                        return value;
                    })
                );

                this.get('node').append(assetNode);
                Y.use('asset', function (Y) {
                    var assetWidget = new Y.Asset({
                        boundingBox: assetNode,
                        templates: e.response.meta.templates,
                        id: asset.id,
                        caption: asset.caption,
                        name: asset.name,
                        sizes: asset.sizes,
                        actions: asset.actions,
                        url_template: asset.url_template
                    });

                    //assetWidget.render();
                    //this.get('assets').add(assetWidget);
                });
            }
        },

        destructor: function () {
            for(i=0; i<this.get('assets').length; ++i) {
                this.get('assets')[i].destroy();
            }
        }
    });

    Y.AssetList = AssetList;
}, '3.1.0', {
    requires : ['widget', 'asset', 'datasource', 'datasource-jsonschema']
});