<template>
    <div id="app">
        <v-app id="taskcomment">
            <!-- <v-form ref="form" @submit.prevent="submit">     -->
            <v-layout row justify-center>
                <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
                <v-card v-if="task">
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
                            <v-flex xs8>
                                <v-layout row wrap>
                                    <task-comments :key="latestTaskComment" @commented="onCommented" :taskid="taskid" :taskpoint="taskpoint"></task-comments>   
                                </v-layout>
                            </v-flex>
                            <v-flex xs4>
                                <v-layout row wrap>
                                    <v-flex xs12>
                                        <v-card dark>
                                            <v-list>
                                            <v-list-tile avatar>
                                                <v-list-tile-content>
                                                    <v-list-tile-title>Task Description</v-list-tile-title>
                                                    <v-list-tile-sub-title v-if="task.task_desc">{{ task.task_desc}}</v-list-tile-sub-title>
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
                                                    <v-list-tile-sub-title v-if="task.userstory">{{ task.userstory.userstory_desc }}</v-list-tile-sub-title>
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
                                                    <v-list-tile-sub-title v-if="task.task_start_date">{{ task.task_start_date }}</v-list-tile-sub-title>
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
                                                    <v-list-tile-sub-title v-if="task.task_end_date">{{ task.task_end_date }}</v-list-tile-sub-title>
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
                                                    <v-list-tile-sub-title v-if="task.task_point">{{ task.task_point }}</v-list-tile-sub-title>
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
                                                    <v-list-tile-sub-title v-if="task.assignee">{{ task.assignee.first_name }}{{ task.assignee.last_name }}</v-list-tile-sub-title>
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
                                                    <v-list-tile-sub-title v-if="task.status">{{ task.status.status_name }}</v-list-tile-sub-title>
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
                                                    <v-list-tile-sub-title v-if="task.priority">{{ task.priority.priority_type }}</v-list-tile-sub-title>
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
                        </v-layout>
                    </v-container>
                    </v-app>
                </v-card>
              </v-dialog>
            </v-layout>
        <!-- </v-form> -->
        </v-app>
    </div>
</template>
<script>
    import Multiselect from 'vue-multiselect';
    import Task from '../../models/Task';
    import TaskComments from './TaskComments';
    export default {
        components: {
            Multiselect, Task, TaskComments
        },
        props: ['taskid', 'taskpoint'],
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
                latestTaskComment:0,
            }
        },
        created() {
            Task.getTask(task => this.task = task, this.taskid);
        },
        methods: {
            onCommented(){
                this.latestTaskComment += 1;
                this.$toasted.success('Congratulations! Your comment has been saved successfully.');
            }
        },
        watch: {
            
        },   
    }
</script>

