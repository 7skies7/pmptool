<template>
    <div class="col-md-6">
        <div class="card card-default shadow-sm">
            <div class="card-header">Add New Program</div>

            <div class="card-body">
                <v-app id="editTask">
                    <v-form @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                        <v-container grid-list-md text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-card color="white">
                                        <v-text-field v-model="form.program_name" label="Program Name" placeholder="Enter program name" :messages="form.errors.get('program_name')"></v-text-field>
                                    </v-card>
                                </v-flex>
                                <v-flex xs12>
                                    <v-card color="white">
                                        <v-textarea v-model="form.program_desc" label="Program Description" placeholder="Enter Program Description" :messages="form.errors.get('program_desc')"></v-textarea>
                                    </v-card>
                                </v-flex>
                                <v-flex xs6>
                                    <v-card color="white" >
                                        <v-menu v-model="start_date" :close-on-content-click="false" :nudge-right="40" lazy transition="scale-transition" offset-y full-width min-width="290px">
                                            <template v-slot:activator="{ on }">
                                                <v-text-field v-model="form.program_start_date" label="Select Start Date" prepend-icon="event" readonly v-on="on" :messages="form.errors.get('program_start_date')"></v-text-field>
                                          </template>
                                          <v-date-picker v-model="form.program_start_date" @input="start_date = false"></v-date-picker>
                                        </v-menu>
                                    </v-card>
                                </v-flex>
                                <v-flex xs6>
                                    <v-card color="white">
                                        <v-menu v-model="end_date" :close-on-content-click="false" :nudge-right="40" lazy transition="scale-transition" offset-y full-width min-width="290px">
                                            <template v-slot:activator="{ on }">
                                                <v-text-field v-model="form.program_end_date" label="Select End Date" prepend-icon="event" readonly v-on="on" :messages="form.errors.get('program_end_date')"></v-text-field>
                                          </template>
                                          <v-date-picker v-model="form.program_end_date" @input="end_date = false"></v-date-picker>
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
                                                        <label aria-hidden="true" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Organization</label>
                                                        <multiselect v-model="form.company" :options="companies" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="Select Organization" label="company_name" track-by="id" :preselect-first="false" >
                                                            <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                        </multiselect>
                                                    </div>                                   
                                                </div>
                                                <div class="v-text-field__details"><div class="v-messages theme--light"><div class="v-messages__wrapper">{{form.errors.get('company')}}</div></div></div>
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
                                                        <multiselect v-model="form.program_manager" :options="options" :multiple="true" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="Select Program Manager" label="name" track-by="id" :preselect-first="false" >
                                                            <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                        </multiselect>
                                                        <h5><b-badge pill variant="info" v-for="manager in form.program_manager" v-bind:key="manager.id">{{ manager.name }} </b-badge></h5>
                                                    </div>                        
                                                </div>
                                                <div class="v-text-field__details"><div class="v-messages theme--light"><div class="v-messages__wrapper">{{form.errors.get('program_manager')}}</div></div></div>
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
    import Program from '../../models/Program';

    export default {
        components: {
            Datepicker,Multiselect, Project, Program
        },
        props: ['programid'],
        data() {
            return {
                form: new Form({
                    program_name: '',
                    program_desc: '',
                    program_start_date: new Date().toISOString().substr(0, 10),
                    program_end_date: new Date().toISOString().substr(0, 10),
                    program_manager: '',
                    company: '',
                    errors:'',
                }),
                options: [],
                companies:[],
                start_date: false,
                end_date: false,
                isLoading: false,
            }
        },
        methods: {
            onSubmit() {
                this.form.post('/program/store')
               .then(program => this.$emit('completed', program));
            },                                                  
            closeForm() {
                this.$emit('closeAddForm');
            },
        },
        mounted() {
            // console.log('this is add company comp');
            Project.getResources(results => this.options = results);
            Program.getCompanies(results => this.companies = results);

        }
    }
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

