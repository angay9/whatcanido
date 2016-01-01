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
            whenChanged: {
                type: Function,
                default: function () {
                    return new Function();
                }
            },
            value: {
                required: false,
                default: function () {
                    return null;
                }
            }
        },
        data: function () {
            return {
                label: ''
            };
        },
        ready: function () {
            var values = Object.keys(this.options);
            if (this.required && values.length > 0) {
                var label = this.options[values[0]];
                var value = values[0];

                // Use default value if provided for value and label
                if (this.value && this.value in this.options) {
                    label = this.options[this.value];
                    value = this.value;
                }

                this.label = label;
                this.value = value;
            }
        },
        methods: {
            optionSelected: function (key, option, e) {
                e.preventDefault();
                var optionChanged = this.value != key;
                this.value = key; 
                this.label = option;
                if (optionChanged) {
                    this.whenChanged({
                        value: this.value,
                        label: this.label
                    });
                    
                    // this.$dispatch('dropdown:option-changed', {
                    //     value: this.value,
                    //     label: this.label
                    // });   
                }
            }
        }

    }
</script>  