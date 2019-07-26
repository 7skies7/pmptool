<template>
<div id="app">
    <v-dialog v-model="isLoading" hide-overlay persistent width="300">
        <v-card color="primary" dark>
            <v-card-text>
                Please stand by
                <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
          </v-card-text>
        </v-card>
      </v-dialog>   
    <v-app id="inspire">
    <div class="scopeexpan">
        <v-container fluid grid-list-md>
            <v-card-title>
                <v-spacer></v-spacer>
                <v-spacer></v-spacer>
                <v-spacer></v-spacer>
                <v-spacer></v-spacer>
                <v-text-field
                  v-model="scopesearch"
                  append-icon="search"
                  label="Search"
                  single-line
                  hide-details
                ></v-text-field>
            </v-card-title>
            <v-data-iterator :items="scopes" :search="scopesearch" :loading="isLoading" :rows-per-page-items="rowsPerPageItems" :pagination.sync="pagination" content-tag="v-layout" row wrap>
                <template v-slot:item="props">
                <v-expansion-panel focusable>
                    <v-expansion-panel-content>
                        <template v-slot:header>
                            <!-- <div> -->
                                <table class="table accordtable">
                                    <tr>
                                        <td width="30%">
                                            {{ props.item.crd_id }}
                                        </td>
                                        <td width="50%">{{ props.item.crd_title }}</td>
                                        <td width="20%" v-if="props.item.status" class="text-xs-left"><h5><b-badge pill variant="info">{{ props.item.status.status_name }}</b-badge></h5></td>
                                        <td width="20%" v-else class="text-xs-left">-</td>
                                    </tr>

                                </table>
                            <!-- </div> -->
                        </template>
                        <v-card>
                            <v-card-text>
                            <table class="table accordtable">
                                <tr>
                                    <td width="60%">{{ props.item.crd_desc }}</td>
                                    <td width="40%">
                                        <v-btn v-if="isEditVisible" small @click="showEditScope(props.item.id)">Edit</v-btn>
                                        <v-btn v-if="isDeleteVisible" small @click="deleteScope(props.item.id)">Delete</v-btn>
                                        <v-btn v-if="isAddStoryVisible" small  @click="showAddUserstory(props.item.id)">Add Story</v-btn>
                                    </td>
                                </tr>
                            </table>
                            <userstory-grid :crid="props.item.id" :isEditVisible="isEditUserstoryVisible" :isDeleteVisible="isDeleteUserstoryVisible" :key="props.item.id" @showedituserstory="showEditUserstory" @deleteuserstory="deleteUserstory" @showuserstorycomments="showUserstoryComments"></userstory-grid>
                        </v-card-text>
                        </v-card>
                    </v-expansion-panel-content>
                </v-expansion-panel>
                </template>
            </v-data-iterator>
        </v-container>
        <!-- This is grid old -->
        <!-- <v-card-title>
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
        </v-card-title> -->
        <!-- <v-data-table
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
                <td class="text-xs-left">{{ props.item.userstory_id }}</td>
                <td class="text-xs-left">{{ props.item.crd_desc }}</td>
                <td v-if="props.item.approveddocument" class="text-xs-left">{{ props.item.approveddocument.file_name }}</td>
                <td v-else class="text-xs-left">-</td>
                <td v-if="props.item.status" class="text-xs-left"><h5><b-badge pill variant="info">{{ props.item.status.status_name }}</b-badge></h5></td>
                <td v-else class="text-xs-left">-</td>
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
       </v-data-table> -->
    </div>
  </v-app>
</div>

</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';
    import UserstoryGrid from './UserstoryGrid.vue';
    import Scope from '../../models/Scope';
    import Userstory from '../../models/Userstory';
    export default {
        components: {
            Scope, UserstoryGrid, Userstory
        },
        prop: ['fetchScope'],
        data() {
            return{
                scopesearch: '',
                panelIndex:1,
                scopes: [],
                headers:[
                            { text: 'CRD', align: 'center', value: 'crd_id'
                            },
                            { text: 'Title', align: 'center', value: 'crd_title' },
                            { text: 'Description', align: 'center', value: 'crd_desc' },
                            { text: 'Document', align: 'center', value: 'approveddocument.file_name' },
                            { text: 'Status', align: 'center', value: 'status.status_name' },
                            { text: 'Actions', align: 'center', value: 'actions', sortable: false }
                        ],
                scope: [],
                isLoading: true,
                isEditVisible: false,
                isDeleteVisible: false,
                rowsPerPageItems: [6, 12, 18],
                pagination: {
                    rowsPerPage: 6
                },
                isAddStoryVisible: false,
                isEditUserstoryVisible:false,
                isDeleteUserstoryVisible:false,
                project_id: this.$route.params.id,
            }
        },
        created() {
            Scope.all(scope => this.scopes = scope, this.project_id);
            Scope.editaccess(editaccess => this.isEditVisible = editaccess); 
            Scope.deleteaccess(deleteaccess => this.isDeleteVisible = deleteaccess); 
            Userstory.editaccess(editaccess => this.isEditUserstoryVisible = editaccess); 
            Userstory.deleteaccess(deleteaccess => this.isDeleteUserstoryVisible = deleteaccess); 
        },
        methods: {
            showEditScope(id) {
                this.$emit('showeditscope', id)
            },
            deleteScope(id) {
                this.$emit('deletescope', id)
            },
            showEditUserstory(id) {
                this.$emit('showedituserstory', id)
            },
            deleteUserstory(id) {
                this.$emit('deleteuserstory', id)
            },
            deleteScope(id) {
                this.$emit('deletescope', id)
            },
            showAddUserstory(id) {
                this.$emit('showadduserstory', id)
            },
            showUserstoryComments(id, cr_id){
                this.$emit('showuserstorycomments', id, cr_id)
            }
        },
        mounted() {
            console.log('Scope Grid Mounted.');
            Userstory.addaccess(addaccess => this.isAddStoryVisible = addaccess); 
        },
        watch: {
            scopes(){
                this.isLoading = false;    
            },
            panelIndex(){
                // alert(this.panelIndex);
            }
        }
    }
</script>
