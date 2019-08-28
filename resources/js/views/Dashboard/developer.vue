<template>
<div id="dashboard" style="margin-top:-24px!important">
    <v-app id="inspire">
        <v-container grid-list-md text-xs-center class="unsetWidth">
          <v-layout row wrap>
            <v-flex xs12 sm6 md3>
                <v-layout row wrap>
                    <v-flex xs12 sm12 md12>
                        <v-card class="white--text redgradient">
                            <v-card-title primary-title>
                              <div class="timeblock">
                                <div class="headline">
                                    <div class="intime">
                                        <span><v-icon>timer</v-icon>IN:</span><div v-text="login.login"></div>
                                    </div>
                                    <div class="intime">
                                        <span><v-icon>timer</v-icon>OUT:</span> <div v-text="login.logout"></div>
                                    </div>                                        
                                </div>
                                <div v-text="login.message"></div>
                                
                              </div>
                            </v-card-title>
                            <v-card-actions>
                              <v-btn v-if="isLoginVisible" flat dark @click="onLogin">Login</v-btn>
                              <v-btn v-if="isLogoutVisible" flat dark @click="onLogout">Logout</v-btn>

                            </v-card-actions>
                        </v-card>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-card class="userblockdiv">
                            <v-toolbar color="#28B5E7" dark style="box-shadow:unset"> 
                                <v-toolbar-title>Welcome, <span>{{ user.first_name }}</span></v-toolbar-title>
                            </v-toolbar>
              
                            <v-list two-line>
                                <!-- <v-divider inset></v-divider> -->
                                <v-list-tile @click="">
                                    <v-list-tile-action v-if="this.user.hours_clocked != null">
                                        {{ this.user.hours_clocked }}
                                    </v-list-tile-action>
                                    <v-list-tile-action v-else>
                                        00:00
                                    </v-list-tile-action>
                  
                                    <v-list-tile-content>
                                        <v-list-tile-title>Hours Clocked</v-list-tile-title>
                                        <v-list-tile-sub-title>Monthly</v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                                <v-list-tile @click="">
                                    <v-list-tile-action>
                                        100%
                                    </v-list-tile-action>
                  
                                    <v-list-tile-content>
                                        <v-list-tile-title>Efficiency</v-list-tile-title>
                                        <v-list-tile-sub-title>Statistics</v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                                <v-divider inset></v-divider>

                                <v-list-tile @click="">
                                    <v-list-tile-action>
                                        <v-icon color="indigo">mail</v-icon>
                                    </v-list-tile-action>
                  
                                    <v-list-tile-content>
                                        <v-list-tile-title>{{user.email}}</v-list-tile-title>
                                        <v-list-tile-sub-title>Work</v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                  
                                <v-list-tile @click="">
                                    <v-list-tile-action></v-list-tile-action>
                                    <v-list-tile-content>
                                        <v-list-tile-title>{{user.alternate_email}}</v-list-tile-title>
                                        <v-list-tile-sub-title>Altername Email</v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                  
                                <v-divider inset></v-divider>
                  
                                <v-list-tile @click="">
                                    <v-list-tile-action>
                                        <v-icon color="indigo">account_circle</v-icon>
                                    </v-list-tile-action>
                                    <v-list-tile-content>
                                        <v-list-tile-title><span v-if="user.roles" v-for="role in user.roles" :key="role.id">{{ role.role_title }}</span></v-list-tile-title>
                                        
                                    </v-list-tile-content>
                                </v-list-tile>
                            </v-list>
                        </v-card>
                    </v-flex>
                    
                </v-layout>
            </v-flex>
            <v-flex xs12 sm6 md5>
                <v-layout row wrap>
                    <v-flex xs12 sm6 md12>
                        <v-card class="mx-auto" color="grey lighten-4" >
                            <v-subheader inset>Task Deadline Passed (High Risk)</v-subheader>
                            <v-data-table dense :height="400" :headers="headers_task_deadlin" :items="taskdeadlinepassed" class="elevation-1" dense>
                                <template v-slot:items="props">
                                    <!-- <v-btn class="text-xs-left" href="#/mytasks" flat small color="primary">{{ props.item.task_title }}</v-btn> -->
                                    <span  @click="onShowComments(props.item.id, props.item.task_point)" class="titleBtn">{{ props.item.task_title }}</span>
                                    <td class="text-xs-left">{{ props.item.project.project_name }}</td>
                                    <td class="text-xs-left">{{ props.item.task_end_date }}</td>

                                    <!-- <td class="text-xs-left">{{ props.item.status.status_name }}</td> -->
                                    <td class="text-xs-center"><v-progress-linear v-model="props.item.task_completion" color="#ed4555" thumb-label="always"></v-progress-linear></td>
                                </template>
                            </v-data-table>
                        </v-card>
                    </v-flex>
                    <v-flex xs12 sm6 md12>
                        <v-card class="mx-auto" color="grey lighten-4" >
                            <v-subheader inset>Upcoming Tasks</v-subheader>
                            <v-data-table dense :height="400" :headers="headers_task_upcoming" :items="upcomingtasks" class="elevation-1" dense>
                                <template v-slot:items="props">
                                    <td class="text-xs-left">
                                        <!-- <v-btn href="#/mytasks" flat small color="primary">{{ props.item.task_title }}</v-btn> -->
                                        <span  @click="onShowComments(props.item.id, props.item.task_point)" class="titleBtn">{{ props.item.task_title }}</span>
                                    </td>
                                    <td class="text-xs-left">{{ props.item.project.project_name }}</td>
                                    <td class="text-xs-left">{{ props.item.task_end_date }}</td>
                                    <!-- <td class="text-xs-center">
                                        <h5 v-if="props.item.status"><b-badge pill variant="info">{{ props.item.priority.priority_type }}</b-badge></h5>
                                        <h5 v-else>-</h5>
                                    </td> -->
                                    <td class="text-xs-center"><v-progress-linear v-model="props.item.task_completion" thumb-label="always"></v-progress-linear></td>
                                </template>
                            </v-data-table>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-flex>
            <v-flex flex xs12 sm6 md4>
                <v-layout row wrap>
                    <v-flex flex xs12 sm6 md12>
                        <div class="card card-default shadow-sm">
                            <div class="card-header activitycalendar">
                                <v-list two-line subheader>
                                    <v-list-tile avatar>
                                        <v-list-tile-avatar>
                                            <v-icon class="blue white--text">calendar_today</v-icon>
                                        </v-list-tile-avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title>Daily Activity Calendar</v-list-tile-title>
                                            <v-list-tile-sub-title>Click on the date to check for the days activity.</v-list-tile-sub-title>
                                      </v-list-tile-content>
                                    </v-list-tile>
                                </v-list>
                            </div>
                            <div class="card-body white lighten-4">
                            <FullCalendar defaultView="dayGridMonth" :key="calendarCount" :eventDurationEditable="true" :plugins="calendarPlugins"  :events="timesheets" @eventClick="onEventClick"/>
                            </div>
                        </div>
                    </v-flex>
                </v-layout>
            </v-flex>
        </v-layout>
     
        
      </v-container>
    </v-app>
    <v-dialog v-model="dialog" width="900" height="700" persistent>
        <v-card>
            <v-card-title class="headline grey lighten-2" primary-title> Login / Logout</v-card-title>
  
          <v-card-text>
            <component v-if="camcomponent" :is="camcomponent" @imageuploaded="onImageUpload" :logtype="logtype"></component>
            <p v-else>Please fill your timesheet in order to logout.</p>
          </v-card-text>
  
          <v-divider></v-divider>
  
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" flat @click="dialog = false">
              Close
            </v-btn>

          </v-card-actions>
        </v-card>
    </v-dialog>
    <v-dialog v-model="taskdialog" width="500">
        <v-card>
            <v-card-title class="headline grey lighten-2" primary-title>
            Details
            </v-card-title>
            <v-card-text v-html="commenttable"></v-card-text>
            <v-divider></v-divider>
            <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" flat @click="taskdialog = false">Close</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <view-task v-if="isTaskCommentVisible" :taskid="taskid" :taskpoint="taskpoint"></view-task>
