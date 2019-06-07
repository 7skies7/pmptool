<template>
    <div class="col-md-6">
        <div class="card card-default shadow-sm">
            <div class="card-header">Edit Program</div>
            <div class="card-body">
                <form @submit.prevent="onUpdate" @keydown="form.errors.clear()">
                    <!-- <input type="hidden" > -->

                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                        <div class="col-md-6">
                            <input id="program_name" type="text" class="form-control" placeholder="Enter program name" v-model="form.program_name" name="program_name"  >

                            <!-- @if ($errors->has('program_name')) -->
                                <span class="invalid-feedback" role="alert" v-if="form.errors.has('program_name')" v-text="form.errors.get('program_name')"></span>
                            <!-- @endif -->
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <label for="program_desc" class="col-md-4 col-form-label text-md-right">Description</label>

                        <div class="col-md-6">
                            <textarea id="program_desc" class="form-control" placeholder="Enter program description" v-model="form.program_desc" name="form.program_desc" value="" ></textarea>

                            <!-- @if ($errors->has('program_name')) -->
                                <span class="invalid-feedback" role="alert">
                                    <!-- <strong>{{ $errors->first('program_name') }}</strong> -->
                                </span>
                            <!-- @endif -->
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <label for="program_start_date" class="col-md-4 col-form-label text-md-right">Start Date</label>

                        <div class="col-md-6">
                            <!-- <input id="program_start_date" v-model="form.program_start_date" type="text" class="form-control" name="program_start_date" value="" > -->
                            <Datepicker v-model="form.program_start_date" input-class="form-control" :format="customFormatter"></Datepicker>
                            <!-- @if ($errors->has('program_name')) -->
                                <span class="invalid-feedback" role="alert">
                                    <!-- <strong>{{ $errors->first('program_name') }}</strong> -->
                                </span>
                            <!-- @endif -->
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <label for="program_end_date" class="col-md-4 col-form-label text-md-right">End Date</label>

                        <div class="col-md-6">
                            <!-- <input id="program_end_date" type="text" v-model="form.program_end_date" class="form-control" name="program_end_date" value="" > -->
                            <Datepicker v-model="form.program_end_date" input-class="form-control" :format="customFormatter"></Datepicker>

                            <!-- @if ($errors->has('program_name')) -->
                                <span class="invalid-feedback" role="alert">
                                    <!-- <strong>{{ $errors->first('program_name') }}</strong> -->
                                </span>
                            <!-- @endif -->
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <label for="program_manager" class="col-md-4 col-form-label text-md-right">Program Manager</label>
                        <div class="col-md-6">
                            <multiselect v-model="form.program_manager" :options="options" :multiple="true" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="Select Manager" label="name" track-by="id" :preselect-first="false" >
                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                            </multiselect>
                            <h5><b-badge pill variant="info" v-for="manager in form.program_manager" v-bind:key="manager.id" v-bind:text="manager.name">{{ manager.name }} </b-badge></h5>
                            <!-- @if ($errors->has('program_name')) -->
                                <span class="invalid-feedback" role="alert">
                                    <!-- <strong>{{ $errors->first('program_name') }}</strong> -->
                                </span>
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
        props: ['programid'],
        data() {
            return {
                form: new Form({
                    program_name: '',
                    program_desc: '',
                    program_start_date: '',
                    program_end_date: '',
                    program_manager: '',
                }),
                options: [],
                isLoading: false,
                fullPage: true
            }
        },
        created() {
            axios.get('/company/getResources').then((resources) => {
                        
                        this.options = resources.data;
                        axios.get('/program/edit/'+this.programid).then((response) => {             
                            this.form.program_name = response.data.program_name;
                            this.form.program_desc = response.data.program_desc;
                            this.form.program_start_date = response.data.program_start_date;
                            this.form.program_end_date = response.data.program_end_date;
                            this.form.program_manager = [{id:response.data.user[0].id, name:response.data.user[0].first_name+ " " + response.data.user[0].last_name}];
                            this.isLoading = false;
                        });    
                });
        },
        methods: {
            onUpdate() {
                this.form.post('/program/update/'+this.programid)
               .then(programs => this.$emit('updatecompleted', programs));
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

