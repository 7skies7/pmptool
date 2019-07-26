<template>
<div id="app">
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
            :expand="expand"
            :rows-per-page-items='[10, 20, 40, 80]'
            class="elevation-1"
        >
            <v-progress-linear v-show="isLoading" v-slot:progress color="blue" indeterminate></v-progress-linear>
            <template v-slot:items="props">
                <tr>
                    <td @click="props.expanded = !props.expanded">
                        <v-btn  flat small color="primary">{{ props.item.task_desc }}</v-btn>
                    </td>
                    <td class="text-xs-center">
                        <!-- <v-btn v-if="props.item.priority" flat icon  small> -->
                            <v-icon v-if="props.item.priority" :color="props.item.priority.priority_type" title="props.item.priority.priority_type">star</v-icon>
                        <!-- </v-btn> -->
                    </td>
                    <td class="text-xs-center">
                        <v-avatar color="#EF4667" size="35" v-if="props.item.assignee">
                            <span class="white--text headlinesmal" :title="props.item.assignee.first_name+' '+props.item.assignee.last_name">{{ props.item.assignee.first_name[0]}}{{ props.item.assignee.last_name[0]}}</span>
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
                    <!-- <td class="text-xs-left">{{ props.item.project_budget }}</td> -->
                    <td class="justify-center layout px-0 smallbtn">
                        <v-btn v-if="isEditVisible" @click="showEditTask(props.item)" color="primary" fab depressed small dark><v-icon>edit</v-icon></v-btn>
                        <v-btn v-if="isDeleteVisible" @click="deleteProject(props.item.id)" color="error" fab depressed small dark><v-icon>delete</v-icon></v-btn>
                        <v-btn v-if="addAccess" @click="showAddTask(props.item.id,props.item.task_point)" color="success" fab depressed small dark><v-icon>add</v-icon></v-btn>
                    </td>
                </tr>
            </template>
            <template v-slot:expand="props" >
                <subtask-grid :editAccess="isEditVisible" :deleteAccess="isDeleteVisible" :taskid="props.item.id" :key="props.item.id" @showedittask="showEditTask"></subtask-grid>
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

</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';
    import Task from '../../models/Task';
    import SubtaskGrid from './SubtaskGrid';

    export default {
        components: {
            SubtaskGrid
        },
        props: ['addAccess'],
        data() {
            return{
                search: '',
                projects: [],
                headers:[
                            { text: 'Name', width:"15%", align: 'center', value: 'task_desc'
                            },
                            { text: 'Priority', width:"10%", align: 'center', value: 'priority.priority_type' },
                            { text: 'Assignee', width:"10%", align: 'center', value: 'assignee.first_name'
                            },
                            { text: 'Story Point', width:"10%", align: 'center', value: 'task_point' },
                            { text: 'Userstory', width:"10%", align: 'center', value: 'userstory.userstory_desc' },
                            // { text: 'Type', width:"10%", align: 'center', value: 'tasktype.type' },
                            { text: 'Start Date', width:"10%", align: 'center', value: 'project_status_date' },
                            { text: 'End Date', width:"10%", align: 'center', value: 'project_end_date' },
                            { text: 'Status', width:"10%", align: 'center', value: 'status.status_name' },
                            { text: 'Actions', width:"15%", align: 'center', value: 'actions', sortable: false },
                        ],
                tasks: [],
                isLoading: true,
                isEditVisible: false,
                isDeleteVisible: false,
                project_id: this.$route.params.id,
                expand:true,
            }
        },
        created() {
            Task.all(tasks => this.tasks = tasks, this.project_id);
            Task.editaccess(editaccess => this.isEditVisible = editaccess); 
            Task.deleteaccess(deleteaccess => this.isDeleteVisible = deleteaccess); 
        },
        methods: {
            showEditTask(task) {
                this.$emit('showedittask', task)
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
        }
    }
</script>
