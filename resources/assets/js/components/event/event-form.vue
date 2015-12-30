<template>
    
    <form @submit="submit">
        <div class="alert alert-dagner" v-show="showErrors"></div>
        <div class="form-group">
            <label for="" class="control-label">Title</label>
            <input type="text" class="form-control" v-model="model.title">
        </div>
        <div class="form-group">
            <label for="" class="control-label">Description</label>
            <textarea name="" cols="30" rows="10" class="form-control" v-model="model.description"></textarea>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Latitude</label>
            <input type="text" class="form-control" v-model="model.latitude">
        </div>
        <div class="form-group">
            <label for="" class="control-label">Longitude</label>
            <input type="text" class="form-control" v-model="model.longitude">
        </div>
        <div class="form-group">
            <label for="" class="control-label">Starts at (yyyy-mm-dd hh:mm)</label>
            <input type="text" class="form-control" v-model="model.starts_at">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary"><i class="fa fa-plus fa-btn"></i> Save</button>
        </div>
    </form>
</template>
<script>
    module.exports = {
        data: function () {
            return {
                model: {
                    // id: '',
                    title: '',
                    description: '',
                    latitude: 0.0,
                    longitude: 0.0,
                    starts_at: '',
                }
            };
        },
        computed: {
        },
        props: ['event'] 

            /*event: {
                type: Object,
                default: function () {
                    return null;
                }
            },*/
        ,
        ready: function () {
            if (this.event) {
                this.model['id'] = this.event.id;
                this.model['title'] = this.event.title;
                this.model['description'] = this.event.desc;
                this.model['latitude'] = this.event.lat;
                this.model['longitude'] = this.event.lng;
                this.model['starts_at'] = this.event.starts_at;
            }
        },
        methods: {
            submit: function (e) {
                e.preventDefault();
                var method = 'post';
                var url = '/events';
                
                if (this.event && this.event.id) {
                    method = 'patch';
                    url = '/events/' + this.event.id;
                }

                this.$http[method](url, this.model).then(function (resp) {
                    App.alert('Event has succesfully been saved.');
                    setTimeout(function () {
                        window.location = '/user/events';
                    }, 1000);
                }, function (resp) {
                    var errors = resp.data;
                    App.alert(errors, 'danger');
                });
            },
        }
    }
</script>