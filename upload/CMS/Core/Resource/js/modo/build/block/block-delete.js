YUI().add('block-delete', function (Y) {
    function BlockDelete(config) {
        BlockDelete.superclass.constructor.apply(this, arguments);
    }
    
    var Node = Y.Node
        Plugin = Y.Plugin;

    BlockDelete.NAME = 'blockDelete';
    BlockDelete.NS   = 'delete';
    
    Y.mix(BlockDelete, {
        BUTTON_TEMPLATE: '<li class="{classes}"></li>'
    });

    // plugin attributes
    BlockDelete.ATTRS = {    
        deleteButton: {
            value: null,
            validator: function(val) {
                return (val instanceof Y.Node);
            }
        },
        
        action : {
            value: false,
            validator : Y.Lang.isObject
        }
    };

    // prototype methods
    Y.extend(BlockDelete, Plugin.Base, {
        _dataSource : null,
        
        initializer : function (config) {
            if(this.get('host').get('rendered')) {
                this.renderUI();
                this.bindUI();
            }
            
            this._setupDataSource();
            
            this.publish('delete');
        },
        
        renderUI : function () {
            var header = this.get('host').getStdModNode(Y.WidgetStdMod.HEADER);

            var deleteButton = Node.create(
                Y.substitute(BlockDelete.BUTTON_TEMPLATE, {
                    classes: this.get('host').getClassName('delete')
                })
            );
            
            this.set('deleteButton', deleteButton);
            
            header.one('.' + this.get('host').getClassName('actionbar')).append(
                deleteButton
            );
        },
        
        bindUI : function () {
            // begin deleteing
            this.get('deleteButton').on('click', this._requestDelete, this);
        },
        
        _setupDataSource : function () {
            if (!Y.Lang.isNull(this._dataSource)) {
                return;
            };

            this._dataSource = new Y.DataSource.IO({
                source:this.get('action').postback
            });

            this._dataSource.plug(Y.Plugin.DataSourceJSONSchema, {
                schema: {
                    metaFields: {code: 'code', html: 'html', templates: 'templates'},
                    resultListLocator: "data",
                    resultFields: []
                }
            });
            
            this._dataSource.on('response', this._complete, this);
        },
        
        _requestDelete : function () {
            this._dataSource.sendRequest();
            //Y.bind(this._complete, this)();
        },
        
        _complete : function (e) {
            /*
                TODO implement error handling
            */
            // var response = e.response;
            Y.one('#block-' + this.get('host').get('id') + '-wrapper').remove();
        }
    }); 
    
    Y.namespace('Plugin').BlockDelete = BlockDelete;
}, '3.0.0', {skinnable: true, requires: ['datasource-io', 'datasource-jsonschema', 'plugin', 'block']});