<template>
    <div class="card-body p-0">
        <div class="d-flex flex-column">
            <div >
                <div id="app" class="aclmain">
                    <v-app id="editTask" style="background:#eee!important">
                        <v-progress-linear v-show="isLoading" v-slot:progress color="blue" indeterminate :height="3"></v-progress-linear>    
                        <v-container grid-list-md text-xs-center class="reportContainer reportContainer">
                            <v-layout align-center justify-center row style="padding:10px;">
                                <v-form @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                                    <v-flex xs12>
                                        <v-layout row wrap>
                                            <v-flex xs2>
                                                <v-card color="white">
                                                    <div class="v-input v-text-field v-input--is-label-active theme--light">
                                                        <div class="v-input__control">
                                                            <div class="v-input__slot">
                                                                <div class="v-text-field__slot multiselectwps">
                                                                    <label aria-hidden="true" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Report Type</label>
                                                                    <multiselect @input="onChangeReport" v-model="form.report_type" :options="reports"  :clear-on-select="true" :preserve-search="false" placeholder="Select Report" label="type" track-by="id" :preselect-first="true" :selectLabel="''" :deselectLabel="''">
                                                                        <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                                    </multiselect>
                                                                    
                                                                </div>                        
                                                            </div>
                                                            <div class="v-text-field__details"><div class="v-messages theme--light"><div class="v-messages__wrapper">{{form.errors.get('report_type')}}</div></div></div>
                                                        </div>
                                                    </div>
                                                </v-card>
                                            </v-flex>
                                            <v-flex xs2 v-if="isProjectVisible">
                                                <v-card color="white">
                                                    <div class="v-input v-text-field v-input--is-label-active theme--light">
                                                        <div class="v-input__control">
                                                            <div class="v-input__slot">
                                                                <div class="v-text-field__slot multiselectwps">
                                                                    <label aria-hidden="true" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Project</label>
                                                                    <multiselect v-model="form.project" :options="projects" :multiple="false" :clear-on-select="true" :preserve-search="false" placeholder="All" label="project_name" track-by="id" :preselect-first="true" :selectLabel="''" :deselectLabel="''">
                                                                        <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                                    </multiselect>
                                                                    
                                                                </div>                        
                                                            </div>
                                                            <div class="v-text-field__details"><div class="v-messages theme--light"><div class="v-messages__wrapper">{{form.errors.get('project')}}</div></div></div>
                                                        </div>
                                                    </div>
                                                </v-card>
                                            </v-flex>
                                            <v-flex xs2 v-if="isResourceVisible">
                                                <v-card color="white">
                                                    <div class="v-input v-text-field v-input--is-label-active theme--light">
                                                        <div class="v-input__control">
                                                            <div class="v-input__slot">
                                                                <div class="v-text-field__slot multiselectwps">
                                                                    <label aria-hidden="true" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Resource</label>
                                                                    <multiselect v-model="form.resource" :options="resources" :multiple="false" :clear-on-select="true" :preserve-search="false" placeholder="All" label="name" track-by="id" :preselect-first="true" :selectLabel="''" :deselectLabel="''">
                                                                        <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                                    </multiselect>

                                                                </div>                        
                                                            </div>
                                                            <div class="v-text-field__details"><div class="v-messages theme--light"><div class="v-messages__wrapper">{{form.errors.get('resource')}}</div></div></div>
                                                        </div>
                                                    </div>
                                                </v-card>
                                            </v-flex>
                                            <v-flex xs2>
                                                <v-card color="white">
                                                    <v-menu v-model="start_date" :close-on-content-click="false" :nudge-right="40" lazy transition="scale-transition" offset-y full-width min-width="290px">
                                                        <template v-slot:activator="{ on }">
                                                            <v-text-field v-model="form.start_date" label="From" prepend-icon="event" readonly v-on="on" :messages="form.errors.get('start_date')"></v-text-field>
                                                      </template>
                                                      <v-date-picker v-model="form.start_date" @input="start_date = false"></v-date-picker>
                                                    </v-menu>
                                                </v-card>
                                            </v-flex>
                                            <v-flex xs2>
                                                <v-card color="white">
                                                    <v-menu v-model="end_date" :close-on-content-click="false" :nudge-right="40" lazy transition="scale-transition" offset-y full-width min-width="290px">
                                                        <template v-slot:activator="{ on }">
                                                            <v-text-field v-model="form.end_date" label="To" prepend-icon="event" readonly v-on="on" :messages="form.errors.get('end_date')"></v-text-field>
                                                      </template>
                                                      <v-date-picker v-model="form.end_date" @input="end_date = false"></v-date-picker>
                                                    </v-menu>
                                                </v-card>
                                            </v-flex>
                                            <v-flex xs2>
                                                <v-card color="white">
                                                    <div class="v-input v-text-field v-input--is-label-active theme--light">
                                                        <div class="v-input__control">
                                                            <div class="v-input__slot">
                                                                <div class="v-text-field__slot multiselectwps">
                                                                    <label aria-hidden="true" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Report Type</label>
                                                                    <multiselect v-model="form.zoom_level" :options="zooms" :clear-on-select="true" :preserve-search="false" placeholder="Select Zoom Level" label="type" track-by="id" :preselect-first="true" :selectLabel="''" :deselectLabel="''">
                                                                        <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                                    </multiselect>
                                                                    
                                                                </div>                        
                                                            </div>
                                                            <div class="v-text-field__details"><div class="v-messages theme--light"><div class="v-messages__wrapper">{{form.errors.get('report_type')}}</div></div></div>
                                                        </div>
                                                    </div>
                                                </v-card>
                                            </v-flex>
                                            <v-flex xs2>
                                                <v-card style="background:transparent">
                                                    <v-btn type="submit" :loading="searchloader" color="primary" @click="loader = 'searchloader'">Search</v-btn>
                                                </v-card>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                </v-form>
                            </v-layout>
                        </v-container>
                    </v-app>
                    <v-app id="inspire" v-if="project_reports.length">
                        <v-card-title>
                            <v-toolbar-title>{{ this.report_text }}</v-toolbar-title>
                            <v-divider class="mx-2" inset vertical></v-divider>
                            <v-form @submit.prevent="onExport" @keydown="form.errors.clear()">
                                <v-btn type="submit" :loading="exportloader" color="primary" @click="loader = 'exportloader'">Export</v-btn>
                            </v-form>
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
                                <td class="text-xs-left tableMainColor">{{ props.item.project_name }}</td>
                                <td class="text-xs-center" v-for="x in 12">
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
                            <v-form @submit.prevent="onExport" @keydown="form.errors.clear()">
                                <v-btn type="submit" :loading="exportloader" color="primary" @click="loader = 'exportloader'">Export</v-btn>
                            </v-form>
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
                                <td class="text-xs-left tableMainColor">{{ props.item.resource_name }}</td>
                                <td class="text-xs-center" v-for="x in 12">
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
                    type:1,
                }),
                start_date: false,
                end_date: false,
                cardWidth: 'col-md-12',
                isLoading: false,
                reports: [{ id: 1, type: 'Project Report'}, { id: 2, type: 'Resource Report'}],
                zooms: [{ id: 1, type: 'Monthly'}],
                projects:[],
                resources:[],
                search: '',
                project_headers: [
                    { text: 'Project Name', align: 'left', value: 'resource_name'},
                    { text: 'Jan', align: 'center', },
                    { text: 'Feb', align: 'center', },
                    { text: 'Mar', align: 'center', },
                    { text: 'Apr', align: 'center', },
                    { text: 'May', align: 'center', },
                    { text: 'Jun', align: 'center', },
                    { text: 'Jul', align: 'center', },
                    { text: 'Aug', align: 'center', },
                    { text: 'Sep', align: 'center', },
                    { text: 'Oct', align: 'center', },
                    { text: 'Nov', align: 'center', },
                    { text: 'Dec', align: 'center', },
                ],
                project_reports: [],
                resource_headers: [
                    { text: 'Resource Name', align: 'left', value: 'resource_name'},
                    { text: 'Jan', align: 'center', },
                    { text: 'Feb', align: 'center', },
                    { text: 'Mar', align: 'center', },
                    { text: 'Apr', align: 'center', },
                    { text: 'May', align: 'center', },
                    { text: 'Jun', align: 'center', },
                    { text: 'Jul', align: 'center', },
                    { text: 'Aug', align: 'center', },
                    { text: 'Sep', align: 'center', },
                    { text: 'Oct', align: 'center', },
                    { text: 'Nov', align: 'center', },
                    { text: 'Dec', align: 'center', },
                ],
                resource_reports: [],
                report_text: 'Report',
                isProjectVisible: true,
                isResourceVisible: false,
                searchloader:false,
                exportloader:false,
            }   
        },
        created() {
            Report.getAllProjects(records => this.projects = records);
            Report.getAllResources(records => this.resources = records);

        },
        methods: {
            onSubmit() {
                this.searchloader = true;
                this.form.type = 1;
                var self = this;
                this.form.post('/report/advancesearch')
                .then((records) => {
                    this.clearReport();
                    if(this.form.report_type.id == 1 && this.form.project)
                    {
                        this.report_text = this.form.project.project_name;
                        this.resource_reports = records;    
                    }
                    if(this.form.report_type.id == 1 && (this.form.project == '' || this.form.project == null))
                    {
                        this.project_reports = records;    
                        this.report_text = 'All Projects';
                    }
                    if(this.form.report_type.id == 2 && (this.form.resource == '' || this.form.resource == null))
                    {
                        this.resource_reports = records;    
                        this.report_text = 'All Resources';
                    }
                    if(this.form.report_type.id == 2 && this.form.resource)
                    {
                        this.project_reports = records;    
                        this.report_text = this.form.resource.name;
                    }
                    // this.form.project = '';
                    // this.form.resource = '';
                    this.searchloader = false;
                }); 
            },
            clearReport() {
                this.resource_reports = [];
                this.project_reports = [];
            },
            onChangeReport(type) {
                this.isProjectVisible = false;
                this.isResourceVisible = false;
                if(type.id == 1)
                {
                    this.isProjectVisible = true;
                }
                if(type.id == 2)
                {
                    this.isResourceVisible = true;
                }
            },
            onExport() {
                alert('asdasd');
                this.form.type = 2;
                this.exportloader = true;
                var self = this;
                this.form.post('/report/advancesearch')
                .then((records) => console.log(records));
            }
        },
        mounted() {
            console.log('Component mounted.')
            this.onSubmit();
        },
        watch: {
            
        }
    }
</script>
