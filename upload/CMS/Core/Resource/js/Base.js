CMS.Use([], function (CMS) {
    CMS.Base = Class.extend({
        //_data: new Array(),

        init: function (options) {
            this.merge(options);
        },

        merge: function (object) {
            $.extend(this, object);
        }

    /*
        get: function (name, value) {
            if (typeof this._data[name] == 'undefined')
                throw('Property \'' + name + '\' does not exist.');
            return this._data[name].getter(value);
        },

        set: function (name, value) {
            if (typeof this._data[name] == 'undefined')
                throw('Property \'' + name + '\' does not exist.');
            return this._data[name].setter(value);
        },

        _initProperty: function (name) {
            this._data[name] = {
                value: null,
                getter: function () {
                    return this.value;
                },
                setter: function (value) {
                    this.value = value;
                }
            };
        }
    */
    });
});