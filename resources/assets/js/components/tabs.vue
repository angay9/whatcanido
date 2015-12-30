<template>
    <div class="tab-container">
        <slot></slot>
        <div class="tab-controls clearfix">
            <a href="#one" class="tab-control"  @click="active = $index" :class="{'active': $index == active}" v-for="n in 4">Text {{ $index }}</a>
        </div>
        <div class="tab-container">
            <div id="one" v-for="n in 4" :class="{'hide': $index != active}">
                <p>Text {{ $index }}</p>
            </div>
        </div>
    </div>
</template>
<script>
    module.exports = {
        data: function () {
            return {

            };
        },
        props: {
            active: {
                type: Number,
                default: function () {
                    return 0;
                }
            }
        }
    }
</script>