YUI().add('modal-io', function (Y) {
    function ModalIO(config) {
        ModalIO.superclass.constructor.apply(this, arguments);
    }
    
    var Node = Y.Node
        Plugin = Y.Plugin;

    ModalIO.NAME = 'modalIO';
    ModalIO.NS   = 'io';
    
    Y.mix(ModalIO, {
        
    });
    
    ModalIO.UI_EVENTS = {
        
    }

    // plugin attributes
    ModalIO.ATTRS = {
        source: {
            value: null
        },
        
        loadingMessage : {
            value: Y.Node.create('<p>Loading...</p>'),
            validator: function (val) {
                if (val instanceof Y.Node) {
                    return true;
                }
                
                return Y.Lang.isString(val);
            }
        },
        
        callback: {
            value: function (modal, response, event) {
                this.get('host').setStdModContent(Y.WidgetStdMod.BODY, response.meta.html);
            },
            validator: Y.Lang.isFunction
        }
    };

    // prototype methods
    Y.extend(ModalIO, Plugin.Base, {
        _dataSource : null,
        
        initializer : function (config) {
            if (!this.get('source')) {
                throw 'Y.Plugin.ModalIO source attribute must be set';
            }
            
            this._bindUI();
            
            this._setDataSource();
        },
        
        showLoading : function () {
            this.get('host').setStdModContent(Y.WidgetStdMod.BODY, this.get('loadingMessage'));
        },
        
        hideLoading : function () {
            this.get('loadingMessage').remove();
        },
        
        _setDataSource : function () {
            if (!Y.Lang.isNull(this._dataSource)) {
                return;
            }

            this._dataSource = new Y.DataSource.IO({
                source:this.get('source')
            });

            this._dataSource.plug(Y.Plugin.DataSourceJSONSchema, {
                schema: {
                    metaFields: {code: 'code', templates: 'templates', html: 'html'},
                    resultListLocator: "data",
                    resultFields: []
                }
            });

            if (this.get('callback')) {
                this._dataSource.on('response', function (e) {
                    this.get('callback').apply(this, [this.get('host'), e.response, e]);
                }, this);
            }
        },
        
        _fetch : function () {            
            // actually load the information
           this._dataSource.sendRequest();
        },
        
        _renderUI : function () {
            this.showLoading();
            this._fetch();
        },
        
        _bindUI : function () {
            this.get('host').on('load', this._renderUI, this);
            
            this.get('host').on('hideLoading', function (e) {
                this.hideLoading();
            }, this);
            
            this.get('host').on('showLoading', function (e) {
               this.showLoading();
            }, this);
        }
    }); 
    
    Y.namespace('Plugin').ModalIO = ModalIO;
}, '3.0.0', {
    skinnable: true,
    requires: ['datasource-io', 'datasource-jsonschema', 'plugin', 'modal']
});