<template>
    <div class="col-md-6">
        <div class="card card-default shadow-sm">
            <div class="card-header">Edit Project</div>
            <div class="card-body">
                <form @submit.prevent="onUpdate" @keydown="form.errors.clear()">
                    <!-- <input type="hidden" > -->

                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                        <div class="col-md-6">
                            <input id="project_name" type="text" class="form-control" placeholder="Enter project name" v-model="form.project_name" name="project_name"  >

                            <!-- @if ($errors->has('project_name')) -->
                                <span class="invalid-feedback" role="alert" v-if="form.errors.has('project_name')" v-text="form.errors.get('project_name')"></span>
                            <!-- @endif -->
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <label for="project_desc" class="col-md-4 col-form-label text-md-right">Description</label>

                        <div class="col-md-6">
                            <textarea id="project_desc" class="form-control" placeholder="Enter project description" v-model="form.project_desc" name="form.project_desc" value="" ></textarea>

                            <!-- @if ($errors->has('project_name')) -->
                                <span class="invalid-feedback" role="alert">
                                    <!-- <strong>{{ $errors->first('project_name') }}</strong> -->
                                </span>
                            <!-- @endif -->
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <label for="project_start_date" class="col-md-4 col-form-label text-md-right">Start Date</label>

                        <div class="col-md-6">
                            <!-- <input id="project_start_date" v-model="form.project_start_date" type="text" class="form-control" name="project_start_date" value="" > -->
                            <Datepicker v-model="form.project_start_date" input-class="form-control" :format="customFormatter"></Datepicker>
                            <!-- @if ($errors->has('project_name')) -->
                                <span class="invalid-feedback" role="alert">
                                    <!-- <strong>{{ $errors->first('project_name') }}</strong> -->
                                </span>
                            <!-- @endif -->
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <label for="project_end_date" class="col-md-4 col-form-label text-md-right">End Date</label>

                        <div class="col-md-6">
                            <!-- <input id="project_end_date" type="text" v-model="form.project_end_date" class="form-control" name="project_end_date" value="" > -->
                            <Datepicker v-model="form.project_end_date" input-class="form-control" :format="customFormatter"></Datepicker>

                            <!-- @if ($errors->has('project_name')) -->
                                <span class="invalid-feedback" role="alert">
                                    <!-- <strong>{{ $errors->first('project_name') }}</strong> -->
                                </span>
                            <!-- @endif -->
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <label for="project_manager" class="col-md-4 col-form-label text-md-right">Project Manager</label>
                        <div class="col-md-6">
                            <multiselect v-model="form.project_managers" :options="options" :multiple="true" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="Select Manager" label="name" track-by="id" :preselect-first="false" >
                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                            </multiselect>
                            <h5><b-badge pill variant="info" v-for="manager in form.project_managers" v-bind:key="manager.id">{{ manager.name }} </b-badge></h5>
                            <!-- @if ($errors->has('project_name')) -->
                                <span class="invalid-feedback" role="alert" v-if="form.errors.has('project_managers')" v-text="form.errors.get('project_managers')"></span>
                            <!-- @endif -->
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <label for="project_stakeholders" class="col-md-4 col-form-label text-md-right">Stakeholders</label>
                        <div class="col-md-6">
                            <multiselect v-model="form.project_stakeholders" :options="options" :multiple="true" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="Select Stakeholders" label="name" track-by="id" :preselect-first="false" >
                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                            </multiselect>
                            <h5><b-badge pill variant="info" v-for="stakeholder in form.project_stakeholders" v-bind:key="stakeholder.id">{{ stakeholder.name }} </b-badge></h5>
                            <!-- @if ($errors->has('project_name')) -->
                                <span class="invalid-feedback" role="alert" v-if="form.errors.has('project_stakeholders')" v-text="form.errors.get('project_stakeholders')"></span>
                            <!-- @endif -->
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <label for="project_status" class="col-md-4 col-form-label text-md-right">Status</label>
                        <div class="col-md-6">
                            <multiselect v-model="form.project_status" deselect-label="Can't remove this value" track-by="id" label="status_name" placeholder="Select one" :options="status" :searchable="false" :allow-empty="false">
                                <template slot="singleLabel" slot-scope="{ option }"><strong>{{ option.status_name }}</strong></template>
                            </multiselect>
                            <h5><b-badge pill variant="info" v-if="form.project_status" v-text="form.project_status.status_name">{{ form.project_status.status_name }} </b-badge></h5>
                            <!-- @if ($errors->has('project_name')) -->
                                <span class="invalid-feedback" role="alert" v-if="form.errors.has('project_status')" v-text="form.errors.get('project_status')"></span>
                            <!-- @endif -->
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <label for="project_budget" class="col-md-4 col-form-label text-md-right">Budget</label>

                        <div class="col-md-6">
                            <input id="project_budget" type="text" class="form-control" placeholder="Enter project budget" v-model="form.project_budget" name="project_budget" value="" >

                            <!-- @if ($errors->has('project_name')) -->
                                <span class="invalid-feedback" role="/" v-if="form.errors.has('project_budget')" v-text="form.errors.get('project_budget')"></span>
                            <!-- @endif -->
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <v-progress-circular indeterminate v-show="isLoading" color="primary"></v-progress-circular>
                            <button v-show="!isLoading" type="submit" class="btn btn-primary" :disabled="form.errors.any()" >
                                Save
                            </button>
                            <button v-show="!isLoading" type="button" class="btn btn-primary" @click="closeEditForm">
                                Close
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';
    import Multiselect from 'vue-multiselect';
    // Import component
    import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/vue-loading.css';
    export default {
        components: {
            Datepicker,Multiselect,Loading
        },
        props: ['projectid'],
        data() {
            return {
                form: new Form({
                    project_name: '',
                    project_desc: '',
                    project_start_date: '',
                    project_end_date: '',
                    project_managers: '',
                    project_stakeholders: '',
                    project_status: '',
                    project_budget: '',
                    errors:'',
                }),
                options: [],
                status: [],
                isLoading:true,
                fullPage: true
            }
        },
        created() {
            axios.get('/company/getResources').then((resources) => {
                this.options = resources.data;
                axios.get('/project/getStatus').then((statuses) => {
                    this.status = statuses.data;  
                        axios.get('/project/edit/'+this.projectid).then((response) => {             
                            this.form.project_name = response.data.project_name;
                            this.form.project_desc = response.data.project_desc;
                            this.form.project_start_date = response.data.project_start_date;
                            this.form.project_end_date = response.data.project_end_date;
                            this.form.project_status = response.data.status;
                            this.form.project_managers = response.data.project_managers;
                            this.form.project_stakeholders = response.data.project_stakeholders;
                            this.form.project_budget = response.data.project_budget;
                            
                            this.isLoading = false;
                        });    
                    });  
                });
        },
        methods: {
            onUpdate() {
                this.form.post('/project/update/'+this.projectid)
               .then(projects => this.$emit('updatecompleted', projects));
            },                                                  
            closeEditForm() {
                this.$emit('closeEditForm');
            },
            customFormatter(date) {
                return moment(date).format('YYYY-MM-DD');
            },
        },
        mounted() {
            this.isLoading = true;
            // console.log("this is edit company comp");
        }
    }
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

