<template>
    <div class="content-block">
        <img :src="eventImg(event)" alt="" class="img-responsive event-view-img">
    </div>
    <div class="container-fluid gray-lighter-bg event-desc">
        <div class="row">
            <div class="col-md-12">
                <avatar :src="avatar(event.creator)" class="bordered img-responsive"></avatar>
                <span class="name text-center">{{ event.creator.name }}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-gray text-center">
                <a v-if="canEdit" :href="'/events/' + this.event.id + '/edit'" class="btn btn-primary"><i class="fa fa-btn fa-pencil"></i>Edit</a>

                <h3>{{ event.title }}</h3>
                {{ event.desc }}
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 text-center">
                <h5 class="text-gray">PEOPLE</h5>
                <span class="count">{{ event.participants.length > 0 ? event.participants.length : '0' }}</span>
            </div>
            <div class="col-md-6 text-center">
                <h5 class="text-gray">COMMENTS</h5>
                <span class="count">{{ event.comments.length }}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center m-top-10 m-bot-10" v-if="canParticipate">
                <a href="#" class="btn btn-primary" v-if="!isParticipating" @click="participate">
                    <i class="fa fa-plus-square fa-btn"></i> PARTICIPATE
                </a>
                <a href="#" class="btn btn-danger" v-else @click="leave">
                    <i class="fa fa-minus-square fa-btn"></i> LEAVE
                </a>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row gray-bg">
            <div class="col-md-12">
                <tabs>
                    <tab label="Comments">
                        <ul class="list" v-if="event.comments.length > 0">
                            <li 
                                class="list-item" 
                                v-for="comment in event.comments" 
                            >
                                <div class="row">
                                    <div class="col-xs-4 col-sm-2">
                                        <avatar :src="avatar(comment.user)" class="avatar-sm"></avatar>
                                    </div>
                                    <div class="col-xs-8 col-sm-10">
                                        <div class="row">
                                            <div class="col-xs-12" :class="{'cursor-pointer': isCommentOwner(comment)}" @click="editComment(comment, $event)">
                                                <a href="#" v-if="isCommentOwner(comment)" class="close text-default m-right-10 pull-right" @click="deleteComment(comment, $event)">
                                                    <i class="fa fa-close"></i>
                                                </a>
                                                
                                                <span class="m-right-10 font-bold">{{ comment.user.name }}</span>
                                                <div>
                                                    <em>{{ comment.created_at }}</em>
                                                </div>
                                                <p>{{ comment.comment }}</p>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div>
                            <textarea placeholder="What do you think?" v-model="comment.comment" cols="10" rows="3" class="form-control">
                            </textarea>
                            <button class="btn btn-primary m-top-15" @click="saveComment">
                                <i class="fa fa-plus fa-btn"></i>
                                Send
                            </button>
                        </div>
                    </tab>
                    <tab label="Users">
                        <ul class="list" v-if="event.participants.length">
                            <li class="list-item" v-for="participant in event.participants">
                                <div class="row">
                                    <div class="col-xs-2">
                                        <avatar :src="avatar(participant)" class="avatar-sm"></avatar>
                                    </div>
                                    <div class="col-xs-10">
                                        <h4>{{ participant.name }}</h4>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <p v-else>
                            No participants yet.
                        </p>
                    </tab>
                </tabs>
            </div>
        </div>
    </div>
