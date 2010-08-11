YUI({
    'gallery-paginator': {
        fullpath: 'http://yui.yahooapis.com/gallery-2010.02.25-22/build/gallery-paginator/gallery-paginator-min.js',
        requires: ['widget','substitute','event-key']
    }
}).add('asset-manager', function(Y) {
    AssetManager = function(config) {
        AssetManager.superclass.constructor.apply(this, arguments);
    };

    AssetManager.NAME = 'asset-manager';
   
    // set assetmanager attributes
    AssetManager.ATTRS = {
        source : {
            value : '/direct/asset/manager/index/',
            validator : Y.Lang.isString
        },
        modal : {
            value : null,
            validator : function (val) {
                return val instanceof Y.Widget || Y.Lang.isBoolean(val);
            }
        }
    };
   

    Y.extend(AssetManager, Y.Widget, {
        // prototype
        ready : false,

        initializer : function(config) {
            this.set('modal', new Y.Modal({ modal: true, zIndex: 80, opacity: 60, color: '#000'}));

            var dataSource = new Y.DataSource.IO({
                source: this.get('source')
            });

            dataSource.plug(Y.Plugin.DataSourceJSONSchema, {
                schema: {
                    metaFields: {code: 'code', templates: 'templates', html: 'html'},
                    resultListLocator: 'data',
                    resultFields: []
                }
            });

            dataSource.on('response', function (e) {
                this._getResponse(e.response);
            }, this);

            dataSource.sendRequest();
        },

        _getResponse : function (response) {
            if (0 < parseInt(response.meta.code.id)) {
                alert('Asset manager failed to load.');
                this.destroy();
            }

            node = Y.Node.create(response.meta.html);
            this.get('modal').setStdModContent(Y.WidgetStdMod.BODY, node);
            this.get('modal').render();
            this._setupTabs();
            this._setupUpload();
            this._setupLibrary();
        },

        getBodyNode : function () {
            return this.get('modal').getStdModNode(Y.WidgetStdMod.BODY)
        },
       
        _setupTabs : function () {
            Y.use('tabview', function(Y) {
                var tabview = new Y.TabView({
                    contentBox: '#assetmanager'
                });
                tabview.render();
                var tabview2 = new Y.TabView({
                    contentBox: '#from-url'
                });
                tabview2.render();
                Y.all('.asset-tab').remove();
            });
        },
       
        _setupUpload : function () {
                uploader = new Y.Uploader({
                    node: Y.one('#UploadButton'),
                    errorNode: Y.one('#UploadMessage')
                });
        },
       
        _setupLibrary : function () {
            // setup the paginator
            var paginator = new Y.Paginator({
                totalRecords: 0,
                rowsPerPage: 5
            });
           
            paginator.on('changeRequest', function (e) {
                this.getBodyNode().one('form#filter').one('input[name=page]').setAttribute('value', e.page);
                paginator.setPage(e.page, true);
                this._submitFilter();
            }, this);
           
            paginator.render('#library-pagination');
            this.set('paginator', paginator);
           
            // set up the form
            this.getBodyNode().one('form#filter').on('submit', this._submitFilter, this);
        },
       
        _submitFilter : function (e) {
            var form = this.getBodyNode().one('form#filter');
           
            if (e) {
                e.halt();
                e.currentTarget.setAttribute('disabled', 'disabled');
                form.one('input[name=page]').setAttribute('value', 1);
            }
                
            var ds = new Y.DataSource.IO({
                source: form.getAttribute('action')
            });
           
            ds.plug({
                fn: Y.Plugin.DataSourceJSONSchema, cfg: {
                    schema: {
                        metaFields: {
                            code: 'code',
                            html: 'html',
                            templates: 'templates',
                            rowCount: 'data.rowCount',
                            perPage: 'data.perPage',
                            currentPage: 'data.currentPage'
                        },
                        resultListLocator: "data.assets",
                        resultFields: [
                            'id',
                            'thumb',
                            'url_template',
                            'name',
                            'type',
                            'upload_date',
                            'sizes',
                            'actions',
                            'caption'
                        ]
                    }
                }
            });
           
            ds.on('response', this._updateLibrary, this);
            ds.sendRequest({
                cfg: {
                    method: 'POST',
                    form: {
                        id: form
                    }
                }
            });
        },
       
        _updateLibrary : function (e) {
            var list = this.getBodyNode().one('ul#library-list'),
                actions = null;
            
            // clear out the list
            list.set('innerHTML', null);
           
            // populate the list
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
              
                list.append(assetNode);
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
                  
                    assetWidget.render();
                });
            }
           
            // update the paginator
            var paginator = this.get('paginator');
            paginator.setRowsPerPage(e.response.meta.perPage, true);
            paginator.setTotalRecords(e.response.meta.rowCount, true);
            paginator.setPage(e.response.meta.currentPage, false);
        },

        renderUI : function () {
        },

        destructor : function () {
            if (this.get('modal')) {
                this.get('modal').destroy();
            }
        }
    });
   
    Y.AssetManager = AssetManager;
}, '3.0.0', {
    requires:['base', 'oop', 'datasource-io', 'datasource-jsonschema', 'node', 'tabview', 'uploader', 'gallery-paginator'],
    plugins : {
        'asset-manager-node-plugin' : {
            skinnable : true
        }
    }
});