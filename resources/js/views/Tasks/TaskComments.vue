<template>
    <div class="col-md-12">
        <div class="card card-default shadow-sm">
            <div class="card-header">Task Timeline</div>
            <v-progress-linear v-show="isLoading" v-slot:progress color="blue" indeterminate class="nomarginprogress" height="4px"></v-progress-linear>
            <div class="card-body">
                <v-app id="inspire">
                    <v-container>
                        <v-timeline dense clipped class="commentblock" v-if="timeline">
                            <v-slide-x-transition group>
                                <v-timeline-item v-for="event in timeline" :key="event.id" class="mb-3" color="grey" small>
                                <v-layout justify-space-between>
                                    <v-flex xs12 >
                                        <v-card class="singleComBlock" style="background:#fff!important">
                                            <div>
                                                <span v-if="event.task_hours">{{ event.text }}</span>
                                            </div>
                                            <div>
                                                <v-chip class="white--text ml-0" color="info" label small>{{event.username}}</v-chip>
                                                <v-chip class="white--text ml-0" color="info" label small>Hours: {{event.task_hours}}</v-chip>
                                                <v-chip class="white--text ml-0" color="info" label small>Progress:{{event.task_completion}}%</v-chip>
                                            </div>
                                        </v-card>                         
                                    </v-flex>
                                    <v-flex xs5 text-xs-center>
                                        {{ event.time}}
                                    </v-flex>
                                </v-layout>
                              </v-timeline-item>
                            </v-slide-x-transition> 
                        </v-timeline>
                        <v-timeline dense clipped v-if="isAddCommentForm && this.form.task_completion != 100">
                            <form @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                            <v-timeline-item fill-dot class="white--text mb-0" color="info" large>
                                <template v-slot:icon> <span><v-icon medium dark>comment</v-icon></span> </template>
                                <v-layout row wrap>
                                    <v-flex xs2>
                                        <v-text-field type="number" v-model="task_hrs" label="HH" placeholder="HH" box  :messages="form.errors.get('task_hours')" :rules="rules.hrsmax"></v-text-field>                                        
                                    </v-flex>
                                    <v-flex xs2>
                                        <v-text-field type="number" v-model="task_mins" label="MM" placeholder="MM" box :messages="form.errors.get('task_hours')" :rules="rules.minsmax"></v-text-field>
                                    </v-flex>
                                    <v-flex xs2>
                                        <v-text-field v-model="calcAvailableHours" label="Hrs Remain" placeholder="Hrs Available" box readonly="readonly"></v-text-field>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-card flat class="customcard">
                                            <v-slider v-model="form.task_completion" color="primary" label="Progress" hint="Be honest" min="0" max="100" thumb-label="always"  :messages="form.errors.get('task_completion')" @end="onProgressChange"></v-slider>
                                        </v-card>
                                    </v-flex>
                                    <v-flex xs10>
                                        <v-textarea rows="3" v-model="form.task_comment" box label="Message" type="text" :messages="form.errors.get('task_comment')"></v-textarea>
                                    </v-flex>
                                    <v-flex xs2><v-btn class="mx-0" dark depressed type="submit">Post</v-btn></v-flex>
                                </v-layout>
                            </v-timeline-item>
                            </form>
                        </v-timeline>
                    </v-container>
                </v-app>
            </div>
        </div>
        <v-dialog v-model="dialog" width="700">
            <v-card>
                <v-card-title class="headline grey lighten-2" primary-title>
                    Task Progress 
                </v-card-title>
      
                <v-card-text> 
                    <div>
                        <p>Progress cannot be decreased. You are required to increase the progress.</p>
                        <p><span><strong>Last Progress:</strong></span>{{ this.form.task_completion }}</p>
                    </div>
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
    import Task from '../../models/Task';
    import Multiselect from 'vue-multiselect';
    export default {
        components: {
            Task, Multiselect      
        },
        props: ['taskid','taskpoint'],
        data() {
            return {
                form: new Form({
                    task_hours: '',
                    task_comment: '',
                    task_completion: 0,
                    errors:'',
                    task_id: this.taskid,
                    availableHours: '',
                }),
                events: [],
                input: null,
                nonce: 0,
                isLoading:true,
                isAddCommentForm:false,
                dialog:false,
                prevProg:'',
                rules: {
                    hrsmax: [val => val < 24  || `Hours should be between 0 to 24`,val => val >= 0  || `Hours should be between 0 to 24`],
                    minsmax: [val => val < 59  || `Mins should be between 0 to 59`, val => val > -1  || `Mins should be between 0 to 59`],
                },
                task_hrs:'0',
                task_mins:'0',
            }
        },
        created() {
            
            Task.allComments((events) => {
                    this.events = events;
                    this.isAddCommentForm = true;
                    this.isLoading = false;
            }, this.taskid);
        },
        methods: {
            onSubmit() {
                if(this.form.task_completion <= this.prevProg)
                {
                    alert('You are required to update the progress percentage also.');
                    return false;
                }
                this.form.post('/task/comments/'+this.taskid+'/store')
               .then((task) => {
                    this.$emit('commented');
                })
               .catch(error => this.form.task_completion = this.events[this.events.length - 1].task_completion);
               
            },                                         
            closeCommentForm() {
                this.$emit('closeCommentForm');
            },
            onProgressChange (e) {
                if(this.events.length > 0) {
                    let previousProgress = this.events[this.events.length - 1].task_completion;
                    if(e < previousProgress)
                    {
                        this.dialog = true;
                        this.form.task_completion = previousProgress;
                        this.prevProg = previousProgress;
                    }
                }

                
            }       
        },
        computed: {
            timeline () {
              return this.events.slice().reverse()
            },
            calcAvailableHours() {
                Task.getAvailableHours(hours => this.form.availableHours = hours, this.taskpoint,this.taskid);
                return this.form.availableHours;
            }
        },
        watch: {
            events() {
                if(this.events.length > 0)
                {
                    this.form.task_completion = this.events[this.events.length - 1].task_completion;
                }
                
            },
            task_hrs() {
                this.form.task_hours = this.task_hrs + ':' + this.task_mins;
            },
            task_mins() {
                this.form.task_hours = this.task_hrs + ':' + this.task_mins;
            }

        }
    }
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

