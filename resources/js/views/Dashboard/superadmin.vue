<template>
<div id="dashboard" style="margin-top:-24px!important">
    <v-app id="inspire">
        <v-container grid-list-md text-xs-center class="unsetWidth">
            <v-layout row wrap>
                <v-flex flex xs12 sm6 md3 v-for="item in items2" :key="item.title" class="statswidget">
                    <v-card class="mx-auto" color="white" dark >
                        <v-list two-line subheader color="grey lighten-2">
                            <v-subheader inset>{{ item.cardtitle }}</v-subheader>
                            <v-list-tile  avatar @click="">
                                <v-list-tile-avatar>
                                    <v-icon :class="[item.iconClass]">{{ item.icon }}</v-icon>
                                </v-list-tile-avatar>
          
                                <v-list-tile-content>
                                    <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                                    <!-- <v-list-tile-sub-title>{{ item.subtitle }}</v-list-tile-sub-title> -->
                                </v-list-tile-content>
          
                                <v-list-tile-action>
                                    <v-btn icon ripple>
                                        {{ item.subtitle }}
                                      <!-- <v-icon color="grey lighten-1">4</v-icon> -->
                                    </v-btn>
                                </v-list-tile-action>
                            </v-list-tile>
                        </v-list>
                    </v-card>
                </v-flex>
    
            </v-layout>
        </v-container>
    </v-app>
</div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';
    // import Highcharts from 'highcharts';
    import Chart from '../../components/Chart.vue';
    import Dashboard from '../../models/Dashboard';

   
    export default {
        components: {
            Datepicker,Chart
        },
        data() {
            return{
                addProgram: false,
                programs: [],
                cardWidth: 'col-md-10',
                progressbar: '80',
                progresscards: [
                    {title:"In Planning", value:"100", icon:"check_circle", color:"teal"},
                    {title:"Design", value:"100", icon:"check_circle", color:"pink"},
                    {title:"Development", value:"90", icon:"important_devices", color:"info"},
                    {title:"Testing", value:"10", icon:"bug_report", color:"teal"},


                ],        
            items2: [
                { icon: 'assignment', iconClass: 'blue white--text', title: 'Risks', subtitle: '19',cardtitle: 'Project' },
                { icon: 'info', iconClass: 'red white--text', title: 'Overdue ', subtitle: '90',cardtitle: 'Tasks' },
                { icon: 'attach_money', iconClass: 'green white--text', title: 'Budget', subtitle: '17080',cardtitle: 'Finance' },
                { icon: 'info', iconClass: 'red white--text', title: 'ICM Deadline ', subtitle: 'Jan 10',cardtitle: 'Upcoming Deadlines' }
            ],
            headers: [
                { text: 'Userstory ', align: 'left', sortable: false, value: 'userstory_name' },
                // { text: 'Project',  value: 'userstory_project' },
                { text: 'Point', value: 'userstory_point' },
                { text: 'Priority', value: 'userstory_priority' },
                { text: 'Progress', value: 'userstory_progress' },
            ],
            headers_approval: [
                { text: 'Userstory ', align: 'left', sortable: false, value: 'userstory_desc' },
                // { text: 'Scope',  value: 'scope.crd_title' },
                { text: 'Point', value: 'userstory_point' },
                { text: 'Priority', value: 'userstory_priority' },
                { text: 'Action', align:'center',value: '' },
            ],

            headers_proj_dead: [
                { text: 'Project Name ', align: 'left', sortable: false, value: 'project_name' },
                // { text: 'Scope',  value: 'scope.crd_title' },
                // { text: 'Project Start Date', value: 'project_start_date' },
                { text: 'End Date', value: 'project_end_date' },
                // { text: 'Status', value: 'status.status_name' },
                { text: 'Progress', value: 'project_progress' },
            ],

            headers_projects: [
                { text: 'Project Name ', align: 'left', sortable: false, value: 'project_name' },
                // { text: 'Scope',  value: 'scope.crd_title' },
                // { text: 'Project Start Date', value: 'project_start_date' },
                { text: 'Project End Date', value: 'project_end_date' },
                // { text: 'Status', value: 'status.status_name' },
                { text: 'Progress', value: 'project_progress' },
                { text: 'Action', align:'center', value: '' },

            ],

            headers_tasks: [
                { text: 'Task ', align: 'left', sortable: false, value: 'task_desc' },
                { text: 'Project',  value: 'project.project_name' },
                { text: 'Assigned To', value: 'assignee.first_name' },
                { text: 'Task Status', value: 'status.status_name' },
                { text: 'Task End Date', value: 'task_end_date' },
                { text: 'Task Completion', value: 'task_completion' },

            ],
            userstories: [],
            userstories_pending:[],
            projects_deadline:[],
            projects:[],
            tasks:[],
            comments:[],
            }
        },
        created() {
            Dashboard.getUserstoryProgress(records => this.userstories = records);
            Dashboard.getUserstoryPending(records => this.userstories_pending = records);
            Dashboard.getProjectsDeadlinePassed(records => this.projects_deadline = records);
            Dashboard.getProjects(records => this.projects = records);
            Dashboard.getProjectTasks(records => this.tasks = records);
            Dashboard.getCommentFeed(records => this.comments = records);
// avatar: '/images/User.png',
//           title: 'Worked on the new icm module',
//           subtitle: "<span class='text--primary'>Today, 4:45 PM</span>"

        },
        methods: {
            tasktime(time) {
                return moment(time).fromNow(true);
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
       
    }
</script>
