<template>
    <div class="col-md-6">
        <div class="card card-default shadow-sm">
            <div class="card-header">CRD Comments</div>
            <v-progress-linear v-show="isLoading" v-slot:progress color="blue" indeterminate class="nomarginprogress" height="4px"></v-progress-linear>
            <div class="card-body">
                <v-app id="inspire">
                    <v-container style="max-width: 600px;">
                        <v-timeline dense clipped>
                            <form @submit.prevent="onSubmit" @keydown="form.errors.clear()" enctype="multipart/form-data"> 
                            <v-timeline-item fill-dot class="white--text mb-0" color="#EF4667" large>
                                <template v-slot:icon> <span><v-icon medium dark>attach_file</v-icon></span> </template>
                                <div class="v-input inputupload v-input__control">
                                    <div class="v-input__slot">
                                        <input type="file" id="file" ref="file" class="hidden" @change="onFileChange" />
                                         <span class="invalid-feedback" role="alert" v-if="formApprove.errors.has('file')" v-text="formApprove.errors.get('file')"></span>
                                    </div>
                                </div>
                                <v-text-field v-model="form.comment" hide-details flat label="Leave a comment..." solo @keydown.enter="comment" >
                                    <template v-slot:append>
                                        <v-btn class="mx-0" depressed type="submit">Post</v-btn>
                                    </template>
                                </v-text-field>
                            </v-timeline-item>
                            </form>
                            <form @submit.prevent="onApprove" @keydown="form.errors.clear()" enctype="multipart/form-data"> 
                            <v-timeline-item class="mb-0" hide-dot>
                                <span>Select CRD Document to Approve</span>
                            </v-timeline-item>
                            <v-timeline-item class="approveselect" hide-dot>
                                <multiselect v-model="formApprove.approved_document" :options="documents" :searchable="false" :close-on-select="false" :show-labels="false" track-by="id" label="file_name" placeholder="Pick a value"></multiselect>
                                <v-btn  class="mx-0" color="white" type="submit">Approve CRD</v-btn>
                            </v-timeline-item>
                            </form>
                            <v-timeline-item class="mb-4" hide-dot>
                                <span>Discussions</span>
                            </v-timeline-item>
                            <v-slide-x-transition group>
                                <v-timeline-item v-for="event in timeline" :key="event.id" class="mb-3" color="pink" small>
                                <v-layout justify-space-between>
                                    <v-flex xs7 >
                                        <v-chip class="white--text ml-0" color="purple" label small>
                                            {{event.username}}
                                        </v-chip>
                                        {{ event.text }}
                                        <span v-if="event.filename"><v-icon>attach_file</v-icon> {{ event.filename}}</span>
                                    </v-flex>
                                    <v-flex xs5 text-xs-right v-text="event.time"></v-flex>
                                </v-layout>
                              </v-timeline-item>
                            </v-slide-x-transition>
                        </v-timeline>
                    </v-container>
                    <v-btn xs5 text-md-center depressed small @click="closeCommentForm">CLOSE</v-btn>
                </v-app>
            </div>
        </div>
    </div>
</template>

<script>
    import Scope from '../../models/Scope';
    import Multiselect from 'vue-multiselect';
    export default {
        components: {
            Scope, Multiselect      
        },
        props: ['crdid'],
        data() {
            return {
                form: new Form({
                    file: '',
                    comment: '',
                    errors:'',
                    project_id: this.$route.params.id,
                    crdid: this.crdid,
                    fileName: '',
                }),
                formApprove: new Form({
                    project_id: this.$route.params.id,
                    crdid: this.crdid,
                    approved_document: '',
                    errors:''
                }),
                status: [],
                events: [],
                input: null,
                nonce: 0,
                isLoading:true,
                documents: [],
            }
        },
        created() {
            Scope.allComments(events => this.events = events, this.crdid);
            Scope.fetchDocuments(documents => this.documents = documents, this.crdid);
            Scope.fetchApprovedDocument(document => this.formApprove.approved_document = document, this.crdid);
            this.isLoading = false;
        },
        methods: {
            onSubmit() {
                this.form.post('/scope/comments/'+this.crdid+'/store')
               .then(scope => this.$emit('completed', scope));
               console.log(this.formApprove.errors);
               
            },                        
            onApprove() {
                this.formApprove.post('/scope/comments/'+this.crdid+'/approvedocument')
               .then(scope => this.$emit('completed', scope));
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

