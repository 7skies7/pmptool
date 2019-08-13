<template>
    <table class="table table-bordered table-responsive" id="acltable" style="width:100%">
        <thead>
            <tr>
                <th scope="col" width="20%"><code class="secondary-gradient">Month(July)</code></th>
                <th width="2%" v-for="date in 31" :key="date.id" scope="col" class="secondary-gradient">{{ date }}</th>
                <th width="10%">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="resource in resources" :key="resource.id">
                <th class="primary-gradient" width="20%">{{ resource }}</th>
                
                <td width="2%" v-for="hour in 31" :key="hour.id">
                    08:00
                </td>
                <td width="10%">
                    241
                </td>
            </tr>
            
        </tbody>
    </table>
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
                resources: ['Prabhakar Mudliyar', 'Tirthesh Trivedi', 'John Doe', 'Jack Doe'],
                headers: [
                    { text: 'Resource Name', align: 'left', value: 'resource_name'},
                    { text: 'Project Name', value: 'project_name' },
                    { text: 'CR Title', value: 'crd_title' },
                    { text: 'Userstory Name', value: 'userstory_desc' },
                    { text: 'Task Name', value: 'task_title' },
                    { text: 'Sub Task Name', value: 'sub_task_title' },
                    { text: 'Date', value: 'created_at' },
                    { text: 'Comment', value: 'comments' },
                    { text: 'Hours Logged', value: 'task_hours', class: 'hourstd'},
                    { text: 'Face Capture', value: 'timecard' }
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
