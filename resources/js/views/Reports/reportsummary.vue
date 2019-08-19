<template>
    <div class="card-body p-0">
        <div class="d-flex flex-column">
            <div >
                <div id="app" class="aclmain">
                    <v-app id="editTask" style="background:#eee!important">
                        <v-progress-linear v-show="isLoading" v-slot:progress color="blue" indeterminate :height="3"></v-progress-linear>    
                        <v-container grid-list-md text-xs-center class="commentContainer">
                            <v-layout align-center justify-center row style="padding:10px;">
                                <v-flex xs10>
                                    <v-layout row wrap>
                                        <v-form @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                                            <v-flex xs12>
                                                <v-layout row wrap>
                                                    <v-flex xs4>
                                                        <v-card color="white">
                                                            <div class="v-input v-text-field v-input--is-label-active theme--light">
                                                                <div class="v-input__control">
                                                                    <div class="v-input__slot">
                                                                        <div class="v-text-field__slot multiselectwps">
                                                                            <label aria-hidden="true" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Report Type</label>
                                                                            <multiselect v-model="form.report_type" :options="reports"  :clear-on-select="true" :preserve-search="false" placeholder="Select Report" label="type" track-by="id" :preselect-first="true" >
                                                                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                                            </multiselect>
                                                                            
                                                                        </div>                        
                                                                    </div>
                                                                    <div class="v-text-field__details"><div class="v-messages theme--light"><div class="v-messages__wrapper">{{form.errors.get('report_type')}}</div></div></div>
                                                                </div>
                                                            </div>
                                                        </v-card>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-card color="white">
                                                            <div class="v-input v-text-field v-input--is-label-active theme--light">
                                                                <div class="v-input__control">
                                                                    <div class="v-input__slot">
                                                                        <div class="v-text-field__slot multiselectwps">
                                                                            <label aria-hidden="true" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Project</label>
                                                                            <multiselect v-model="form.project" :options="projects" :multiple="false" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="All" label="project_name" track-by="id" :preselect-first="true" >
                                                                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                                            </multiselect>
                                                                            
                                                                        </div>                        
                                                                    </div>
                                                                    <div class="v-text-field__details"><div class="v-messages theme--light"><div class="v-messages__wrapper">{{form.errors.get('project')}}</div></div></div>
                                                                </div>
                                                            </div>
                                                        </v-card>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-card color="white">
                                                            <div class="v-input v-text-field v-input--is-label-active theme--light">
                                                                <div class="v-input__control">
                                                                    <div class="v-input__slot">
                                                                        <div class="v-text-field__slot multiselectwps">
                                                                            <label aria-hidden="true" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Resource</label>
                                                                            <multiselect v-model="form.resource" :options="resources" :multiple="false" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="All" label="name" track-by="id" :preselect-first="true" >
                                                                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                                            </multiselect>

                                                                        </div>                        
                                                                    </div>
                                                                    <div class="v-text-field__details"><div class="v-messages theme--light"><div class="v-messages__wrapper">{{form.errors.get('resource')}}</div></div></div>
                                                                </div>
                                                            </div>
                                                        </v-card>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-card color="white">
                                                            <v-menu v-model="start_date" :close-on-content-click="false" :nudge-right="40" lazy transition="scale-transition" offset-y full-width min-width="290px">
                                                                <template v-slot:activator="{ on }">
                                                                    <v-text-field v-model="form.start_date" label="From" prepend-icon="event" readonly v-on="on" :messages="form.errors.get('start_date')"></v-text-field>
                                                              </template>
                                                              <v-date-picker v-model="form.start_date" @input="start_date = false"></v-date-picker>
                                                            </v-menu>
                                                        </v-card>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-card color="white">
                                                            <v-menu v-model="end_date" :close-on-content-click="false" :nudge-right="40" lazy transition="scale-transition" offset-y full-width min-width="290px">
                                                                <template v-slot:activator="{ on }">
                                                                    <v-text-field v-model="form.end_date" label="To" prepend-icon="event" readonly v-on="on" :messages="form.errors.get('end_date')"></v-text-field>
                                                              </template>
                                                              <v-date-picker v-model="form.end_date" @input="end_date = false"></v-date-picker>
                                                            </v-menu>
                                                        </v-card>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-card color="white">
                                                            <div class="v-input v-text-field v-input--is-label-active theme--light">
                                                                <div class="v-input__control">
                                                                    <div class="v-input__slot">
                                                                        <div class="v-text-field__slot multiselectwps">
                                                                            <label aria-hidden="true" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Report Type</label>
                                                                            <multiselect v-model="form.zoom_level" :options="zooms" :clear-on-select="true" :preserve-search="false" placeholder="Select Zoom Level" label="type" track-by="id" :preselect-first="true" >
                                                                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                                            </multiselect>
                                                                            
                                                                        </div>                        
                                                                    </div>
                                                                    <div class="v-text-field__details"><div class="v-messages theme--light"><div class="v-messages__wrapper">{{form.errors.get('report_type')}}</div></div></div>
                                                                </div>
                                                            </div>
                                                        </v-card>
                                                    </v-flex>
                                                </v-layout>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-card style="background:transparent">
                                                    <v-btn type="submit" color="primary">Search</v-btn>
                                                </v-card>
                                            </v-flex>
                                        </v-form>
                                    </v-layout>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-app>
                    <v-app id="inspire" v-if="project_reports.length">
                        <v-card-title>
                        <v-toolbar-title>{{ this.report_text }}</v-toolbar-title>
                        <v-divider class="mx-2" inset vertical></v-divider>
                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>
                        <v-text-field
                          v-model="search"
                          append-icon="search"
                          label="Search"
                          single-line
                          hide-details
                        ></v-text-field>
                        </v-card-title>

                        <v-data-table :headers="project_headers" :search="search" :items="project_reports" class="reporttable">
                            <template v-slot:items="props" class="table-bordered">
                                <td class="text-xs-centre">{{ props.item.project_name }}</td>
                                <td class="text-xs-centre" v-for="x in 12">
                                    <span v-if="props.item[x]">{{ props.item[x] }}</span>
                                    <span v-else>-</span>
                                </td>
                            </template>
                        </v-data-table>
                    </v-app>
                    <v-app id="inspire" v-if="resource_reports.length">
                        <v-card-title>
                        <v-toolbar-title>{{ this.report_text }}</v-toolbar-title>
                        <v-divider class="mx-2" inset vertical></v-divider>
                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>
                        <v-text-field
                          v-model="search"
                          append-icon="search"
                          label="Search"
                          single-line
                          hide-details
                        ></v-text-field>
                        </v-card-title>
                        <v-data-table :headers="resource_headers" :search="search" :items="resource_reports" class="reporttable">
                            <template v-slot:items="props" class="table-bordered">
                                <td class="text-xs-centre">{{ props.item.resource_name }}</td>
                                <td class="text-xs-centre" v-for="x in 12">
                                    <span v-if="props.item[x]">{{ props.item[x] }}</span>
                                    <span v-else>-</span>
                                </td>
                            </template>
                        </v-data-table>
                    </v-app>
                </div>
            </div>
        </div>
    </div>              
