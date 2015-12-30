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
                <span class="count">12 213</span>
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
                <tabs></tabs>
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
            onUserParticipated: function (data) {
                this.$set('event.participants', data.event.participants);
            },
            onUserLeft: function (data) {
                this.$set('event.participants', data.event.participants);
            }
        }
    };
</script>
