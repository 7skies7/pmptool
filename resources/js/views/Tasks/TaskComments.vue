<template>
    <div class="col-md-12">
        <div class="card card-default shadow-sm">
            <div class="card-header">Task Timeline</div>
            <v-progress-linear v-show="isLoading" v-slot:progress color="blue" indeterminate class="nomarginprogress" height="4px"></v-progress-linear>
            <div class="card-body">
                <v-app id="inspire">
                    <v-container>
                        <v-timeline dense clipped class="commentblock" v-if="events">
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
                        <v-timeline dense clipped>
                            <form @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                            <v-timeline-item fill-dot class="white--text mb-0" color="info" large>
                                <template v-slot:icon> <span><v-icon medium dark>comment</v-icon></span> </template>
                                <v-layout row wrap>
                                    <v-flex xs6>
                                        <v-text-field v-model="form.task_hours" label="Hours" placeholder="Add Hours" box :suffix="calcAvailableHours" :messages="form.errors.get('task_hours')"></v-text-field>
                                        </v-card>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-card flat class="customcard">
                                            <v-slider v-model="form.task_completion" color="primary" label="Progress" hint="Be honest" min="0" max="100" thumb-label="always" tick :messages="form.errors.get('task_completion')"></v-slider>
                                        </v-card>
                                    </v-flex>
                                    <v-flex xs10>
                                        <v-text-field v-model="form.task_comment" box label="Message" type="text" :messages="form.errors.get('task_comment')"></v-text-field>
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
                    task_completion: '',
                    errors:'',
                    task_id: this.taskid,
                    availableHours: '',
                }),
                events: [],
                input: null,
                nonce: 0,
                isLoading:true,
            }
        },
        created() {
            this.isLoading = false;
            Task.allComments(events => this.events = events, this.taskid);
        },
        methods: {
            onSubmit() {
                this.form.post('/task/comments/'+this.taskid+'/store')
               .then((task) => {
                    this.$emit('commented');
                });
               
            },                                         
            closeCommentForm() {
                this.$emit('closeCommentForm');
            },
            comment () {
            }       
        },
        computed: {
            timeline () {
              return this.events.slice().reverse()
            },
            calcAvailableHours() {
                Task.getAvailableHours(hours => this.form.availableHours = hours, this.taskpoint,this.taskid);
                return "'Available Hours:" + this.form.availableHours + "'";
            }
        },
        mounted() {
                        
        }
    }
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