</template>

<script>
    import resourcereport from './resourcereport';
    import projectreport from './projectreport';
    import timecardreport from './timecardreport';
    import moment from 'moment';
    import Multiselect from 'vue-multiselect';
    import Report from '../../models/Report';

    export default {
        components: {
            resourcereport, projectreport, timecardreport, moment, Multiselect, Report
        },
        data() {
            return {
                form: new Form({
                    report_type:'',
                    project:'',
                    resource:'',
                    start_date:'',
                    end_date:'',
                    zoom_level:'',
                }),
                start_date: false,
                end_date: false,
                cardWidth: 'col-md-12',
                isLoading: false,
                reports: [{ id: 1, type: 'Project Report'}, { id: 2, type: 'Resource Report'}],
                zooms: [{ id: 1, type: 'Monthly'}, { id: 2, type: 'Daily'}],
                projects:[],
                resources:[],
                search: '',
                project_headers: [
                    { text: 'Project Name', align: 'left', value: 'resource_name'},
                    { text: 'Jan', value: 'project_name' },
                    { text: 'Feb', value: 'crd_title' },
                    { text: 'Mar', value: 'userstory_desc' },
                    { text: 'Apr', value: 'task_title' },
                    { text: 'May', value: 'sub_task_title' },
                    { text: 'Jun', value: 'created_at' },
                    { text: 'Jul', value: 'comments' },
                    { text: 'Aug', value: 'task_hours'},
                    { text: 'Sep', value: 'timecard' },
                    { text: 'Oct', value: 'timecard' },
                    { text: 'Nov', value: 'timecard' },
                    { text: 'Dec', value: 'timecard' }
                ],
                project_reports: [],
                resource_headers: [
                    { text: 'Resource Name', align: 'left', value: 'resource_name'},
                    { text: 'Jan', value: 'project_name' },
                    { text: 'Feb', value: 'crd_title' },
                    { text: 'Mar', value: 'userstory_desc' },
                    { text: 'Apr', value: 'task_title' },
                    { text: 'May', value: 'sub_task_title' },
                    { text: 'Jun', value: 'created_at' },
                    { text: 'Jul', value: 'comments' },
                    { text: 'Aug', value: 'task_hours'},
                    { text: 'Sep', value: 'timecard' },
                    { text: 'Oct', value: 'timecard' },
                    { text: 'Nov', value: 'timecard' },
                    { text: 'Dec', value: 'timecard' }
                ],
                resource_reports: [],
                report_text: 'Report',
            }   
        },
        created() {
            Report.getAllProjects(records => this.projects = records);
            Report.getAllResources(records => this.resources = records);

        },
        methods: {
            onSubmit() {
                var self = this;
                this.form.post('/report/advancesearch')
                .then((records) => {
                    this.clearReport();
                    if(this.form.report_type.id == 1 && this.form.project)
                    {
                        this.report_text = this.form.project.project_name;
                        this.resource_reports = records;    
                    }
                    if(this.form.report_type.id == 1 && this.form.project == '')
                    {
                        this.project_reports = records;    
                        this.report_text = 'All Projects';
                    }
                    this.form.project = '';
                }); 
            },
            clearReport() {
                this.resource_reports = [];
                this.project_reports = [];
            }
        },
        mounted() {
            // this.isLoading = true;
            // Acl.roles(roles => this.roles = roles)      
            console.log('Component mounted.')
        },
        watch: {
        }
    }
</script>
