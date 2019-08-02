<template>
    <div class="col-md-6">
        <div class="card card-default shadow-sm">
            <div class="card-header">Add Tasks</div>

            <div class="card-body">
                <v-app id="editTask" class="wpsform">
                    <v-form v-model="valid" enctype="multipart/form-data" @submit.prevent="onSubmit">
                        <v-container grid-list-md text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs12 md12>
                                    <v-card color="white">
                                        <div class="v-input v-text-field v-input--is-label-active theme--light">
                                            <div class="v-input__control">
                                                <div class="v-input__slot">
                                                    <div class="v-text-field__slot multiselectwps">
                                                        <multiselect @input="onChangeScope" v-model="form.scope" :options="scopes" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="Select CR" label="crd_title" track-by="id" :preselect-first="false" >
                                                            <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                        </multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </v-card>
                                </v-flex>
                                <v-flex xs12 md12>
                                    <v-card color="white">
                                        <div class="v-input v-text-field v-input--is-label-active theme--light">
                                        <div class="v-input__control">
                                            <div class="v-input__slot">
                                                <div class="v-text-field__slot multiselectwps">
                                                    <multiselect v-model="form.userstory" :options="userstories" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="Select Userstory" label="userstory_desc" track-by="id" :preselect-first="false" >
                                                        <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                    </multiselect>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    </v-card>
                                </v-flex>
                                <v-flex xs12 md12>
                                    <v-card color="white">
                                        <div class="v-input v-text-field v-input--is-label-active v-input--is-dirty theme--light">
                                        <div class="v-input__control">
                                            <div class="v-input__slot">
                                                <div class="v-text-field__slot">
                                                    <label for="file" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Upload WBS</label>
                                                    <input id="file" type="file" ref="file" @change="onChangeFileUpload">
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    </v-card>
                                </v-flex>
                                <v-flex xs12 md12 class="uploadBtnDiv">
                                    <v-card color="white">
                                        <v-btn v-if="isUploadVisible" :loading="uploadwbs" color="blue-grey" class="white--text" @click="loader = 'uploadwbs'" type="submit">
                                        Upload
                                        <v-icon right dark>cloud_upload</v-icon>
                                        </v-btn>
                                        <v-btn color="info" href="/download/WBS_Sample_Template.xlsx"> WBS Template</v-btn>
                                        <v-btn @click="closeWBSForm"> Close Form</v-btn>
                                    </v-card>
                                </v-flex>                                
                            </v-layout>
                        </v-container> 
                    </v-form>
                </v-app>
            </div>
        </div>
    

    <v-dialog v-model="dialog" width="700">
        <v-card>
            <v-card-title class="headline grey lighten-2" primary-title>
                WBS Excel Errors
            </v-card-title>
  
            <v-card-text> 
                <div><p>Please check and correct the following errors detected while rendering the excel.</p></div>
                <div v-html="this.wbserrors"></div>
            </v-card-text>
  
            <v-divider></v-divider>
  
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" flat @click="dialog = false">Close</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</div>
          
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import Scope from '../../models/Scope';
    import TaskGrid from './TaskGrid.vue';
    export default {
        components: {
            Multiselect,TaskGrid
        },
        data() {
            return {
                form: new Form({
                    userstory: '',
                    scope:'',
                    file: '',
                    errors:'',
                    project_id: this.$route.params.id,
                    fileName:'',
                }),
                cardWidth: 'col-md-6',
                isAddVisible: false,
                valid: false,
                loader:null,
                uploadwbs: false,  
                userstories: [],
                scopes:[],
                isUploadVisible:false,
                wbserrors:'',
                dialog:false,
                latestTasks:0,
            }
        },
        created() {
            Scope.allProjectScope(scope => this.scopes = scope, this.form.project_id);
        },
        methods: {
            onChangeScope(scope)
            {
                Scope.allScopeUserstories(userstories => this.userstories = userstories, this.form.scope.id); 
            },
            onChangeFileUpload(){
                this.form.file = this.$refs.file.files[0];
                this.isUploadVisible = true;
            },
            onSubmit() {
                let formData = new FormData();
                formData.append('file', this.form.file);
                formData.append('userstory', this.form.userstory.id);
                formData.append('scope', this.form.scope.id);
                formData.append('project', this.form.project_id);
                formData.append('errors', this.form.errors);
  
                axios.post('/task/wps/'+this.form.project_id+'/store',
                    formData,{headers: {'Content-Type': 'multipart/form-data'}})
                .then((data) => {
                    this.$emit('wbsuploaded');
                    this.form = [];
                })
                .catch((error) => {
                    this.wbserrors = error.response.data.message;
                    this.dialog = true;
                });
               
            },   
            closeWBSForm() {
                this.$emit('closeWBSForm');
            },
        },
        mounted() {
            console.log('Task mounted.')
        },
        watch: {
            loader () {
              const l = this.loader
              this[l] = !this[l]

              setTimeout(() => (this[l] = false), 3000)

              this.loader = null
            }
          }   
    }
</script>

