<template>    
        <div class="projectTabs">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <v-app id="scopeTabs">
                        <v-container grid-list-md text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs2>
                                    Dashboard
                                </v-flex>
                                <v-flex xs2>
                                    <router-link :to="'/project/'+project.id+'/scope'" class="">Change Request</router-link>
                                    
                                </v-flex>
                                <v-flex xs4 class="projectName">
                                    {{this.project.project_name}}
                                </v-flex>
                                <v-flex xs2>
                                    <router-link :to="'/project/'+project.id+'/tasks'" class="">Tasks</router-link>
                                </v-flex>
                                <v-flex xs2>
                                    Sprint
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-app>
                    
                </div>
            </div>
            <div class="row justify-content-center" style="display:none">
                <div class="col-md-12">

                    <v-toolbar tabs>
                        <div>
                            <v-avatar color="teal" size="35">
                                <span class="white--text headlinesmall">CM</span>
                            </v-avatar>
                            <v-avatar size="35" color="#EF4667">
                                <span class="white--text headlinesmall">AP</span>
                            </v-avatar>
                            <v-avatar color="teal" size="35">
                                <span class="white--text headlinesmall">PM</span>
                            </v-avatar>
                        </div>
                        <div>          
                            <v-toolbar-title>{{ this.project.project_name}}</v-toolbar-title>   
                        </div>
                        <!-- <v-spacer></v-spacer> -->
                        <div>
                            <v-chip title="Start Date">{{ this.project.project_start_date}}</v-chip>
                            <v-chip title="End Date">{{ this.project.project_end_date}}</v-chip>
                            <v-btn icon>
                              <v-icon>more_vert</v-icon>
                            </v-btn>
                        </div>
                        <template v-slot:extension>
                          <v-tabs
                            v-model="tabs"
                            fixed-tabs
                            color="transparent"
                          >
                            <v-tabs-slider></v-tabs-slider>
                            <!-- <v-tab :to="'/project/'+project.id+'/dashboard'" class="primary--text"> -->
                                <!-- Dashboard -->
                            <!-- </v-tab> -->

                            <v-tab :to="'/project/'+project.id+'/scope'" class="primary--text" :key="2">
                                Scope
                            </v-tab>
                  
                            <v-tab :to="'/project/'+project.id+'/tasks'" class="primary--text" :key="3">
                                Tasks
                            </v-tab>
                  
                            <!-- <v-tab :href="'#/project/'+this.project.id+'/sprint'" class="primary--text" @click="changeTab(3)"> -->
                                <!-- Sprint -->
                            <!-- </v-tab> -->
                          </v-tabs>
                        </template>
                    </v-toolbar>
                </div>
            </div>
        </div>
      
    </div>
    
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';
    import Scope from '../../models/Scope.js'

    export default {
        components: {
            Datepicker, Scope
        },
        props:['selected'],
        data() {
            return{     
                tabs: null,      
                cardWidth: 'col-md-10',
                isAddVisible: false,
                latestKey:0,
                project_id: this.$route.params.id,
                project: {},
            }
        },
        created() {
            Scope.getProject(project => this.project = project, this.project_id);
            this.tabs = this.selected;
        },
        methods: {
           
        },
        mounted() {
            
        }
    }
</script>
