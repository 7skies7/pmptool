<template>
    <div class="col-md-6">
        <div class="card card-default shadow-sm">
            <div class="card-header">Add New Project</div>

            <div class="card-body">
                <v-app id="editTask">
                    <v-form @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                        <v-container grid-list-md text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-card color="white">
                                        <v-text-field v-model="form.project_name" label="Project Name" placeholder="Enter project name" :messages="form.errors.get('project_name')"></v-text-field>
                                    </v-card>
                                </v-flex>
                                <v-flex xs12>
                                    <v-card color="white">
                                        <v-textarea v-model="form.project_desc" label="Project Description" placeholder="Enter Project Description" :messages="form.errors.get('project_desc')"></v-textarea>
                                    </v-card>
                                </v-flex>
                                <v-flex xs6>
                                    <v-card color="white" >
                                        <v-menu v-model="start_date" :close-on-content-click="false" :nudge-right="40" lazy transition="scale-transition" offset-y full-width min-width="290px">
                                            <template v-slot:activator="{ on }">
                                                <v-text-field v-model="form.project_start_date" label="Select Start Date" prepend-icon="event" readonly v-on="on" :messages="form.errors.get('project_start_date')"></v-text-field>
                                          </template>
                                          <v-date-picker v-model="form.project_start_date" @input="start_date = false"></v-date-picker>
                                        </v-menu>
                                    </v-card>
                                </v-flex>
                                <v-flex xs6>
                                    <v-card color="white">
                                        <v-menu v-model="end_date" :close-on-content-click="false" :nudge-right="40" lazy transition="scale-transition" offset-y full-width min-width="290px">
                                            <template v-slot:activator="{ on }">
                                                <v-text-field v-model="form.project_end_date" label="Select End Date" prepend-icon="event" readonly v-on="on" :messages="form.errors.get('project_end_date')"></v-text-field>
                                          </template>
                                          <v-date-picker v-model="form.project_end_date" @input="end_date = false"></v-date-picker>
                                        </v-menu>
                                        </v-card>
                                    </v-card>
                                </v-flex>
                                <v-flex xs6>
                                    <v-card color="white">
                                        <div class="v-input v-text-field v-input--is-label-active theme--light">
                                            <div class="v-input__control">
                                                <div class="v-input__slot">
                                                    <div class="v-text-field__slot multiselectwps">
                                                        <label aria-hidden="true" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Program</label>
                                                        <multiselect v-model="form.program" :options="programs" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="Select Program" label="program_name" track-by="id" :preselect-first="false" >
                                                            <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                        </multiselect>
                                                    </div>                                   
                                                </div>
                                                <div class="v-text-field__details"><div class="v-messages theme--light"><div class="v-messages__wrapper">{{form.errors.get('program')}}</div></div></div>
                                            </div>
                                        </div>
                                    </v-card>
                                </v-flex>
                                <v-flex xs6>
                                    <v-card color="white">
                                        <div class="v-input v-text-field v-input--is-label-active theme--light">
                                            <div class="v-input__control">
                                                <div class="v-input__slot">
                                                    <div class="v-text-field__slot multiselectwps">
                                                        <label aria-hidden="true" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Managers</label>
                                                        <multiselect v-model="form.project_managers" :options="options" :multiple="true" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="Select Project Manager" label="name" track-by="id" :preselect-first="false" >
                                                            <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                        </multiselect>
                                                        <h5><b-badge pill variant="info" v-for="manager in form.project_managers" v-bind:key="manager.id">{{ manager.name }} </b-badge></h5>
                                                    </div>                        
                                                </div>
                                                <div class="v-text-field__details"><div class="v-messages theme--light"><div class="v-messages__wrapper">{{form.errors.get('project_managers')}}</div></div></div>
                                            </div>
                                        </div>
                                    </v-card>
                                </v-flex>
                                <v-flex xs6>
                                    <v-card color="white">
                                        <div class="v-input v-text-field v-input--is-label-active theme--light">
                                            <div class="v-input__control">
                                                <div class="v-input__slot">
                                                    <div class="v-text-field__slot multiselectwps">
                                                        <label aria-hidden="true" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Stakeholders</label>
                                                        <multiselect v-model="form.project_stakeholders" :options="options" :multiple="true" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="Select Stakeholders" label="name" track-by="id" :preselect-first="false" >
                                                            <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                        </multiselect>
                                                        <h5><b-badge pill variant="info" v-for="stakeholder in form.project_stakeholders" v-bind:key="stakeholder.id">{{ stakeholder.name }} </b-badge></h5>
                                                    </div>                        
                                                </div>
                                                <div class="v-text-field__details"><div class="v-messages theme--light"><div class="v-messages__wrapper">{{form.errors.get('project_stakeholders')}}</div></div></div>
                                            </div>
                                        </div>
                                    </v-card>
                                </v-flex>
                                <v-flex xs6>
                                    <v-card color="white">
                                        <div class="v-input v-text-field v-input--is-label-active theme--light">
                                            <div class="v-input__control">
                                                <div class="v-input__slot">
                                                    <div class="v-text-field__slot multiselectwps">
                                                        <label aria-hidden="true" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Status</label>
                                                        <multiselect v-model="form.project_status" :options="status" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="Select Status" label="status_name" track-by="id" :preselect-first="false" >
                                                            <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                        </multiselect>
                                                    </div>                                   
                                                </div>
                                                <div class="v-text-field__details"><div class="v-messages theme--light"><div class="v-messages__wrapper">{{form.errors.get('project_status')}}</div></div></div>
                                            </div>
                                        </div>
                                    </v-card>
                                </v-flex>
                                <v-flex xs12>
                                    <v-card color="white">
                                        <v-progress-circular indeterminate v-show="isLoading" color="primary"></v-progress-circular>
                                        <v-btn type="submit" color="info" > Save</v-btn>
                                        <v-btn @click="closeForm"> Close</v-btn>
                                    </v-card>
                                </v-flex>
                                                      
                            </v-layout>
                        </v-container> 
                    </v-form>
                </v-app>
            </div>
        </div>
    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';
    import Multiselect from 'vue-multiselect';
    import Project from '../../models/Project';
    export default {
        components: {
            Datepicker,Multiselect, Project
        },
        props: ['projectid'],
        data() {
            return {
                form: new Form({
                    project_name: '',
                    project_desc: '',
                    project_start_date: new Date().toISOString().substr(0, 10),
                    project_end_date: new Date().toISOString().substr(0, 10),
                    project_managers: '',
                    project_stakeholders: '',
                    project_status: '',
                    project_budget: '',
                    program:'',
                    errors:'',
                }),
                options: [],
                status: [],
                start_date: false,
                end_date: false,
                programs:[],
                isLoading:true,
            }
        },
        methods: {
            onSubmit() {
                this.form.post('/project/store')
               .then(project => this.$emit('completed', project));
            },                                                  
            closeForm() {
                this.$emit('closeAddForm');
            },
            customFormatter(date) {
                return moment(date).format('YYYY-MM-DD');
            },
        },
        mounted() {
            // console.log('this is add company comp');
            Project.getPrograms(programs => this.programs = programs);
            Project.getResources(results => this.options = results);
            Project.getStatus(results => this.status = results);
            this.isLoading = false;
        }
    }
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

