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
            :items="scopes"
            :loading="isLoading"
            :search="search"
            :rows-per-page-items='[10, 20, 40, 80]'
            class="elevation-1"
        >
            <v-progress-linear v-show="isLoading" v-slot:progress color="blue" indeterminate></v-progress-linear>
            <template v-slot:items="props">
                <td>
                    <v-btn flat small color="primary"  @click="showScopeComments(props.item.id)">{{ props.item.crd_id }}</v-btn>
                </td>
                <td class="text-xs-right">{{ props.item.crd_title }}</td>
                <td class="text-xs-right">{{ props.item.crd_desc }}</td>
            <td class="text-xs-right"><h5><b-badge pill variant="info">{{ props.item.status.status_name }}</b-badge></h5></td>
                <td class="justify-center layout px-0 tdaction" style="padding:7px 24px!important">
                    <button v-if="isEditVisible" class="btn btn-sm btn-primary" @click="showEditScope(props.item.id)"><font-awesome-icon icon="edit" ></font-awesome-icon></button>
                    <button v-if="isDeleteVisible" class="btn btn-sm btn-danger" @click="deleteScope(props.item.id)"><font-awesome-icon icon="trash" ></font-awesome-icon></button>
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
    import Scope from '../../models/Scope';
    export default {
        components: {
            Scope
        },
        prop: ['fetchScope'],
        data() {
            return{
                search: '',
                scopes: [],
                headers:[
                            { text: 'CRD', align: 'center', value: 'crd_id'
                            },
                            { text: 'Title', align: 'center', value: 'crd_title' },
                            { text: 'Description', align: 'center', value: 'crd_desc' },
                            { text: 'Status', align: 'center', value: 'status.status_name' },
                            { text: 'Actions', align: 'center', value: 'actions', sortable: false }
                        ],
                scope: [],
                isLoading: true,
                isEditVisible: false,
                isDeleteVisible: false,
            }
        },
        created() {
            Scope.all(scope => this.scopes = scope);
            Scope.editaccess(editaccess => this.isEditVisible = editaccess); 
            Scope.deleteaccess(deleteaccess => this.isDeleteVisible = deleteaccess); 
        },
        methods: {
            showEditScope(id) {
                this.$emit('showeditscope', id)
            },
            deleteScope(id) {
                this.$emit('deletescope', id)
            },
            showScopeComments(id){
                this.$emit('showscopecomments', id)
            }
        },
        mounted() {
            console.log('Scope Grid Mounted.')
        },
        watch: {
            scopes(){
                this.isLoading = false;    
            }
        }
    }
</script>
