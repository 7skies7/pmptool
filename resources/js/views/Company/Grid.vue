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
                    <v-avatar color="#EF4667" size="35" v-if="(props.item.managers).length > 0" v-for="manager in props.item.managers" v-bind:key="manager.id">
                        <span class="white--text headlinesmal" :title="manager.first_name+' '+manager.last_name">{{ manager.first_name[0]}}{{ manager.last_name[0]}}</span>
                    </v-avatar>

                </td>
                <td class="justify-center layout px-0 smallbtn" style="padding:7px 24px!important">
                    <v-btn v-if="isEditVisible" @click="showEditCompany(props.item)" color="primary" fab depressed small dark><v-icon>edit</v-icon></v-btn>
                    <v-btn v-if="isDeleteVisible" @click="deleteCompany(props.item.id)" color="error" fab depressed small dark><v-icon>delete</v-icon></v-btn>
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
                            { text: 'Name', align: 'center', value: 'company_name',width: '15%'
                            },
                            { text: 'Description', align: 'center', value: 'company_desc', width: '40%' },
                            { text: 'Managers', align: 'center', value: 'user[0].first_name', width: '30%' },
                            { text: 'Actions', align: 'center', value: 'actions', sortable: false, width: '15%' }
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
            showEditCompany(company) {
                this.$emit('showeditcompany', company)
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
