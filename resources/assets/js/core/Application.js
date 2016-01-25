var App = {
    config: {
        user: null,
        csrfToken: null,
        url: null,
        avatarPath: null
    },
    store: {
        messagesStore: require('../stores/messagesStore.js')
    },
    user: function () {
        var user = this.config.user;
        user.avatar = function () {
            if (!this.img) {
                return 'https://placehold.it/150x150';
            }
            
            var pat = /^http?:\/\//i;
            var pat2 = /^https?:\/\//i;
            if (pat.test(this.img) || pat2.test(this.img))
            {
                return this.img;
            }

            return App.config.avatarPath + '/' + this.id + '/' + this.img;
        };
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
            if (typeof msg[key] == 'string') {
                msg[key] = [msg[key]];   
            }
            
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