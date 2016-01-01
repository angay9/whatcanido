var App = {
    config: {
        user: null,
        csrfToken: null,
        url: null
    },
    store: {
        messagesStore: require('../stores/messagesStore.js')
    },
    user: function () {
        return this.config.user;
    }
};

App.init = function (config) {
    this.config = config;
};

App.alert = function (msg, type, normalizeStrings) {
    
    var normalizeStrings = normalizeStrings === undefined ? true : normalizeStrings;

    /**
     * Convert the msg parameter to an array
     */
    var type = type || 'info';
    var messages = msg;
    if (typeof msg == "string") {
        messages = [msg];
    }
    else if (msg instanceof Object && ! (msg instanceof Array)) {
        var keys = Object.keys(msg);
        messages = keys.map(function (key) {
            return key + ': ' + msg[key].join('<br />'); 
        });
    };
    /**
     * Clear all the other message types
     */
    var types = Object.keys(App.store.messagesStore.messages);
    types.forEach(function (type) {
        App.store.messagesStore.messages[type] = null;
    });

    if (normalizeStrings) {
        var normalizeFunc = function (msg) {
            return msg.replace(/_/g, ' ');
        };
        messages = messages.map(normalizeFunc);
    }

    App.store.messagesStore.messages[type] = messages;
    App.store.messagesStore['type'] = type;

    setTimeout(function () {
        App.clearAlert();
    }, 3000);
};

App.clearAlert = function (type) {
    App.store.messagesStore.messages[type] = null;
    App.store.messagesStore['type'] = null;
};


App.clearConfirm = function (type) {
    App.store.messagesStore.messages[type] = null;
    App.store.messagesStore['type'] = null;
};

module.exports = App;