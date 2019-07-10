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
            :items="projects"
            :loading="isLoading"
            :search="search"
            :rows-per-page-items='[10, 20, 40, 80]'
            class="elevation-1"
        >
            <v-progress-linear v-show="isLoading" v-slot:progress color="blue" indeterminate></v-progress-linear>
            <template v-slot:items="props">
                <td>
                    <v-btn :href="'#/project/'+props.item.id+'/detail'" flat small color="primary">{{ props.item.project_name }}</v-btn>
                </td>
                <td class="text-xs-left">{{ props.item.project_desc }}</td>
                <td class="text-xs-left">{{ props.item.project_start_date }}</td>
                <td class="text-xs-left">{{ props.item.project_end_date }}</td>
                <td class="text-xs-center"><h5><b-badge pill variant="info">{{ props.item.status.status_name }}</b-badge></h5></td>
                <!-- <td class="text-xs-left">{{ props.item.project_budget }}</td> -->
                <td class="justify-center layout px-0 tdaction" style="padding:7px 24px!important">
                    <button v-if="isEditVisible" class="btn btn-sm btn-primary" @click="showEditProject(props.item.id)"><font-awesome-icon icon="edit" ></font-awesome-icon></button>
                    <button v-if="isDeleteVisible" class="btn btn-sm btn-danger" @click="deleteProject(props.item.id)"><font-awesome-icon icon="trash" ></font-awesome-icon></button>
                </td>
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
    import Project from '../../models/Project';
    export default {
        components: {
            
        },
        prop: ['fetchProjects'],
        data() {
            return{
                search: '',
                projects: [],
                headers:[
                            { text: 'Name', align: 'center', value: 'project_name'
                            },
                            { text: 'Description', align: 'center', value: 'project_desc' },
                            { text: 'Start Date', align: 'center', value: 'project_start_date' },
                            { text: 'End Date', align: 'center', value: 'project_end_date' },
                            { text: 'Status', align: 'center', value: 'status.status_name' },
                            // { text: 'Budget', align: 'center', value: 'project_budget' },
                            { text: 'Actions', align: 'center', value: 'actions', sortable: false }
                        ],
                projects: [],
                isLoading: true,
                isEditVisible: false,
                isDeleteVisible: false,
            }
        },
        created() {
            Project.all(projects => this.projects = projects);
            Project.editaccess(editaccess => this.isEditVisible = editaccess); 
            Project.deleteaccess(deleteaccess => this.isDeleteVisible = deleteaccess); 
        },
        methods: {
            showEditProject(id) {
                this.$emit('showeditproject', id)
            },
            deleteProject(id) {
                this.$emit('deleteproject', id)
            }
        },
        mounted() {
            console.log('Project Grid Mounted.')
        },
        watch: {
            projects(){
                this.isLoading = false;    
            }
        }
    }
</script>
