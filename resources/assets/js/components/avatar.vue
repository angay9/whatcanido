<template>
    <img :src="src" alt="Avatar" class="avatar img-circle center-block" :class="class">
</template>
<script>
    module.exports = {
        props: ['src', 'class']
    };
</script>