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
            :items="companies"
            :loading="isLoading"
            :search="search"
            :rows-per-page-items='[10, 20, 40, 80]'>
            class="elevation-1"
        >
            <v-progress-linear v-show="isLoading" v-slot:progress color="blue" indeterminate></v-progress-linear>
            <template v-slot:items="props">
                <td class="text-xs-center">{{ props.item.company_name }}</td>
                <td class="text-xs-center">{{ props.item.company_desc }}</td>
                <td class="text-xs-center">
                    <!-- <h5><b-badge pill variant="info">{{ props.item.user[0].first_name}} {{props.item.user[0].last_name }} </b-badge></h5> -->
                    <v-avatar color="#EF4667" size="35">
                        <span class="white--text headlinesmal" :title="props.item.user[0].first_name+' '+props.item.user[0].last_name">{{ props.item.user[0].first_name[0]}}{{ props.item.user[0].last_name[0]}}</span>
                    </v-avatar>
                </td>
                <td class="justify-center layout px-0 tdaction" style="padding:7px 24px!important">
                    <button v-if="isEditVisible" class="btn btn-sm btn-primary" @click="showEditCompany(props.item.id)"><font-awesome-icon icon="edit" ></font-awesome-icon></button>
                    <button v-if="isDeleteVisible" class="btn btn-sm btn-danger" @click="deleteCompany(props.item.id)"><font-awesome-icon icon="trash" ></font-awesome-icon></button>
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
    import Company from '../../models/Company';
    export default {
        components: {
            
        },
        prop: ['fetchCompanies'],
        data() {
            return{
                search: '',
                companies: [],
                headers:[
                            { text: 'Name', align: 'center', value: 'company_name'
                            },
                            { text: 'Description', align: 'center', value: 'company_desc' },
                            { text: 'Managers', align: 'center', value: 'user[0].first_name' },
                            { text: 'Actions', align: 'center', value: 'actions', sortable: false }
                        ],
                companies: [],
                isLoading: true,
                isEditVisible: false,
                isDeleteVisible: false,
            }
        },
        created() {
            Company.all(companies => this.companies = companies)
            Company.editaccess(editaccess => this.isEditVisible = editaccess); 
            Company.deleteaccess(deleteaccess => this.isDeleteVisible = deleteaccess);                       

        },
        methods: {
            showEditCompany(id) {
                this.$emit('showeditcompany', id)
            },
            deleteCompany(id) {
                this.$emit('deletecompany', id)
            }
        },
        mounted() {
            console.log('Company Grid Mounted.')
        },
        watch: {
            companies(){
                this.isLoading = false;    
            }
        }
    }
</script>
