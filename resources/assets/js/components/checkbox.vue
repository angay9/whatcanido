<template>
    <label for="" class="m-left-5 va-bottom">
        <i class="checkbox text-primary bg-default fa" @click="onChange" :class="{'fa-check-square': checked, 'fa-square': !checked}"></i>
        <span class="m-left-5">{{ label }}</span>        
    </label>

</template>
<script>
    module.exports = {
        props: {
            checked: {
                type: Boolean,
                default: false
            },
            label: {
                type: String,
                default: ''
            },
            whenChanged: {
                type: Function,
                default: function () {
                    return new Function();
                }
            }
        },
        methods: {
            onChange: function () {
                this.checked = ! this.checked;
                this.whenChanged(this.checked);
            }
        }
    }
</script>