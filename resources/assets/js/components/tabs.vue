<template>
    <div class="tab-controls clearfix">
        <a href="#" 
            class="tab-control" 
            @click="whenTabCliecked($index, $event)"
            :class="{'active': activeIndex == $index}"
            v-for="label in labels"
        >
            {{ label }}
        </a>
    </div>
    <div class="tab-container">
        <slot></slot>
    </div>
</template>
<script>
    module.exports = {
        data: function () {
            return {
                activeIndex: 0,
                labels: []
            };
        },
        props: {
        },
        ready: function () {
        },
        methods: {
            whenTabCliecked: function (index, e) {
                e.preventDefault();
                this.$set('activeIndex', index);
            }
        }
    }
</script>