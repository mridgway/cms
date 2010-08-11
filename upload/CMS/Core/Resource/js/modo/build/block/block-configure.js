YUI().add('block-configure', function (Y) {
    function BlockConfigure(config) {
        BlockConfigure.superclass.constructor.apply(this, arguments);
    }

    var Node = Y.Node
        Plugin = Y.Plugin;

    BlockConfigure.NAME = 'blockConfigure';
    BlockConfigure.NS   = 'configure';

    Y.mix(BlockConfigure, {
        BUTTON_TEMPLATE: '<li class="{classes}"></li>'
    });

    // plugin attributes
    BlockConfigure.ATTRS = {

    };

    // prototype methods
    Y.extend(BlockConfigure, Y.Plugin.Base, {
        /**
         * Initialize
         *
         * @method initializer
         * @protected
         */
        initializer: function(config) {
            if(this.get('host').get('rendered')) {
                this.renderUI();
                this.bindUI();
            }
        },

        bindUI : function () {

        },

        renderUI : function () {
            var header = this.get('host').getStdModNode(Y.WidgetStdMod.HEADER);

            var configureButton = Node.create(
                Y.substitute(BlockConfigure.BUTTON_TEMPLATE, {
                    classes: this.get('host').getClassName('configure')
                })
            );

            header.one('.' + this.get('host').getClassName('actionbar')).append(
                configureButton
            );
        }
    });

    Y.namespace('Plugin').BlockConfigure = BlockConfigure;
}, '3.0.0', {skinnable: true, requires: ['plugin', 'node', 'block']});