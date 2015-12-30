<template>
    <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            {{ label }}
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
            <li v-for="option in options">
                <a href="#" :class="{'selected': $key == value}" @click="optionSelected($key, option, $event)">
                    <i class="fa fa-check fa-btn" v-if="$key == value"></i>
                    {{ option }}
                </a>
            </li>
        </ul>
    </div>
</template>
<script>
    module.exports = {
        props: {
            options: {
                required: true,
                type: Object
            },
            required: {
                default: function () {
                    return true;
                },
                type: Boolean
            },
            selected: {
                required: false,
                default: function () {
                    return null;
                }
            }
        },
        data: function () {
            return {
                value: '',
                label: ''
            };
        },
        ready: function () {
            var values = Object.keys(this.options);
            if (this.required && values.length > 0) {
                var label = this.options[values[0]];
                var value = values[0];
                if (this.selected && this.selected in this.options) {
                    label = this.options[this.selected];
                    value = this.selected;
                }

                this.label = this.options[values[0]];
                this.value = values[0];

            }
        },
        methods: {
            optionSelected: function (key, option, e) {
                e.preventDefault();
                var optionChanged = this.value != key;
                this.value = key; 
                this.label = option;
                if (optionChanged) {
                    this.$dispatch('dropdown:option-changed', {
                        value: this.value,
                        label: this.label
                    });   
                }
            }
        }

    }
</script>  