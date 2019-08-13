<template>
    <div id="app">
        <v-card-title>
            <v-spacer></v-spacer>
            <v-spacer></v-spacer>
            <v-spacer></v-spacer>
            <v-spacer></v-spacer>
            <v-text-field
              v-model="search"
              append-icon="search"
              label="Search"
              single-line
              hide-details
            ></v-text-field>
        </v-card-title>
        <v-app id="inspire">
            <v-data-table :headers="headers" :items="records" class="elevation-1">
                <template v-slot:items="props">
                    <td>{{ props.item.project_name }}</td>
                    <td class="text-xs-left">{{ props.item.crd_title }}</td>
                    <td class="text-xs-left">{{ props.item.userstory_desc }}</td>
                    <td class="text-xs-left">{{ props.item.task_title }}</td>
                    <td class="text-xs-left">{{ props.item.sub_task_title }}</td>
                    <td class="text-xs-center">{{ props.item.resource_name }}</td>
                    <td class="text-xs-center">{{ props.item.created_at }}</td>
                    <td class="text-xs-left">{{ props.item.comments }}</td>
                    <td class="text-xs-center">{{ props.item.task_hours }}</td>
                </template>
            </v-data-table>
        </v-app>
    </div>
</template>

<script>
    // import Acl from '../../models/Acl';
    export default {
        components: {
        },
        data() {
            return {
                cardWidth: 'col-md-12',
                search: '',
                headers: [
                    { text: 'Project Name', align: 'left', value: 'project_name' },
                    { text: 'CR Title', value: 'crd_title' },
                    { text: 'Userstory Name', value: 'userstory_desc' },
                    { text: 'Task Name', value: 'task_title' },
                    { text: 'Sub Task Name', value: 'sub_task_title' },
                    { text: 'Resource Name', value: 'resource_name'},
                    { text: 'Date', value: 'created_at' },
                    { text: 'Comment', value: 'comments' },
                    { text: 'Hours Logged', value: 'task_hours', class: 'hourstd'},
                ],
                records: [
                    {
                        resource_name: 'Prabhakar Mudliyar',
                        project_name: 'PMS Portal',
                        crd_title: 'Need ACL for the PMS',
                        userstory_desc: 'ACL Role Management Systems Matrix',
                        task_title: 'Fetch All Roles, Modules and Actions Associated',
                        sub_task_title: 'Database Structure For Acl',
                        created_at: '2019-08-01',
                        comments: 'Structure for roles, modules and pivot tables generated',
                        task_hours: '08:00',
                        timecard: '09:00',
                    },
                    {
                        resource_name: 'Tirthesh Trivedi',
                        project_name: 'PMS Portal',
                        crd_title: 'Need ACL for the PMS',
                        userstory_desc: 'ACL Role Management Systems Matrix',
                        task_title: 'Access Matrix Saved',
                        sub_task_title: 'Form for access matrix',
                        created_at: '2019-08-02',
                        comments: 'Form UI and Structure completed',
                        task_hours: '08:00',
                        timecard: '09:00',
                    },
                    {
                        resource_name: 'User Report',
                        project_name: 'PMS Portal',
                        crd_title: 'Need ACL for the PMS',
                        userstory_desc: 'ACL Role Management Systems Matrix',
                        task_title: 'Access Matrix Saved',
                        sub_task_title: 'Form for access matrix',
                        created_at: '2019-08-02',
                        comments: 'Form UI and Structure completed',
                        task_hours: '08:00',
                        timecard: '09:00',
                    },
                    {
                        resource_name: 'Jack Fernandes',
                        project_name: 'PMS Portal',
                        crd_title: 'Need ACL for the PMS',
                        userstory_desc: 'ACL Role Management Systems Matrix',
                        task_title: 'Access Matrix Saved',
                        sub_task_title: 'Form for access matrix',
                        created_at: '2019-08-02',
                        comments: 'Form UI and Structure completed',
                        task_hours: '08:00',
                        timecard: '09:00',
                    },
                    {
                        resource_name: 'John Doe',
                        project_name: 'ICM',
                        crd_title: 'Need ACL for the PMS',
                        userstory_desc: 'ACL Role Management Systems Matrix',
                        task_title: 'Access Matrix Saved',
                        sub_task_title: 'Form for access matrix',
                        created_at: '2019-08-02',
                        comments: 'Form UI and Structure completed',
                        task_hours: '08:00',
                        timecard: '09:00',
                    },
                ]
            }   
        },
        created() {

        },
        methods: {
            onReportTabChange(id) {
                this.isLoading = true;
            },
        },
        mounted() {
            this.isLoading = true;
            // Acl.roles(roles => this.roles = roles)      
            console.log('Component mounted.')
        },
        watch: {
        }
    }
</script>
