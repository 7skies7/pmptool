<template>
    <div class="col-md-12">
        <div class="card card-default shadow-sm">
            <!-- <div class="card-header">{{ story.userstory_desc }}</div> -->
            <v-progress-linear v-show="isLoading" v-slot:progress color="blue" indeterminate class="nomarginprogress" height="4px"></v-progress-linear>
            <div class="card-body">
                <v-app id="editTask">
                    <v-container style="max-width: 600px;">
                        <v-timeline dense clipped>
                            <v-timeline-item class="mb-4" hide-dot>
                                <span>Discussions</span>
                            </v-timeline-item>
                            <v-slide-x-transition group>
                                <v-timeline-item v-for="event in timeline" :key="event.id" class="mb-3" color="grey" small>
                                    <v-layout justify-space-between>
                                    <v-flex xs12 >
                                        <v-card class="singleComBlock" style="background:#fff!important">
                                            <div>
                                                <span v-if="event.text">{{ event.text }}</span>
                                            </div>
                                            <div>
                                                <v-chip class="white--text ml-0" color="info" label small>{{event.username}}</v-chip>
                                                <v-chip class="white--text ml-0" color="info" label small>Hours: {{event.userstory_point}}</v-chip>
                                                <v-btn :href="'/download/2/'+userstoryid+'!!$'+event.filename" class="ml-0 npuppercase" flat small> <v-icon>attach_file</v-icon>{{event.filename}}</v-btn>
                                            </div>
                                        </v-card>                         
                                    </v-flex>
                                    <v-flex xs5 text-xs-center>
                                        {{ event.time}}
                                        <div v-if="event.userstory_point && approve">
                                        <v-icon v-if="event.approved" color="#009688" class="approveicon" @click="onApprove(event.id)">check_circle</v-icon>
                                        <v-icon v-else class="approveicon" @click="onApprove(event.id)">check_circle</v-icon>
                                        </div>
                                    </v-flex>
                                </v-layout>
                                <v-layout justify-space-between style="display:none">
                                    <v-flex xs7 >
                                        <v-chip class="white--text ml-0" color="purple" label small>
                                            {{event.username}}
                                        </v-chip>
                                        <!-- {{ event.text }} -->
                                        <v-badge v-if="event.userstory_point">
                                            <template v-slot:badge>
                                              <span>{{ event.userstory_point }}</span>
                                            </template>
                                            {{ event.text }}
                                        </v-badge>
                                        <span v-else>{{ event.text }}</span>
                                        <!-- <span v-if="event.filename"><v-icon>attach_file</v-icon> {{ event.filename}}</span> -->
                                        <v-chip v-if="event.filename" class="black--text ml-0" label small>
                                            <v-icon>attach_file</v-icon> {{ event.filename}}
                                        </v-chip>                            
                                    </v-flex>
                                    <v-flex xs5 text-xs-right>
                                        {{ event.time}}
                                        <div v-if="event.userstory_point && approve">
                                        <v-icon v-if="event.approved" color="#009688" class="approveicon" @click="onApprove(event.id)">check_circle</v-icon>
                                        <v-icon v-else class="approveicon" @click="onApprove(event.id)">check_circle</v-icon>
                                        </div>
                                    </v-flex>
                                </v-layout>
                              </v-timeline-item>
                            </v-slide-x-transition> 
                            <form v-if="story.userstory_status != 2" @submit.prevent="onSubmit" @keydown="form.errors.clear()" enctype="multipart/form-data">
                            <v-timeline-item fill-dot class="white--text mb-0" color="#EF4667" large>
                                <template v-slot:icon> <span><v-icon medium dark>attach_file</v-icon></span> </template>
                                <div class="v-input inputupload v-input__control">
                                    <div class="v-input__slot">
                                        <input type="file" id="file" ref="file" class="hidden" @change="onFileChange" />
<!--                                         <input type="number" v-model="form.userstory_point" id="storypoint" placeholder="Story Point" /> -->
                                         <span class="invalid-feedback" role="alert" v-if="formApprove.errors.has('file')" v-text="formApprove.errors.get('file')"></span>
                                    </div>
                                </div>
                                <v-card color="white">
                                    <v-text-field type="number" v-model="form.userstory_point" label="Task Point" placeholder="Task Point" :rules="rules.userstory_point" :messages="form.errors.get('userstory_point')"></v-text-field>
                                </v-card>
                                <v-textarea rows="3" v-model="form.comment" hide-details flat label="Leave a comment..." solo @keydown.enter="comment" >
                                    
                                </v-textarea>
                                <v-btn class="mx-0" depressed type="submit">Post</v-btn>
                            </v-timeline-item>
                            </form>
