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
            :items="users"
            :loading="isLoading"
            :search="search"
            :rows-per-page-items='[10, 20, 40, 80]'
            class="elevation-1"
        >
            <v-progress-linear v-show="isLoading" v-slot:progress color="blue" indeterminate></v-progress-linear>
            <template v-slot:items="props">
                <td class="text-xs-center">{{ props.item.first_name }} {{ props.item.last_name }}</td>
                <td class="text-xs-left">{{ props.item.email }}</td>
                <td class="text-xs-center">
                    <h5 v-if="(props.item.roles).length > 0" v-for="role in props.item.roles"><b-badge pill variant="info">{{ role.role_title }}</b-badge></h5>
                    <h5 v-else>-</h5>
                </td>
                <td class="text-xs-center">
                    <h5 v-if="(props.item.companies).length > 0" v-for="company in props.item.companies"><b-badge pill variant="info">{{ company.company_name }}</b-badge></h5>
                    <h5 v-else>-</h5>
                </td>
                <td class="justify-center layout px-0 smallbtn">
                        <v-btn v-if="isEditVisible" @click="showEditUser(props.item)" color="primary" fab depressed small dark><v-icon>edit</v-icon></v-btn>
                        <v-btn v-if="isDeleteVisible" @click="deleteUser(props.item.id)" color="error" fab depressed small dark><v-icon>delete</v-icon></v-btn>
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
    import User from '../../models/User';
    export default {
        components: {
            
        },
        prop: ['fetchUsers'],
        data() {
            return{
                search: '',
                users: [],
                headers:[
                            { text: 'Name', align: 'center', value: 'first_name'
                            ,width: '15%'},
                            { text: 'Email', align: 'center', value: 'email',width: '25%' },
                            { text: 'Role', align: 'center', value: 'roles.role_title',width: '10%' },
                            { text: 'Organization', align: 'center', value: 'companies.company_name',width: '10%' },
                            // { text: 'Managers', align: 'center', value: 'user[0].first_name',width: '25%' },
                            { text: 'Actions', align: 'center', value: 'actions', sortable: false,width: '15%' }
                        ],
                programs: [],
                isLoading: true,
                isEditVisible: false,
                isDeleteVisible: false,
            }
        },
        created() {
            User.all(users => this.users = users);
            User.editaccess(editaccess => this.isEditVisible = editaccess); 
            User.deleteaccess(deleteaccess => this.isDeleteVisible = deleteaccess); 
        },
        methods: {
            showEditUser(user) {
                this.$emit('showedituser', user)
            },
            deleteUser(id) {
                this.$emit('deleteuser', id)
            }
        },
        mounted() {
            console.log('User Grid Mounted.')
        },
        watch: {
            users(){
                this.isLoading = false;    
            }
        }
    }
</script>
