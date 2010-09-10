CMS.DataSource = Class.extend({

    source: null,
    args: [],
    isPost: false,

    successCallback: $.noop(),
    failureCallback: $.noop(),


    init: function(options) {
        this.setOptions(options);
        return this;
    },

    setOptions: function (options) {
        (options.source) && this.setSource(options.source);
        (options.args) && this.setArgs(options.args);
        (options.post) && this.setPost(options.isPost);
        (options.success) && this.setSuccessCallback(options.success);
        (options.failure) && this.setFailureCallback(options.fail);
    },

    setSource: function (source) {
        this.source = source;
    },

    setArgs: function (args) {
        this.args = args;
    },

    setPost: function (post) {
        this.isPost = post;
    },

    setSuccessCallback: function (success) {
        this.successCallback = success;
    },

    setFailureCallback: function (fail) {
        this.failureCallback = fail;
    },

    send: function () {
        if (typeof this.source === 'string') {
            return (this.isPost) ? this.post() : this.get();
        }
        return receive(this.source);
    },

    get: function () {
        $.get(this.source, this.args, this.receive, 'json');
    },

    post: function () {
        $.post(this.source, this.args, this.receive, 'json');
    },

    receive: function (data, status, request) {
        this.successCallback(data, status, request);
    }

});