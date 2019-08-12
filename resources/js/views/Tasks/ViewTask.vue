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
                            <v-flex xs7>
                                <v-layout row wrap>
                                    <task-comments :key="latestTaskComment" @commented="onCommented" :taskid="taskid" :taskpoint="taskpoint"></task-comments>   
                                </v-layout>
                            </v-flex>
                            <v-flex xs5>
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
                                                    <v-list-tile-sub-title v-if="task.status">
                                                        <v-chip class="white--text ml-0" color="info" label small>{{ task.status.status_name }}</v-chip></v-list-tile-sub-title>
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
                                    <v-flex xs12 style="background:#ddd">  
                                        <div id="app">
                                            <v-app id="inspire">
                                                <v-subheader style="color:#FB8C00">0 Issues Reported<span class="float:right"></span></v-subheader>
                                                <v-data-table :headers="headers" :items="issues" :items-per-page="5" class="elevation-1" style="display:none">
                                                    <template v-slot:items="props">
                                                        <td class="text-xs-left"><v-btn flat small color="primary">{{ props.item.issue_desc }}</v-btn></td>
                                                        <td class="text-xs-center">{{ props.item.status }}</td>
                                                        <td class="text-xs-center">{{ props.item.reported_by }}</td>
                                                        <td class="text-xs-center">{{ props.item.reported_date }}</td>
                                                      </template>
                                                </v-data-table>
                                            </v-app>
                                        </div>    
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
                headers: [
                    { text: 'Issue', align: 'left', value: 'issue_desc' },
                    { text: 'Status', value: 'status' },
                    { text: 'Reported By', value: 'reported_by' },
                    { text: 'Date', value: 'created_at' },
                ],
                issues: [
                    {
                        issue_desc: 'Require Validations for Hours',
                        reported_by: 'John Doe',
                        status: 'Open',
                        reported_date: '2019-08-01',
                    },
                    {
                        issue_desc: 'Change Task Timeline for Mobile',
                        reported_by: 'Jane Doe',
                        status: 'Closed',
                        reported_date: '2019-20-01',
                    }
                ],                    
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

