/**
 * Global socket events
 * 
 */
module.exports = {
    observe: function () {
        App.socket.on('whatcanido-channel:App\\Events\\EventWasCreated', this.onEventWasCreated);
        App.socket.on('whatcanido-channel:App\\Events\\UserParticipatedInEvent', this.onUserParticipated);
        App.socket.on('whatcanido-channel:App\\Events\\UserLeftEvent', this.onUserLeft);
    },
    onEventWasCreated: function (data) {
        if (App.config.user.id != data.event.creator_id) {
            App.alert('New <a href="/events/' + data.event.id + '">event</a> was created.');   
        }
    },
    onUserParticipated: function (data) {
        if (App.config.user.id == data.event.creator.id) {
            App.alert('New user is now participating in your <a href="/events/' + data.event.id + '">event</a>.');    
        }
    },
    onUserLeft: function (data) {
        if (App.config.user.id == data.event.creator.id) {
            App.alert('A user has left your <a href="/events/' + data.event.id + '">event</a>');                
        }
    }
}