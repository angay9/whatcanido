<template>
    <tabs>
        <tab label="General">
            <form action="" method="POST" class="form-horizontal" role="form" @submit="save">
                <div class="form-group">
                    <label for="" class="conrol-label col-xs-2">Email</label>
                    <div class="col-xs-10">
                        <input type="text" class="form-control" v-model="user.email">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="conrol-label col-xs-2">Address</label>
                    <div class="col-xs-10">
                        <input type="text" class="form-control" id="address" v-model="user.address">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="conrol-label col-xs-2">Email notifications</label>
                    <div class="col-xs-10">
                        <checkbox :checked.sync="user.settings.email_notifications">
                        </checkbox>
                    </div>
                </div>
        
                <div class="form-group">
                    <div class="col-xs-10 col-xs-offset-2">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </tab>
        <tab label="Avatar">
            <file-uploader url="/settings/avatar">
            </file-uploader>
        </tab>
    </tabs>

</template>
<script>
    module.exports = {
        /*props: {
            user: {
                type: Object,
                required: true
            }
        },*/
        data: function () {
            return {
                user: App.user(),
            };
        },
        created: function () {
            if (!this.user.settings) {
                this.user.settings = {
                    email_notifications: false
                };
            }
            this.user.settings.email_notifications = this.user.settings.email_notifications == 1 ? true : false;
        },
        ready: function () {
            var input = document.getElementById('address');
            var autocomplete = new google.maps.places.Autocomplete(input);
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();
                this.user['address'] = place.formatted_address;
            }.bind(this));
        },
        methods: {
            save: function (e) {
                e.preventDefault();
                var data = {
                    address: this.user.address,
                    email: this.user.email,
                    email_notifications: this.user.settings.email_notifications ? 1 : 0
                };

                this.$http.post('/settings', data)
                    .then(function (resp) {
                        App.alert('Saved.');
                    }, function (resp) {
                        App.alert(resp.data, 'danger');
                    });
            }
        }
    }
</script>