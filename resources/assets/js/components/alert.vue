<template>
    <div class="alert fade-transition" 
        role="alert" 
        v-show="!!store.messages[store.type]"
        :class="{ 
            'alert-success': store.type == 'success', 
            'alert-info': store.type == 'info',
            'alert-warning': store.type == 'warning', 
            'alert-danger': store.type == 'danger'
        }"
    >
        <button type="button" class="close" @click="hide">
          <span>×</span>
        </button>

        <strong></strong>
        <p v-for="msg in store.messages[store.type]">{{{ msg }}}</p>
      </div>
</template>
<script>
    module.exports = {
        data: function () {
            return {
                store: App.store.messagesStore
            };
        },
        ready: function () {
        },
        methods: {
            hide: function (e) {
                e.preventDefault();
                this.show = false;
                App.clearAlert(this.store.type);
            },
            show: function (e) {
                e.preventDefault();
                this.show = true;
            }
        }

    };
</script>