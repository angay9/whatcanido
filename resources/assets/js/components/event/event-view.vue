<template>
    <div class="content-block">
        <img :src="event.img" alt="" class="img-responsive">
    </div>
    <div class="container-fluid gray-lighter-bg event-desc">
        <div class="row">
            <div class="col-md-12">
                <avatar :src="event.creator.img"></avatar>
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
                            <li class="list-item" v-for="comment in event.comments">
                                <div class="row">
                                    <div class="col-xs-2">
                                        <avatar :src="comment.user.img"></avatar>
                                    </div>
                                    <div class="col-xs-10">
                                        <div class="row">
                                            <div class="col-xs-12" v-if="isCommentOwner(comment)">
                                                <a href="#" class="text-default m-right-10" @click="editComment(comment, $event)">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a href="#" class="text-default m-right-10" @click="deleteComment(comment, $event)">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                <span>{{ comment.created_at }}</span>
                                            </div>
                                        </div>
                                        <p>{{ comment.comment }}</p>
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
                        Users
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
                return (App.config.user.id != this.event.creator.id)
                // &&
                // !this.isParticipating
            },
            isParticipating: function () {
                return this.event.participants.some(function (participant) {
                    return participant.id == App.config.user.id;
                }, this);
            },
            canEdit: function () {
                return this.event && App.config.user.id == this.event.creator.id;
            }
        },
        ready: function () {
            App.socket.on('whatcanido-channel:App\\Events\\UserParticipatedInEvent', this.onUserParticipated);
            App.socket.on('whatcanido-channel:App\\Events\\UserLeftEvent', this.onUserLeft);
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
                            var comment = self.event.comments.filter(function (comment) {
                                return comment.id == this.comment.id;
                            }.bind(this))[0];
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
                            this.event.comments.$remove(comment);
                        });

                }
            },
            editComment: function (comment, e) {
                e.preventDefault();
                this.$set('comment', {
                    id: comment.id,
                    comment: comment.comment,
                });
            },
            isCommentOwner: function (comment) {
                return App.config.user.id == comment.user.id;
            },
            onUserParticipated: function (data) {
                this.$set('event.participants', data.event.participants);
            },
            onUserLeft: function (data) {
                this.$set('event.participants', data.event.participants);
            }
        }
    };
</script>
