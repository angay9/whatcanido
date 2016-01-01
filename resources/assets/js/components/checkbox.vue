<template>
    <label for="" v-if="label != ''" class="m-left-5 va-bottom">
        <i 
            class="checkbox bg-default fa" 
            @click="onChange" 
            :class="{
                'fa-check-square': checked, 
                'fa-square': !checked,
                'text-primary': checked,
                'text-default': !checked
            }"
        >
        </i>
        <span class="m-left-5">{{ label }}</span>        
    </label>

    <i 
        v-else
        class="checkbox bg-default fa" 
        @click="onChange" 
        :class="{
            'fa-check-square': checked, 
            'fa-square': !checked,
            'text-primary': checked,
            'text-default': !checked
        }"
    >

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