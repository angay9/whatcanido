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
        <!-- <div class="form-group">
            <label for="file">
                Event Image [jpg, png]
            </label>
            <input type="file" name="file" id="file" class="form-control">    
        </div> -->
        <div class="form-group">
            <label for="" class="control-label">Starts at (yyyy-mm-dd hh:mm)</label>
            <input type="text" class="form-control date" v-model="model.starts_at">
        </div>
        <div class="form-group">
            <label for="" class="control-label">Place</label>
            <input type="text" class="form-control" v-model="model.place" id="place">
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
                    latitude: '',
                    longitude: '',
                    starts_at: '',
                    place: '',
                    file: ''
                }
            };
        },
        computed: {
        },
        props: ['event'],
        ready: function () {
            var input = document.getElementById('place');
            var autocomplete = new google.maps.places.Autocomplete(input);
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();
                this.model['place'] = place.formatted_address;
                this.model['latitude'] = place.geometry.location.lat();
                this.model['longitude'] = place.geometry.location.lng();

            }.bind(this));
            if (this.event) {
                this.model['id'] = this.event.id;
                this.model['title'] = this.event.title;
                this.model['description'] = this.event.desc;
                this.model['latitude'] = this.event.lat;
                this.model['longitude'] = this.event.lng;
                this.model['starts_at'] = this.event.starts_at;
                this.model['place'] = this.event.place;
                this.model['file'] = this.event.file;
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
                // var data = new FormData();

                // data.append('id', this.model['id']);
                // data.append('title', this.model['title']);
                // data.append('description', this.model['description']);
                // data.append('latitude', this.model['latitude']);
                // data.append('longitude', this.model['longitude']);
                // data.append('starts_at', this.model['starts_at']);
                // data.append('place', this.model['place']);
                // data.append('file', document.getElementById('file').files[0]);

                this.$http[method](url, this.model).then(function (resp) {
                    App.alert('Event has succesfully been saved.');
                    setTimeout(function () {
                        // window.location = '/user/events';
                    }, 1000);
                }, function (resp) {
                    var errors = resp.data;
                    App.alert(errors, 'danger');
                });
            },
        }
    }
</script>