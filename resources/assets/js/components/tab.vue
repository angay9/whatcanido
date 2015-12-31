<template>
    <div v-show="show">
        <slot></slot>
    </div>
</template>
<script>
    module.exports = {
        props: {
            label: {
                type: String,
                required: true
            },
        },
        created: function () {
            this.$parent.labels.push(this.label);
            for (var c in this.$parent.$children)
            {
                if (this.$parent.$children[c].$el == this.$el)
                {
                    this.index = c;
                    break;
                }
            }
        },
        computed: {
            show: function () {
                return this.index == this.$parent.activeIndex;
            }
        },
        data: function () {
            return {
                index: 0
            }
        },
        methods: {
        }
    }
</script>