<!--                             <form @submit.prevent="onApprove" @keydown="form.errors.clear()" enctype="multipart/form-data"> 
                            <v-timeline-item class="mb-0" hide-dot>
                                <span>Select CRD Document to Approve</span>
                            </v-timeline-item>
                            <v-timeline-item class="approveselect" hide-dot>
                                <multiselect v-model="formApprove.approved_document" :options="documents" :searchable="false" :close-on-select="false" :show-labels="false" track-by="id" label="file_name" placeholder="Pick a value"></multiselect>
                                <v-btn  class="mx-0" color="white" type="submit">Approve CRD</v-btn>
                            </v-timeline-item>
                            </form> -->
                        </v-timeline>
                    </v-container>
                    <!-- <v-btn xs5 text-md-center depressed small @click="closeCommentForm">CLOSE</v-btn> -->
                </v-app>
            </div>
        </div>
    </div>
</template>

<script>
    import Userstory from '../../models/Userstory';
    import Multiselect from 'vue-multiselect';
    export default {
        components: {
            Userstory, Multiselect      
        },
        props: ['userstoryid', 'scopeid'],
        data() {
            return {
                form: new Form({
                    file: '',
                    comment: '',
                    userstory_point: '',
                    errors:'',
                    project_id: this.$route.params.id,
                    userstory_id: this.userstoryid,
                    cr_id: this.scopeid,
                    fileName: '',
                }),
                formApprove: new Form({
                    project_id: this.$route.params.id,
                    userstoryid: this.userstoryid,
                    approved_document: '',
                    errors:''
                }),
                status: [],
                events: [],
                input: null,
                nonce: 0,
                isLoading:true,
                documents: [],
                story:[],
                rules: {
                    userstory_point: [val => val < 21  || `Userstory point should be between 1 to 20`,val => val >= 1  || `Userstory point should be between 1 to 20`],
                },
                approve:'',
            }
        },
        created() {

            Userstory.fetchUserstory(userstory => this.story = userstory, this.userstoryid);
            Userstory.allComments((events) => {
                this.events = events.comments;
                this.approve = events.approve;  
            }, this.userstoryid);
            // Userstory.fetchDocuments(documents => this.documents = documents, this.userstoryid);
            // Userstory.fetchApprovedDocument(document => this.formApprove.approved_document = document, this.userstoryid);
            this.isLoading = false;
        },
        methods: {
            onSubmit() {
                this.form.post('/userstory/comments/'+this.userstoryid+'/store')
               .then(userstory => this.$emit('completed', userstory));
               console.log(this.formApprove.errors);
               
            },                        
            onApproveL(id) {
                this.formApprove.post('/userstory/comments/'+this.userstoryid+'/approvedocument')
               .then(scope => this.$emit('completed', scope));
            },              
            onApprove(id) {
                if(id != '')
                {
                    axios.post('/userstory/comments/'+this.userstoryid+'/approvedocument', {commentid: id})
                  .then(userstory => this.$emit('approvecompleted', userstory));    
                }
                
            },                          
            closeCommentForm() {
                this.$emit('closeCommentForm');
            },
            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createFile(files[0]);
                this.form.fileName = files[0]['name'];
            },
            createFile(file) {
                let reader = new FileReader();
                let vm = this;
                let file_arr = '';
                reader.onload = (e) => {
                    vm.form.file = e.target.result;
                };
                
                reader.readAsDataURL(file);
            },
            comment () {
              //   const time = (new Date()).toTimeString()
              //   this.events.push({
              //       id: this.nonce++,
              //       text: this.form.comment,
              //       time: time.replace(/:\d{2}\sGMT-\d{4}\s\((.*)\)/, (match, contents, offset) => {
              //       return ` ${contents.split(' ').map(v => v.charAt(0)).join('')}`
              //       })
              //   })

              // this.input = null
            }       
        },
        computed: {
            timeline () {
              return this.events.slice().reverse()
            }
        },
        mounted() {
                        
        }
    }
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

