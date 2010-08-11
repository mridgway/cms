YUI().add('asset', function (Y) {
    var Asset = function(config) {
        Asset.superclass.constructor.apply(this, arguments);
    };
    
    Asset.NAME = 'asset';
    
    Asset.ATTRS = {
        actions : {
            value: null
        },
        
        templates : {
            value: null
        },
        
        currentAction : {
            value : false//,
            // validator : function (val) {                
            //     var actions = this.get('actions');
            //     for(x in actions) {
            //         if (actions[x] == val) {
            //             return true;
            //         }
            //     }
            //     
            //     console.log("before check");
            //     console.log(val);
            //     // if (val === false) {
            //     //     return true;
            //     // }
            //     console.log("after check");
            //     
            //     return false;
            // }
        },
        
        actionBox : {
            value : null,
            validator : function (val) {
                return val instanceof Y.Node
            }
        },
        
        asset : {
            value : null,
            validator : Y.Lang.isObject
        },
        
        caption : {
            value: null,
            validator : Y.Lang.isString
        },
        
        name : {
            value: null,
            validator : Y.Lang.isString
        },
        
        sizes : {
            value : null
        },
        
        url_template : {
            value : null,
            validator : Y.Lang.isString
        }
    }
    
    Y.extend(Asset, Y.Widget, {
        initializer : function (config) {
            this._setupActions();
            this.set('actionBox', this.get('boundingBox').one('.action-box'));
            this.after('currentActionChange', this._actionChange);
        },
        
        _setupActions : function () {
            this.get('boundingBox').all('.asset-action').each(function (currentNode, index, nodeList) {
                currentNode.on('click', this._toggleAction, this, currentNode.getAttribute('id'));
            }, this);
        },
        
        _toggleAction : function (e, action) {
            e.halt();
            
            if (this.get('currentAction') && this.get('currentAction') != action) {
                this.get('actionBox').set('innerHTML', '');
            }
            
            this.set('currentAction', action);
        },

        /* @todo change functionality for non-image inserts */
        _actionChange : function (e) {
            if (!this.get('currentAction'))
                return;
            // console.log(this.get('currentAction'));

            this.get('actionBox').set('innerHTML', Y.substitute(this.get('templates')[this.get('currentAction')], {
                name: this.get('name'),
                caption: this.get('caption'),
                id: this.get('id')
            }, function (key, val, meta) {
                if (val == null) {
                    return '';
                }
                return val;
            }));
            
            switch(this.get('currentAction')) {
                case 'Insert':
                    this._bindUIForInsert();
                    break;
                case 'Edit':
                    this._bindUIForEdit();
                    break;
                case 'Delete':
                    this._bindUIForDelete();
                    break;
            }
        },
        
        _bindUIForInsert : function () {
            var select = this.get('boundingBox').one('select.size'),
                sizes = this.get('sizes');

            for(x in sizes) {
                select.append(
                    Y.Node.create(
                        Y.substitute('<option value="{sysname}">{title}</option>', sizes[x])
                    )
                );
            }
        },

        _bindUIForEdit : function () {
            var form = this.get('actionBox').one('form');
            var submit = this.get('actionBox').one('form input#submit'),
                cancel = Y.Node.create('<a href="/js" id="cancel" class="cancel">cancel</a>');
            submit.get('parentNode').insertBefore(cancel, submit);
            cancel.on('click', this._cancel, this);
            form.on('submit', this._submitEditForm, this);
        },

        _bindUIForDelete : function () {
            var form = this.get('actionBox').one('form');
            var submit = this.get('actionBox').one('form input.submit'),
                cancel = this.get('actionBox').one('form input.cancel');
            cancel.on('click', this._cancel, this);
            submit.on('click', this._submitDeleteForm, this);
        },

        _cancel : function (e) {
            e.halt();
            this._closeAction();
            return false;
        },

        _submitEditForm : function (e) {
            e.halt();
            e.currentTarget.setAttribute('disabled', 'disabled');

            var form = this.get('actionBox').one('form');

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

        _submitDeleteForm : function (e) {
            e.halt();
            e.currentTarget.setAttribute('disabled', 'disabled');

            var form = this.get('actionBox').one('form');

            var ds = new Y.DataSource.IO({
                source: form.getAttribute('action')
            });

            ds.plug({
                fn: Y.Plugin.DataSourceJSONSchema, cfg: {
                    schema: {
                        metaFields: {
                            code: 'code',
                            html: 'html',
                            templates: 'templates'
                        },
                        resultListLocator: "data",
                        resultFields: []
                    }
                }
            });

            ds.on('response', function(e) {
                if (e.response.meta.code.id == 0) {
                    this.destroy();
                }
            }, this);
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
                @TODO implement error handling
            */
            var response = e.response;
            this.set('name', response.results[0].name);
            this.set('caption', response.results[0].caption);

            this.get('boundingBox').one('.name').set('innerHTML', this.get('name'));

            this._closeAction();
        },

        _closeAction : function() {
            this.get('actionBox').set('innerHTML', '');
            this.set('currentAction', false);
        },
        
        destructor : function () {
            
        }
    });
    
    Y.Asset = Asset;
}, '3.1.0', {
    requires : ['widget', 'asset-manager']
});