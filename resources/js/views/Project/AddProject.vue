<template>
    <div class="col-md-6">
        <div class="card card-default shadow-sm">
            <div class="card-header">Add New Project</div>

            <div class="card-body">
                <form @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                    <!-- <input type="hidden" > -->

                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                        <div class="col-md-6">
                            <input id="project_name" type="text" class="form-control" placeholder="Enter project name" v-model="form.project_name" name="project_name" value="" >

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
                                <span class="invalid-feedback" role="alert" v-if="form.errors.has('project_desc')" v-text="form.errors.get('project_desc')"></span>
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
                                <span class="invalid-feedback" role="alert" v-if="form.errors.has('project_start_date')" v-text="form.errors.get('project_start_date')"></span>
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
                                <span class="invalid-feedback" role="alert" v-if="form.errors.has('project_end_date')" v-text="form.errors.get('project_end_date')"></span>
                            <!-- @endif -->
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <label for="project_managers" class="col-md-4 col-form-label text-md-right">Project Manager</label>
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
                            <multiselect v-model="form.project_status" :options="status" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="Select Status" label="status_name" track-by="id" :preselect-first="false" >
                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                            </multiselect>
                            <h5><b-badge pill variant="info" v-if="form.project_status">{{ form.project_status.status_name }}</b-badge></h5>
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
                            <button type="submit" class="btn btn-primary" :disabled="form.errors.any()">
                                Save
                            </button>
                            <button type="button" class="btn btn-primary" @click="closeAddForm">
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
    export default {
        components: {
            Datepicker,Multiselect
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
                status: []
            }
        },
        methods: {
            onSubmit() {
                this.form.post('/project/store')
               .then(project => this.$emit('completed', project));
            },                                                  
            closeAddForm() {
                this.$emit('closeAddForm');
            },
            customFormatter(date) {
                return moment(date).format('YYYY-MM-DD');
            },
        },
        mounted() {
            // console.log('this is add company comp');
            $.ajax({
                url:"/company/getResources",
                method:"GET",
                dataType: "JSON",
                success: results => this.options = results
            });

            $.ajax({
                url:"/project/getStatus",
                method:"GET",
                dataType: "JSON",
                success: results => this.status = results
            });
        }
    }
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

