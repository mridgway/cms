YUI().add('block-move', function (Y) {
    function BlockMove(config) {
        BlockMove.superclass.constructor.apply(this, arguments);
    }

    var Node = Y.Node
        Plugin = Y.Plugin;

    BlockMove.NAME = 'blockMove';
    BlockMove.NS   = 'move';

    Y.mix(BlockMove, {
        BUTTON_TEMPLATE: '<li class="{classes}"></li>'
    });

    // plugin attributes
    BlockMove.ATTRS = {

    };

    // prototype methods
    Y.extend(BlockMove, Y.Plugin.Base, {
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

            var moveButton = Node.create(
                Y.substitute(BlockMove.BUTTON_TEMPLATE, {
                    classes: this.get('host').getClassName('move')
                })
            );

            header.one('.' + this.get('host').getClassName('actionbar')).append(
                moveButton
            );
        }
    });

    Y.namespace('Plugin').BlockMove = BlockMove;
}, '3.0.0', {skinnable: true, requires: ['plugin', 'node', 'block']});