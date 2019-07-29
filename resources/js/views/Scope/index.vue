<template>

    <div class="container-fluid">
        <div class="projectTabs">
            <div class="row justify-content-center">
                <div class="col-md-12">

                    <v-toolbar tabs>
                        <div>
                           <!--  <v-avatar size="35">
                                <img src="https://cdn.vuetifyjs.com/images/john.jpg" alt="John">
                            </v-avatar> -->
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
                            <v-tab href="#mobile-tabs-5-1" class="primary--text" @click="changeTab(0)">
                                Dashboard
                            </v-tab>

                            <v-tab href="#mobile-tabs-5-2" class="primary--text" @click="changeTab(1)">
                                Scope
                            </v-tab>
                  
                            <v-tab href="#mobile-tabs-5-3" class="primary--text" @click="changeTab(2)">
                                Tasks
                            </v-tab>
                  
                            <v-tab href="#mobile-tabs-5-4" class="primary--text" @click="changeTab(3)">
                                Sprint
                            </v-tab>
                          </v-tabs>
                        </template>
                    </v-toolbar>
                </div>
            </div>
        </div>
        <dashboard v-if="tabsdiv[0]" :key="latestKey"></dashboard>
        <scope v-if="tabsdiv[1]" :key="latestKey"></scope>
        <task v-if="tabsdiv[2]" :key="latestKey"></task>
        <sprint v-if="tabsdiv[3]" :key="latestKey"></sprint>
    </div>
    
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';
    import dashboard from './dashboard.vue';
    import scope from './scope.vue';
    import task from '../Tasks/index.vue';
    import sprint from './sprint.vue';
    import Scope from '../../models/Scope';

    export default {
        components: {
            Datepicker, scope, task, sprint, dashboard, Scope
        },
        data() {
            return{     
                tabs: null,      
                cardWidth: 'col-md-10',
                isAddVisible: false,
                dashboard:true,
                scope:false,
                task:false,
                sprint:false,
                latestKey:0,
                tabsdiv: [true, false, false, false],
                project_id: this.$route.params.id,
                project: {},
            }
        },
        created() {
            Scope.getProject(project => this.project = project, this.project_id);

        },
        methods: {
            changeTab(tab) {
                this.tabsdiv = [false, false, false, false];
                this.tabsdiv[tab] = true;
            }
           
        },
        mounted() {
            
        }
    }
</script>
