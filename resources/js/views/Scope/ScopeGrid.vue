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
            <v-card-title class="searchbar">
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
                                        <td width="20%">
                                            <v-list>
                                                <v-list-tile avatar>
                                                    <v-list-tile-content>
                                                        <v-list-tile-sub-title>CR</v-list-tile-sub-title>
                                                        <v-list-tile-title >{{ props.item.crd_id }}</v-list-tile-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                            </v-list>

                                        </td>
                                        <td width="45%">
                                            <v-list>
                                                <v-list-tile avatar>
                                                    <v-list-tile-content>
                                                        <v-list-tile-sub-title>Title</v-list-tile-sub-title>
                                                        <v-list-tile-title >{{ props.item.crd_title }}</v-list-tile-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                            </v-list>
                                        </td>
                                        <td width="20%" class="text-xs-left">
                                            <v-list>
                                                <v-list-tile avatar>
                                                    <v-list-tile-content v-if="props.item.status" >
                                                        <v-list-tile-sub-title>Status</v-list-tile-sub-title>
                                                        <v-list-tile-title ><h5><b-badge pill variant="info">{{ props.item.status.status_name }}</b-badge></h5></v-list-tile-title>
                                                    </v-list-tile-content>
                                                    <v-list-tile-content v-else>
                                                        <v-list-tile-title ><h5>-</h5></v-list-tile-title>

                                                    </v-list-tile-content>
                                                </v-list-tile>
                                            </v-list>
                                        </td>
                                        
                                        <td width="15%" style="background:#fff;" class="scopebtn">
                                            <v-btn v-if="isEditVisible" @click="showEditScope(props.item.id)" color="primary accordianbtn" fab depressed small dark><v-icon>edit</v-icon></v-btn>
                                            <v-btn v-if="isEditVisible" color="error accordianbtn" @click="deleteScope(props.item.id)" fab depressed small dark><v-icon>delete</v-icon></v-btn>
                                            <v-btn color="success accordianbtn" v-if="isAddStoryVisible" fab depressed small dark  @click="showAddUserstory(props.item.id)"><v-icon>add</v-icon></v-btn>
                                        </td>
                                    </tr>

                                </table>
                            <!-- </div> -->
                        </template>
                        <v-card>
                            <v-card-text>
                            <userstory-grid :crid="props.item.id" :isEditVisible="isEditUserstoryVisible" :isDeleteVisible="isDeleteUserstoryVisible" :key="props.item.id" @showedituserstory="showEditUserstory" @deleteuserstory="deleteUserstory" @showuserstorycomments="showUserstoryComments"></userstory-grid>
                        </v-card-text>
                        </v-card>
                    </v-expansion-panel-content>
                </v-expansion-panel>
                </template>
            </v-data-iterator>
        </v-container>
        
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
