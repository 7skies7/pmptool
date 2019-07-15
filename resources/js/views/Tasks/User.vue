<template>
<div id="app">
    <div class="row justify-content-center">
        <div :class="cardWidth">
            <div class="card card-default shadow-sm border-0">
                <div class="card-header">
                    <div class="sflex spacebetween">
                        <div>
                            <span>My Tasks</span>
                        </div>
                        <div class="pb-11">
                            <!-- <button v-if="isAddVisible" class="btn btn-add float-right" @click="showUploadWbs">Add Tasks</button> -->
                            <!-- <v-btn color="info" v-if="isAddVisible" @click="showUploadWbs"> Add Tasks</v-btn> -->

                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="d-flex flex-column">
                        <div class="flex-1">
                             <v-app id="inspire">
                                <div>
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
                                    <v-data-table
                                        :headers="headers"
                                        :items="tasks"
                                        :loading="isLoading"
                                        :search="search"
                                        :rows-per-page-items='[10, 20, 40, 80]'
                                        class="elevation-1"
                                    >
                                        <v-progress-linear v-show="isLoading" v-slot:progress color="blue" indeterminate></v-progress-linear>
                                        <template v-slot:items="props">
                                            <tr>
                                                <td >
                                                   {{ props.item.task_desc }}
                                                </td>
                                                <td class="text-xs-center">
                                                    <!-- <v-btn v-if="props.item.priority" flat icon  small> -->
                                                        <v-icon v-if="props.item.priority" :color="props.item.priority.priority_type" title="props.item.priority.priority_type">star</v-icon>
                                                    <!-- </v-btn> -->
                                                </td>
                                                <td class="text-xs-center">
                                                    <v-avatar color="#EF4667" size="35" v-if="(props.item.assignee).length > 0" v-for="assignee in props.item.assignee" v-bind:key="manager.id">
                                                        <span class="white--text headlinesmal" :title="assignee.first_name+' '+manager.last_name">{{ assignee.first_name[0]}}{{ assignee.last_name[0]}}</span>
                                                    </v-avatar>
                                                </td>
                                                <td class="text-xs-center"><strong>{{ props.item.task_point }}</strong></td>
                                                <td class="text-xs-center">{{ props.item.userstory.userstory_id }}</td>
                                                <!-- <td class="text-xs-center">{{ props.item.tasktype.type }}</td> -->
                                                <td class="text-xs-center"><span v-if=" props.item.task_start_date != '0000-00-00'">{{ props.item.task_start_date }}</span></td>
                                                <td class="text-xs-center"><span v-if=" props.item.task_end_date != '0000-00-00'">{{ props.item.task_end_date }}</span></td>
                                                <td class="text-xs-center">
                                                    <h5 v-if="props.item.status"><b-badge pill variant="info">{{ props.item.status.status_name }}</b-badge></h5>
                                                    <h5 v-else>-</h5>
                                                </td>
                                                <td>
                                                    <v-btn @click="onShowComments(props.item.id)" fab depressed small dark color="primary"><v-icon>add</v-icon></v-btn>
                                                </td>
                                               
                                            </tr>
                                        </template>
                                        
                                        <template v-slot:no-results>
                                            <v-alert :value="true" color="error" icon="warning">
                                                Your search for "{{ search }}" found no results.
                                            </v-alert>
                                        </template>
                                   </v-data-table>
                                </div>
                              </v-app>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <view-task v-if="isTaskCommentVisible" :taskid="taskid"></view-task>
        
    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';
    import Task from '../../models/Task';
    import ViewTask from './ViewTask';
    export default {
        components: {
            ViewTask, Task
        },
        props: ['addAccess'],
        data() {
            return{
                search: '',
                projects: [],
                headers:[
                            { text: 'Name', align: 'center', value: 'task_desc'
                            },
                            { text: 'Priority',  align: 'center', value: 'priority.priority_type' },
                            { text: 'Assignee', align: 'center', value: 'task_assignee'
                            },
                            { text: 'Story Point', align: 'center', value: 'task_point' },
                            { text: 'Userstory', align: 'center', value: 'userstory.userstory_desc' },
                            // { text: 'Type', width:"10%", align: 'center', value: 'tasktype.type' },
                            { text: 'Start Date', align: 'center', value: 'project_status_date' },
                            { text: 'End Date',  align: 'center', value: 'project_end_date' },
                            { text: 'Status',  align: 'center', value: 'status.status_name' },
                            { text: 'Actions',  align: 'center', value: 'actions', sortable: false },
                        ],
                tasks: [],
                isLoading: true,
                isEditVisible: false,
                isDeleteVisible: false,
                project_id: this.$route.params.id,
                expand:true,
                cardWidth:'col-md-11',
                isTaskCommentVisible: false,
                taskid: '',
                events: [],
                input: null,
                nonce: 0
            }
        },
        created() {
            Task.allUserTask(tasks => this.tasks = tasks, this.project_id);
            Task.editaccess(editaccess => this.isEditVisible = editaccess); 
            Task.deleteaccess(deleteaccess => this.isDeleteVisible = deleteaccess); 
        },
        methods: {
            onShowComments(id) {
                this.isTaskCommentVisible = !this.isTaskCommentVisible;
                this.taskid = id;
            },
            showAddTask(id, point) {
                this.$emit('showaddtask', id, point)
            },
            deleteProject(id) {
                this.$emit('deleteproject', id)
            }
        },
        mounted() {
            console.log('Project Grid Mounted.')
        },
        watch: {
            tasks(){
                this.isLoading = false;    
            }
        },
        computed: {
            timeline () {
              return this.events.slice().reverse()
            }
        },
    }
</script>
