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
            :items="programs"
            :loading="isLoading"
            :search="search"
            :rows-per-page-items='[10, 20, 40, 80]'
            class="elevation-1"
        >
            <v-progress-linear v-show="isLoading" v-slot:progress color="blue" indeterminate></v-progress-linear>
            <template v-slot:items="props">
                <td>{{ props.item.program_name }}</td>
                <td class="text-xs-right">{{ props.item.program_desc }}</td>
                <td class="text-xs-right">{{ props.item.program_start_date }}</td>
                <td class="text-xs-right">{{ props.item.program_end_date }}</td>
                <td class="text-xs-right">
                    <h5><b-badge pill variant="info">{{ props.item.user[0].first_name}} {{props.item.user[0].last_name }} </b-badge></h5>
                </td>
                <td class="justify-center layout px-0 tdaction" style="padding:7px 24px!important">
                    <button v-if="isEditVisible" class="btn btn-sm btn-primary" @click="showEditProgram(props.item.id)"><font-awesome-icon icon="edit" ></font-awesome-icon></button>
                    <button v-if="isDeleteVisible" class="btn btn-sm btn-danger" @click="deleteProgram(props.item.id)"><font-awesome-icon icon="trash" ></font-awesome-icon></button>
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
    import Program from '../../models/Program';
    export default {
        components: {
            
        },
        prop: ['fetchPrograms'],
        data() {
            return{
                search: '',
                programs: [],
                headers:[
                            { text: 'Name', align: 'center', value: 'program_name'
                            },
                            { text: 'Description', align: 'center', value: 'program_desc' },
                            { text: 'Start Date', align: 'center', value: 'program_start_date' },
                            { text: 'End Date', align: 'center', value: 'program_end_date' },
                            { text: 'Managers', align: 'center', value: 'program_manager' },
                            { text: 'Actions', align: 'center', value: 'actions', sortable: false }
                        ],
                programs: [],
                isLoading: true,
                isEditVisible: false,
                isDeleteVisible: false,
            }
        },
        created() {
            Program.all(programs => this.programs = programs);
            Program.editaccess(editaccess => this.isEditVisible = editaccess); 
            Program.deleteaccess(deleteaccess => this.isDeleteVisible = deleteaccess); 
        },
        methods: {
            showEditProgram(id) {
                this.$emit('showeditprogram', id)
            },
            deleteProgram(id) {
                this.$emit('deleteprogram', id)
            }
        },
        mounted() {
            console.log('Program Grid Mounted.')
        },
        watch: {
            programs(){
                this.isLoading = false;    
            }
        }
    }
</script>
