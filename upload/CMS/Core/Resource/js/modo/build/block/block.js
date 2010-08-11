YUI().add('block', function (Y) {    
    function Block(config) {
        Block.superclass.constructor.apply(this, arguments);
    }
    
    Block.NAME = 'block';
    Block.ATTRS = {        
        block : {
            value: false,
            validator : function (val) {
                return (val instanceof Y.Node);
            }
        },
        
        id : {
            value: null,
            validator : Y.Lang.isNumber
        },

        page : {
            value : null
        }
    };
    
    Y.extend(Block, Y.Widget, {
        _collapsed : false,
        
        initializer: function () {
            this.set('block', this.get('contentBox').one('.block'));
        },
        destructor: function () {
            
        },
        renderUI: function () {
            this.setStdModContent(Y.WidgetStdMod.BODY, this.get('block'));
            this._renderActionBar();
        },
        bindUI: function () {

        },
        syncUI: function () {
            this._updateHeader();
        },
        _renderActionBar: function () {
            var actionBar = Y.Node.create(
                Y.substitute(Block.ACTIONBAR_TEMPLATE, {
                    classes: this.getClassName('actionbar')
                })
            );
            
            this.setStdModContent(Y.WidgetStdMod.HEADER, actionBar);
        },
        
        hideHeader : function () {
            this._collapsed = true;
            this.syncUI();
        },
        
        showHeader : function () {
            this._collapsed = false;
            this.syncUI();
        },
        
        _updateHeader : function () {
            if (this._collapsed) {
                this.getStdModNode(Y.WidgetStdMod.HEADER).setStyle('display', 'none');
            } else {
                this.getStdModNode(Y.WidgetStdMod.HEADER).setStyle('display', 'block');
            }
        },
        
        _removeBlock : function () {
            
        }
    });
    
    Y.mix(Block, {
        ACTIONBAR_TEMPLATE: '<ul class="{classes}"></ul>'
    });
    
    Y.Block = Y.Base.build('block', Block, [Y.WidgetStdMod]);
}, '3.1.0', {
    skinnable : true,
    requires : ['node', 'base', 'widget', 'widget-stdmod', 'blockcss', 'substitute'], 
    plugins : {
    	'block-edit' : {
    		skinnable : true
    	},
    	'block-delete' : {
    		skinnable : true
    	},
    	'block-move' : {
    		skinnable : true
    	},
    	'block-configure': {
    		skinnable : true
    	}
    }
});