</template>
<script>
    module.exports = {
        props: {
            event: {
                type: Object
            }
        },
        data: function () {
            return {
                comment: {
                    id: null,
                    comment: '',
                }
            }
        },
        computed: {
            canParticipate: function () {
                return (App.user().id != this.event.creator.id)
            },
            isParticipating: function () {
                return this.event.participants.some(function (participant) {
                    return participant.id == App.user().id;
                }, this);
            },
            canEdit: function () {
                return this.event && App.user().id == this.event.creator.id;
            }
        },
        ready: function () {
            App.socket.on('whatcanido-channel:App\\Events\\UserParticipatedInEvent', this.onUserParticipated);
            App.socket.on('whatcanido-channel:App\\Events\\UserLeftEvent', this.onUserLeft);

            App.socket.on('whatcanido-channel:App\\Events\\CommentWasCreated', this.onCommentCreated);
            App.socket.on('whatcanido-channel:App\\Events\\CommentWasUpdated', this.onCommentUpdated);
            App.socket.on('whatcanido-channel:App\\Events\\CommentWasDeleted', this.onCommentDeleted);
        },
        methods: {
            participate: function (e) {
                e.preventDefault();

                this.$http
                    .post('/events/participate', {'event_id': this.event.id})
                    .then(function (response) {
                        App.alert('Welcome onboard.');
                    })
                ;
            },
            leave: function (e) {
                e.preventDefault();
                
                this.$http
                    .delete('/events/leave', {'event_id': this.event.id})
                    .then(function (response) {
                        App.alert('We will be missing you.');
                    })
                ;
            },
            saveComment: function() {
                var self = this;
                var updateComment = this.comment.id != null;
                var method = 'post';
                var data = {comment: this.comment.comment, event_id: this.event.id};
                var url = '/comments';
                
                if (updateComment) {
                    method = 'patch';
                    data['id'] = this.comment.id;
                    url += ('/' + this.comment.id);
                }

                this.$http[method](url, data)
                    .then(function(response) {
                        if (!updateComment) {
                            self.event.comments.push(response.data.comment);
                        } else {
                            var comment = self.findComment(this.comment.id);
                            comment.comment = response.data.comment.comment;
                            comment.created_at = response.data.comment.created_at;
                            comment.updated_at = response.data.comment.updated_at;
                        }
                        self.comment = {
                            id: null,
                            comment: '',
                        };
                    }, function (response) {
                        App.alert(response.data, 'danger');
                    });
            },
            deleteComment: function(comment, e) {
                e.preventDefault();
                if (confirm('Are you sure ?')) {
                    this.$http.delete('/comments/' + comment.id)
                        .then(function (resp) {
                            this.comment = {
                                id: null,
                                comment: '',
                            };
                            this.event.comments.$remove(comment);
                        });

                }
            },
            findComment: function (id) {
                var comments = this.event.comments.filter(function (comment) {
                    return comment.id == id;
                }.bind(this));

                return comments.length > 0 ? comments[0] : null;
            },
            editComment: function (comment, e) {
                e.preventDefault();

                if (!this.isCommentOwner(comment)) {
                    return false;
                }
                
                this.$set('comment', {
                    id: comment.id,
                    comment: comment.comment,
                });
            },
            isCommentOwner: function (comment) {
                return App.user().id == comment.user.id;
            },
            onUserParticipated: function (data) {
                if (this.event.id == data.event.id) {
                    this.$set('event.participants', data.event.participants);   
                }
            },
            onUserLeft: function (data) {
                if (this.event.id == data.event.id) {
                    this.$set('event.participants', data.event.participants);
                }
            },
            onCommentCreated: function (data) {
                if (App.user().id != data.comment.user.id) {
                    this.event.comments.push(data.comment);
                }
            },
            onCommentUpdated: function (data) {
                if (App.user().id != data.comment.user.id) {
                    var comment = this.findComment(data.comment.id);
                    comment.comment = data.comment.comment;
                    comment.created_at = data.comment.created_at;
                    comment.updated_at = data.comment.updated_at;
                    comment.user = data.comment.user;
                }
                
            },
            onCommentDeleted: function (data) {
                var comment = this.findComment(data.id);
                if (App.user().id != comment.user.id) {
                    this.event.comments.$remove(comment);   
                }
            },
            avatar: function (user) {
                var pat = /^http?:\/\//i;
                var pat2 = /^https?:\/\//i;
                if (!user.img) {
                    return 'https://placehold.it/150x150';
                }

                if (pat.test(user.img) || pat2.test(user.img)) {
                    return user.img;
                }

                return App.config.avatarPath + '/' + user.id + '/' + user.img;
            },
            eventImg: function (event) {
                return `https://maps.googleapis.com/maps/api/streetview?size=600x200&location=${event.lat},${event.lng}&key=${App.config.googleMapsApiKey}`;
            }
        }
    };
</script>
