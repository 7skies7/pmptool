<template>
    <div id="app">
        <v-app id="taskcomment">
            <v-form ref="form" @submit.prevent="submit">    
            <v-layout row justify-center>
                <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
                <v-card>
                    <v-toolbar dark color="primary">
                        <v-btn icon dark @click="dialog = false">
                          <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>Add Comments</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-toolbar-items>
                          <v-btn dark flat @click="dialog = false">Save</v-btn>
                        </v-toolbar-items>
                    </v-toolbar>
                    <v-app id="taskcomment1">
                    <v-container grid-list-md text-xs-center class="commentContainer">
                        <v-layout row wrap>
                            <v-flex xs6>
                                <v-layout row wrap>
                                    <v-flex xs12>
                                        <v-card>
                                            <v-list three-line subheader>
                                                <v-subheader>Please note, you are required to update the progress if you are adding hours.</v-subheader>
                                            </v-list>
                                        </v-card>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-card flat color="white" class="customcard">
                                            <v-text-field
                                                v-model="form.task_hours"
                                                label="Hours" placeholder="Add Hours"
                                                required
                                            ></v-text-field>
                                        </v-card>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-card flat class="customcard">
                                            <v-slider
                                            v-model="form.task_completion" color="primary" label="Progress" hint="Be honest" min="1" max="100" thumb-label="always" tick></v-slider>
                                        </v-card>
                                    </v-flex>
                                    <v-flex xs12>
                                        <v-card flat color="white" class="customcard">
                                            <v-textarea
                                                rows="2"
                                                label="Comment"
                                                v-model="form.task_comment"
                                              ></v-textarea>
                                        </v-card>
                                    </v-flex>
                                    
                                    <v-flex xs12>
                                        <v-card dark>
                                            <v-list>
                                            <v-list-tile avatar>
                                                <v-list-tile-content>
                                                    <v-list-tile-title>Task Description</v-list-tile-title>
                                                    <v-list-tile-sub-title>{{ task.task_desc}}</v-list-tile-sub-title>
                                                </v-list-tile-content>
                                            </v-list-tile>
                                            </v-list>
                                        </v-card>
                                    </v-flex>
                                    <v-flex xs12>
                                        <v-card dark>
                                            <v-list>
                                            <v-list-tile avatar>
                                                <v-list-tile-content>
                                                    <v-list-tile-title>User Story</v-list-tile-title>
                                                    <v-list-tile-sub-title>{{ task.userstory.userstory_desc }}</v-list-tile-sub-title>
                                                </v-list-tile-content>
                                            </v-list-tile>
                                            </v-list>
                                        </v-card>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-card dark>
                                            <v-list>
                                            <v-list-tile avatar>
                                                <v-list-tile-content>
                                                    <v-list-tile-title>Start Date</v-list-tile-title>
                                                    <v-list-tile-sub-title>{{ task.task_start_date }}</v-list-tile-sub-title>
                                                </v-list-tile-content>
                                            </v-list-tile>
                                            </v-list>
                                        </v-card>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-card dark>
                                            <v-list>
                                            <v-list-tile avatar>
                                                <v-list-tile-content>
                                                    <v-list-tile-title>End Date</v-list-tile-title>
                                                    <v-list-tile-sub-title>{{ task.task_end_date }}</v-list-tile-sub-title>
                                                </v-list-tile-content>
                                            </v-list-tile>
                                            </v-list>
                                        </v-card>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-card dark>
                                            <v-list>
                                            <v-list-tile avatar>
                                                <v-list-tile-content>
                                                    <v-list-tile-title>Task Point</v-list-tile-title>
                                                    <v-list-tile-sub-title>{{ task.task_point }}</v-list-tile-sub-title>
                                                </v-list-tile-content>
                                            </v-list-tile>
                                            </v-list>
                                        </v-card>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-card dark>
                                            <v-list>
                                            <v-list-tile avatar>
                                                <v-list-tile-content>
                                                    <v-list-tile-title>Assignee</v-list-tile-title>
                                                    <v-list-tile-sub-title>{{ task.assignee.first_name }}{{ task.assignee.last_name }}</v-list-tile-sub-title>
                                                </v-list-tile-content>
                                            </v-list-tile>
                                            </v-list>
                                        </v-card>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-card dark>
                                            <v-list>
                                            <v-list-tile avatar>
                                                <v-list-tile-content>
                                                    <v-list-tile-title>Status</v-list-tile-title>
                                                    <v-list-tile-sub-title>{{ task.status.status_name }}</v-list-tile-sub-title>
                                                </v-list-tile-content>
                                            </v-list-tile>
                                            </v-list>
                                        </v-card>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-card dark>
                                            <v-list>
                                            <v-list-tile avatar>
                                                <v-list-tile-content>
                                                    <v-list-tile-title>Priority</v-list-tile-title>
                                                    <v-list-tile-sub-title>{{ task.priority.priority_type }}</v-list-tile-sub-title>
                                                </v-list-tile-content>
                                            </v-list-tile>
                                            </v-list>
                                        </v-card>
                                    </v-flex>
                                    <v-flex xs12 style="display:none">
                                        <v-card>
                                            <v-card-text>
                                                <v-subheader class="pl-0">Task Completion %</v-subheader>
                                                <v-slider v-model="value" step="10" thumb-label="always" tick ></v-slider>
                                          </v-card-text>
                                        </v-card>
                                    </v-flex>   
                                </v-layout>
                            </v-flex>
                            <v-flex xs6>
                                <v-layout row wrap>
                                    <v-flex xs12>
                                        <v-card>
                                            <task
                                        
                                        </v-card>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                    </v-container>
                    </v-app>
                </v-card>
              </v-dialog>
            </v-layout>
        </v-form>
        </v-app>
    </div>
</template>
<script>
    import Multiselect from 'vue-multiselect';
    import Task from '../../models/Task';
    import TaskComments from './TaskComments';
    export default {
        components: {
            Multiselect, Task
        },
        props: ['taskid'],
        data() {
            
            return {
                cardWidth: 'col-md-10',
                dialog: true,
                notifications: false,
                sound: true,
                widgets: false,
                task:[],
                value:0,
                form: new Form({
                    errors:'',
                        task_comment: '',
                        task_hours: '',
                        task_completion: 0,
                }),
                timeline:'',
            }
        },
        created() {
            Task.getTask(task => this.task = task, this.taskid);
        },
        methods: {
            onSubmit() {

            },
            submit () {
                this.resetForm()
            }
        },
        mounted() {
            console.log('Task mounted.')
        },
        watch: {
            
        },   
    }
</script>

