<template>
    <div class="sidebar gray-bg">
        <div class="user-block gray-lighter-bg">
            <avatar :src="user.img" class="bordered img-responsive"></avatar>
            <span class="name">{{ user.name }}</span>
        </div>
        <div class="user-menu">
            <ul>
                <li class="menu-title">Events</li>
                <li>
                    <a href="/events/create">Create</a>
                </li>
                <li>
                    <a href="/events">Upcoming</a>
                </li>
                <li>
                    <a href="/user/events">Organized by me</a>
                </li>
                <li>
                    <a href="/logout">Logout</a>
                </li>
                <!-- <li class="clearfix">
                    <a href="#">Notifications <span class="label label-primary pull-right">12</span></a>
                </li> -->
            </ul>
            <ul class="m-top-40">
                <li class="menu-title">Application</li>
                <li><a href="/settings">Settings</a></li>
            </ul>
        </div>
    </div>    
</template>
<script>
    module.exports = {
        data: function () {
            return  {
                user: App.user()
            }
        }
    };
</script>