<template>
    <div class="col-md-6">
        <div class="card card-default shadow-sm">
            <div class="card-header">CRD Comments</div>
            <div class="card-body">
                <form @submit.prevent="onSubmit" @keydown="form.errors.clear()" enctype="multipart/form-data"> 
                    <v-app id="inspire">
                        <v-container style="max-width: 600px;">
                            <v-timeline dense clipped>
                                <v-timeline-item fill-dot class="white--text mb-5" color="#EF4667" large>
                                    <template v-slot:icon> <span><v-icon medium dark>attach_file</v-icon></span> </template>
                                    <div class="v-input inputupload v-input__control">
                                        <div class="v-input__slot">
                                            <input type="file" id="file" ref="file" class="hidden" @change="onFileChange" />
                                        </div>
                                    </div>
                                    <v-text-field v-model="form.comment" hide-details flat label="Leave a comment..." solo @keydown.enter="comment" >
                                        <template v-slot:append>
                                            <v-btn class="mx-0" depressed type="submit">Post</v-btn>
                                        </template>
                                    </v-text-field>
                                </v-timeline-item>
                          
                                <v-slide-x-transition group>
                                    <v-timeline-item v-for="event in timeline" :key="event.id" class="mb-3" color="pink" small>
                                    <v-layout justify-space-between>
                                      <v-flex xs7 v-text="event.text"></v-flex>
                                      <v-flex xs5 text-xs-right v-text="event.time"></v-flex>
                                    </v-layout>
                                  </v-timeline-item>
                                </v-slide-x-transition>
                          
                                <v-timeline-item class="mb-3" hide-dot>
                                    <v-btn class="mx-0" color="white">Approve CRD</v-btn>
                                </v-timeline-item>
                          
                                <v-timeline-item class="mb-3" color="grey" icon-color="grey lighten-2" small
                                >
                                    <v-layout justify-space-between>
                                        <v-flex xs7>This order was archived.</v-flex>
                                        <v-flex xs5 text-xs-right>15:26 EDT</v-flex>
                                    </v-layout>
                                </v-timeline-item>
                          
                                <v-timeline-item class="mb-3" small >
                                    <v-layout justify-space-between>
                                        <v-flex xs7>
                                            Digital Downloads fulfilled 1 item.
                                        </v-flex>
                                        <v-flex xs5 text-xs-right>15:25 EDT</v-flex>
                                    </v-layout>
                                </v-timeline-item>
                            </v-timeline>
                        </v-container>
                    </v-app>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
            
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
                status: [],
                events: [],
                input: null,
                nonce: 0
            }
        },
        methods: {
            onSubmit() {
                this.form.post('/scope/comments/'+this.crdid+'/store')
               .then(scope => this.$emit('completed', scope));
               // axios.post('/image/store',{image: this.image}).then(response => {
               //     console.log(response);
               //  });
            },                                                  
            closeAddForm() {
                this.$emit('closeAddForm');
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
                const time = (new Date()).toTimeString()
                this.events.push({
                    id: this.nonce++,
                    text: this.input,
                    time: time.replace(/:\d{2}\sGMT-\d{4}\s\((.*)\)/, (match, contents, offset) => {
                    return ` ${contents.split(' ').map(v => v.charAt(0)).join('')}`
                    })
                })

              this.input = null
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