</div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';
    // import Highcharts from 'highcharts';
    import Chart from '../../components/Chart.vue';
    import FullCalendar from '@fullcalendar/vue';
    import dayGridPlugin from '@fullcalendar/daygrid';
    import Camera from '../../components/Camera.vue';
    import User from '../../models/User';
    import Dashboard from '../../models/Dashboard';
    import ViewTask from '../Tasks/ViewTask';

    export default {
        components: {
            Datepicker,Chart, FullCalendar, Camera, moment, Dashboard, ViewTask
        },
        data() {
            return{
                addProgram: false,
                programs: [],
                cardWidth: 'col-md-10',
                progressbar: '80',
                calendarPlugins: [ dayGridPlugin ],
                login: {
                    login:'00:00',
                    logout:'00:00',
                    message: "You are required to face log in, to track your timesheet"
                },
                dialog:false,
                taskdialog:false,
                isLoginVisible:true,
                isLogoutVisible:false,
                camcomponent:'',
                timecard: [],
                logtype: 1,
                user:{
                    first_name: '',
                    roles: '',
                    email:'',
                    alternate_email:'',
                },
                timesheets: [],
                calendarCount:0,
                taskdeadlinepassed: [],
                upcomingtasks:[],
                commenttable:'',
                isTaskCommentVisible:false,
                headers_task_deadlin: [
                    { text: 'Task Name ', align: 'left', sortable: false, value: 'task_title' },
                    { text: 'Project',  value: 'project.project_name' },
                    { text: 'End Date', value: 'task_end_date' },
                    { text: 'Progress', value: 'task_completion' },
                ],
                headers_task_upcoming: [
                    { text: 'Task Name ', align: 'left', sortable: false, value: 'task_title' },
                    { text: 'Project',  value: 'project.project_name' },
                    { text: 'End Date', value: 'task_end_date' },
                    // { text: 'Priority', value: 'priority.priority_type' },
                    { text: 'Progress', value: 'task_completion' },
                ],
                
            }
        },
        created() {
            User.getTimecard(timecard => this.timecard = timecard); 
            User.fetchUserDetails(user => this.user = user); 
            User.fetchTimesheet(timesheets => this.timesheets = timesheets);
            Dashboard.getTaskDeadlinePassed(records => this.taskdeadlinepassed = records);
            Dashboard.getAllUpcomingTasks(records => this.upcomingtasks = records);


        },
        methods: {
            onLogin() {
                this.dialog = true;
                this.camcomponent = 'camera';
                this.logtype = 1;
            },
            onLogout() {
                User.getTodayComment((comment) => {
                    this.dialog = true;
                    if(comment >= 1)
                    {
                        this.dialog = true;
                        this.camcomponent = 'camera';
                        this.logtype = 2;
                    }else{
                        this.camcomponent = '';
                    }
                    
                    // this.timecard = timecard;
                }); 
            },
            onImageUpload(){
                User.getTimecard(timecard => this.timecard = timecard); 
                this.$toasted.success('Congratulations! You have logged in successfully.');
                this.dialog = false;
            },
            getLogTime(){
                User.getTimecard(timecard => this.timecard = timecard); 
            },
            onEventClick(e){
                this.commenttable = e.event.extendedProps.comments;
                this.taskdialog = true;
            },
            onShowComments(id, taskpoint) {
                this.isTaskCommentVisible = !this.isTaskCommentVisible;
                this.taskid = id;
                this.taskpoint = taskpoint;
            },

        },
        mounted() {
            console.log('Component mounted.')
        },
        watch: {
            timecard() {
                if(this.timecard.length)
                {
                    if(this.timecard[0].log_out_image !=  null && this.timecard[0].log_in_image !=  null){
                        this.login.login =  moment(this.timecard[0].log_in_time).format('hh:mm');
                        this.login.logout =  moment(this.timecard[0].log_out_time).format('hh:mm');
                        this.login.message = 'Timecard for the day has been filled';
                        this.isLogoutVisible = false;
                        this.isLoginVisible = false;
                    }else if(this.timecard[0].log_in_image !=  null)
                    {
                        
                        this.login.login =  moment(this.timecard[0].log_in_time).format('hh:mm');
                        this.login.message = 'Please fill the timesheet before logging out.';
                        this.isLogoutVisible = true;
                        this.isLoginVisible = false;    
                    }
                }
            },
            user() {
                // console.log(this.user);
                if(this.user != ''){
                    this.user.first_name = this.user.first_name;
                    this.user.email = this.user.email;
                    this.user.alternate_email = this.user.alternate_email;
                    this.user.roles = this.user.roles;
                }
            },
            timesheets() {
                this.calendarCount += 1;
                
            }
        }
    }
</script>
<style lang='scss'>

@import '~@fullcalendar/core/main.css';
@import '~@fullcalendar/daygrid/main.css';

</style>