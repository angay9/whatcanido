/**
 * Global socket events
 * 
 */
module.exports = {
    observe: function () {
        App.socket.on('whatcanido-channel:App\\Events\\EventWasCreated', this.onEventWasCreated);
        App.socket.on('whatcanido-channel:App\\Events\\UserParticipatedInEvent', this.onUserParticipated);
        App.socket.on('whatcanido-channel:App\\Events\\UserLeftEvent', this.onUserLeft);
        App.socket.on('whatcanido-channel:App\\Events\\CommentWasCreated', this.onCommentCreated);
    },
    onEventWasCreated: function (data) {
        if (App.user().id != data.event.creator_id) {
            App.alert('New <a href="/events/' + data.event.id + '">event</a> was created.');   
        }
    },
    onUserParticipated: function (data) {
        if (App.user().id == data.event.creator.id) {
            App.alert('New user is now participating in your <a href="/events/' + data.event.id + '">event</a>.');    
        }
    },
    onUserLeft: function (data) {
        if (App.user().id == data.event.creator.id) {
            App.alert('A user has left your <a href="/events/' + data.event.id + '">event</a>');                
        }
    },
    onCommentCreated: function (data) {
        if (App.user().id != data.comment.user.id && App.user().id == data.comment.event.creator_id) {
            App.alert('A user has comment on one <a href="/events/' + data.comment.event_id + '">one</a> of your events.');
        }
    }
}