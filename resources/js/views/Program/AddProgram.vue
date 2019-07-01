<template>
    <div class="col-md-6">
        <div class="card card-default shadow-sm">
            <div class="card-header">Add New Program</div>

            <div class="card-body">
                <form @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                    <!-- <input type="hidden" > -->

                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                        <div class="col-md-6">
                            <input id="program_name" type="text" class="form-control" placeholder="Enter program name" v-model="form.program_name" name="program_name" value="" >
                            <span class="invalid-feedback" role="alert" v-if="form.errors.has('program_name')" v-text="form.errors.get('program_name')"></span>

                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <label for="program_desc" class="col-md-4 col-form-label text-md-right">Description</label>

                        <div class="col-md-6">
                            <textarea id="program_desc" class="form-control" placeholder="Enter program description" v-model="form.program_desc" name="form.program_desc" value="" ></textarea>
                            <span class="invalid-feedback" role="alert" v-if="form.errors.has('program_name')" v-text="form.errors.get('program_name')"></span>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <label for="program_start_date" class="col-md-4 col-form-label text-md-right">Start Date</label>

                        <div class="col-md-6">
                            <Datepicker v-model="form.program_start_date" input-class="form-control" :format="customFormatter"></Datepicker>
                            <span class="invalid-feedback" role="alert" v-if="form.errors.has('program_name')" v-text="form.errors.get('program_name')"></span>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <label for="program_end_date" class="col-md-4 col-form-label text-md-right">End Date</label>

                        <div class="col-md-6">
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
                            <h5><b-badge pill variant="info" v-for="manager in form.program_manager" v-bind:key="manager.id">{{ manager.name }} </b-badge></h5>
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
                options: []
            }
        },
        methods: {
            onSubmit() {
                this.form.post('/program/store')
               .then(program => this.$emit('completed', program));
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
        }
    }
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

