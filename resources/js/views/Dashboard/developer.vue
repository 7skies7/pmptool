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
                        <v-card>
                            <v-toolbar color="#28B5E7" dark style="box-shadow:unset"> 
                                <v-toolbar-title>Welcome, <span>{{ user.first_name}}</span></v-toolbar-title>
                            </v-toolbar>
              
                            <v-list two-line>
                                <!-- <v-divider inset></v-divider> -->
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
            <v-flex xs12 sm6 md3>
                <v-layout row wrap>
                    
                    <v-flex xs12 sm12 md12>
                        <v-card class="mx-auto" color="blue-grey darken-2" dark >
                            <v-list two-line subheader color="blue-grey darken-2">
                                <v-subheader inset>Statistics</v-subheader>
                                <v-list-tile  avatar @click="">
                                    <v-list-tile-avatar>
                                        <v-icon class="blue white--text">call_to_action</v-icon>
                                    </v-list-tile-avatar>
              
                                    <v-list-tile-content>
                                        <v-list-tile-title>Overall Efficiency</v-list-tile-title>
                                        <v-list-tile-sub-title><!-- {{ item.subtitle }} --></v-list-tile-sub-title>
                                    </v-list-tile-content>
              
                                    <v-list-tile-action>
                                        <v-btn icon ripple>
                                            90%
                                        </v-btn>
                                    </v-list-tile-action>
                                </v-list-tile>
                                <v-list-tile  avatar @click="">
                                    <v-list-tile-avatar>
                                        <v-icon class="blue white--text">call_to_action</v-icon>
                                    </v-list-tile-avatar>
              
                                    <v-list-tile-content>
                                        <v-list-tile-title>Overall Efficiency</v-list-tile-title>
                                        <v-list-tile-sub-title><!-- {{ item.subtitle }} --></v-list-tile-sub-title>
                                    </v-list-tile-content>
              
                                    <v-list-tile-action>
                                        <v-btn icon ripple>
                                            90%
                                        </v-btn>
                                    </v-list-tile-action>
                                </v-list-tile>
                            </v-list>
                        </v-card>
                    </v-flex>
                    
                </v-layout>
            </v-flex>
            <v-flex flex xs12 sm6 md6>
                <v-layout row wrap>
                    <v-flex flex xs12 sm6 md12>
                        <div class="card card-default shadow-sm">
                            <div class="card-header" style="text-transform:unset">
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
                            <FullCalendar defaultView="dayGridMonth" :key="calendarCount" :eventDurationEditable="true" :plugins="calendarPlugins"  :events="timesheets"/>
                            </div>
                        </div>
                    </v-flex>
                </v-layout>
            </v-flex>
        </v-layout>
     
        
      </v-container>
    </v-app>
    <v-dialog v-model="dialog" width="900" height="700">
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
    export default {
        components: {
            Datepicker,Chart, FullCalendar, Camera, moment
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
                
            }
        },
        created() {
            User.getTimecard(timecard => this.timecard = timecard); 
            User.fetchUserDetails(user => this.user = user); 
            User.fetchTimesheet(timesheets => this.timesheets = timesheets);

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
            }

        },
        mounted() {
            console.log('Component mounted.')
        },
        watch: {
            timecard() {
                
                if(this.timecard != '')
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
                    user.first_name = this.user.first_name;
                    user.email = this.user.email;
                    user.alternate_email = this.user.alternate_email;
                    user.roles = this.user.roles;
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