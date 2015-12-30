<template>
    <div>
        <dropdown :options="{'starts_at.desc': 'Most recent', 'participants.desc': 'Most popular'}", :selected="1">  
        </dropdown>
    </div>
    <ul class="events-list">
        <li class="event" v-for="event in paginator.data">
            <div class="row">
                <div class="col-xs-2">
                    <img :src="event.img" class="img-responsive img-thumbnail">
                </div>
                <div class="col-xs-10">
                    <div class="row">
                        <div class="col-xs-7">
                            <h4 class="title">
                                {{ event.title }} <i data-toggle="tooltip" data-placement="top" title="Participating already." v-if="isParticipating(event)" class="text-primary fa fa-check-circle"></i>
                            </h4>
                        </div>
                        <div class="col-xs-5">
                            <div>
                                Starts at: {{ event.starts_at }}
                            </div>
                            <div>
                                Participants: {{ event.participants.length }}
                            </div>
                        </div>
                    </div>
                    
                    <p>
                        {{ event.desc }}
                    </p>
                    <a :href="'/events/' + event.id" target="_blank" class="btn btn-sm btn-primary">SHOW MORE <i class="fa fa-caret-right fa-btn-right"></i></a>
                </div>
            </div>
        </li>
    </ul>
    <div class="text-center">
        <button class="btn btn-primary btn-block" @click="loadMore" v-if="paginator.total > paginator.data.length">MORE</button>
    </div>
</template>
<script>
    module.exports = {
        props: {
            paginator: {
                type: Object,
                required: true
            },
            url: {
                type: String,
                required: true
            }
        },
        data: function () {
            return {
                label: '',
                value: '',
            }
        },
        computed: {

        },
        ready: function () {
            this.listenToEvents();
        },
        methods: {
            listenToEvents: function () {
                this.$on('dropdown:option-changed', function (data) {
                    this.label = data.label;
                    this.value = data.value;
                    data = {
                        'orderBy': this.value,
                    };
                    this.load(1, data);
                }.bind(this));
            },
            isParticipating: function (event) {
                return event.participants.some(function (participant) {
                    return participant.id == App.config.user.id;
                }, this);
            },
            loadMore: function (e) {
                e.preventDefault();
                this.load();
            },
            load: function (page, data) {
                var self = this;
                var data = data !== undefined ? data : {};
                var appendItems = page === undefined;
                var page = page !== undefined ? page : this.paginator.current_page + 1
                var url = this.url + '?page=' + page;

                this.$http.get(url, data).then(function (response) {
                    var items = [];
                    var paginator = response.data.paginator;
                    if (appendItems) {
                        items = self.paginator.data;
                        Array.prototype.push.apply(items, paginator.data);   
                    } else {
                        items = paginator.data;
                    }
                    paginator.data = items;
                    self.$set('paginator', paginator);
                });
            }
        },
    }
</script>