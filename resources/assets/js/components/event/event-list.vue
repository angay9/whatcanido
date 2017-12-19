<template>
    <div>
        <!-- <dropdown 
            :when-changed="onDropdownChange" 
            :options="{'starts_at.desc': 'Most recent', 'participants_count.desc': 'Most popular'}" 
            :value.sync="value"
        >  
        </dropdown> -->
        <checkbox 
            label="Old" 
            :checked.sync="showOld" 
            :when-changed="loadWithFilters">
        </checkbox>
    </div>
    <ul class="list" v-if="paginator.data.length > 0">
        <li class="list-item" v-for="event in paginator.data">
            <div class="row">
                <div class="col-xs-2">
                    <img :src="eventImg(event)" class="img-responsive img-thumbnail">
                </div>
                <div class="col-xs-10">
                    <div class="row">
                        <div class="col-xs-12">
                            <h4 class="title">
                                {{ event.title }} <i data-toggle="tooltip" data-placement="top" title="Participating already." v-if="isParticipating(event)" class="text-primary fa fa-check-circle"></i>
                            </h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            Starts at: {{ event.starts_at }}
                        </div>
                        <div class="col-xs-4">
                            Participants: {{ event.participants.length }}
                        </div>
                        <div class="col-xs-4">
                            Place: {{ event.place }}
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
    <div class="text-center" v-if="paginator.data.length > 0">
        <button class="btn btn-primary btn-block" @click="loadMore" v-if="paginator.total > paginator.data.length">MORE</button>
    </div>
    <div v-if="paginator.data.length == 0">
        No events yet.
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
            },
            showOld: {
                type: Boolean,
                default: false
            }
        },
        data: function () {
            return {
                label: '',
                value: 'starts_at.desc',
            }
        },
        ready: function () {
        },
        methods: {
            isParticipating: function (event) {
                return event.participants.some(function (participant) {
                    return participant.id == App.user().id;
                }, this);
            },
            loadMore: function (e) {
                e.preventDefault();
                var data = {
                    'orderBy': this.value,
                    'showOld': this.showOld ? 1 : 0
                };
                this.load(undefined, data);
            },
            loadWithFilters: function () {
                var data = {
                    'orderBy': this.value,
                    'showOld': this.showOld ? 1 : 0
                };
                this.load(1, data);
            },
            load: function (page, data, appendItems) {
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
            },
            onDropdownChange: function (data) {
                this.value = data.value;
                this.label = data.label;
                this.loadWithFilters();
            },
            eventImg: function (event) {
                return `https://maps.googleapis.com/maps/api/streetview?size=80x80&location=${event.lat},${event.lng}&key=${App.config.googleMapsApiKey}`;
            }
        },
    }
</script>