YUI().add('block-edit', function (Y) {
    function BlockEdit(config) {
        BlockEdit.superclass.constructor.apply(this, arguments);
    }
    
    var Node = Y.Node
        Plugin = Y.Plugin;

    BlockEdit.NAME = 'blockEdit';
    BlockEdit.NS   = 'edit';
    
    Y.mix(BlockEdit, {
        BUTTON_TEMPLATE: '<li class="{classes}"></li>'
    });

    // plugin attributes
    BlockEdit.ATTRS = {
        isEditing: {
            value: false,
            validator: Y.Lang.isBoolean
        },
        
        editButton: {
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
    Y.extend(BlockEdit, Plugin.Base, {
        _dataSource : null,
        
        initializer : function (config) {
            if(this.get('host').get('rendered')) {
                this.renderUI();
                this.bindUI();
            }
            
            this._setupDataSource();
        },
        
        renderUI : function () {
            var header = this.get('host').getStdModNode(Y.WidgetStdMod.HEADER);

            var editButton = Node.create(
                Y.substitute(BlockEdit.BUTTON_TEMPLATE, {
                    classes: this.get('host').getClassName('edit')
                })
            );
            
            this.set('editButton', editButton);
            editButton.set('weight', this.get('weight'));
            header.one('.' + this.get('host').getClassName('actionbar')).append(
                editButton
            );
        },
        
        bindUI : function () {
            // begin editing
            this.get('editButton').on('click', this._startEdit, this);
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
            
            this._dataSource.on('response', this._displayForm, this);
        },
        
        _startEdit : function () {
            this.set('isEditing', true);
            this.get('host').hideHeader();
            this._fetchForm();
        },
        
        _fetchForm : function () {            
            // actually load the information
           this._dataSource.sendRequest();
        },
        
        _displayForm : function (e) {
            var response = e.response,
                block = this.get('host').get('block'),
                dirtyBlock = Y.Node.create('<div class="dirty-block"></div>');

            this.set('dirtyBlock', dirtyBlock);
            
            dirtyBlock.setAttribute('class', block.getAttribute('class'));
            block.setStyle('display', 'none');
            
            block.get('parentNode').append(dirtyBlock);

            dirtyBlock.append(response.meta.html);
            
            // creating cancel button
            var submit = dirtyBlock.one('input[type=submit]'),
                cancel = Y.Node.create('<a href="#" id="cancel" class="cancel">cancel</a>');
                
            // adding the cancel button
            submit.get('parentNode').insertBefore(cancel, submit);
            
            // bind form actions
            cancel.on('click', this._cancelEdit, this);
            dirtyBlock.one('form').on('submit', this._submitEdit, this);
        },

        _cancelEdit : function (e) {
            e.halt();
            this._endEdit();
            return false;
        },
        
        _submitEdit : function (e) {
            e.halt();
            e.currentTarget.setAttribute('disabled', 'disabled');
            
            var form = this.get('dirtyBlock').one('form');
            
            var ds = new Y.DataSource.IO({
                source: form.getAttribute('action')
            });
            
            ds.plug({
                fn: Y.Plugin.DataSourceJSONSchema, cfg: {
                    schema: {
                       metaFields: {code: 'code', html: 'html', templates: 'templates'},
                       resultListLocator: "data",
                       resultFields: []
                    }
                }
            });
            
            ds.on('response', this._complete, this);
            ds.sendRequest({
                cfg: {
                    method: 'POST',
                    form: {
                        id: form
                    }
                }
            });
        },
        
        _complete : function (e) {
            /*
                TODO implement error handling
            */
            var response = e.response;
            
            this.get('host').get('block').set('innerHTML', response.meta.html);
            this._endEdit();
        },
        
        _endEdit : function () {
            this.set('isEditing', false);
            this.get('host').showHeader();
            
            this.get('dirtyBlock').remove();
            this.set('dirtyBlock', null);
            
            this.get('host').get('block').setStyle('display', 'block');
        }
    }); 
    
    Y.namespace('Plugin').BlockEdit = BlockEdit;
}, '3.0.0', {skinnable: true, requires: ['datasource-io', 'io-form', 'datasource-jsonschema', 'plugin', 'block']});