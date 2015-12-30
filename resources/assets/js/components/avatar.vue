<template>
    <img :src="src" alt="Avatar" class="avatar img-responsive img-circle bordered center-block">
</template>
<script>
    module.exports = {
        props: ['src']
    };
</script>