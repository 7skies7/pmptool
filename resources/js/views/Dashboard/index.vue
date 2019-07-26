<template>
    <!-- <manager></manager> -->
    <component :is="dashboard"></component>
</template>

<script>
    import superadmin from './superadmin.vue';
    import admin from './admin.vue';
    import programmanager from './programmanager.vue';
    import projectmanager from './projectmanager.vue';
    import developer from './developer.vue';
    import User from '../../models/User';

    export default {
        components: {
            developer, projectmanager, programmanager, admin, superadmin
        },
        data() {
            return{
                dashboard:'',
        
            }
        },
        created() {
            User.fetchRole(role => this.dashboard = role);
        },
        methods: {
           
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
