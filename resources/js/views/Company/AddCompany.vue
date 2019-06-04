<template>
    <div class="col-md-6">
        <div class="card card-default shadow-sm">
            <div class="card-header">Add New Company</div>

            <div class="card-body">
                <form @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                    <!-- <input type="hidden" > -->

                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                        <div class="col-md-6">
                            <input id="company_name" type="text" class="form-control" placeholder="Enter organization name" v-model="form.company_name" name="company_name" value="" >

                            <!-- @if ($errors->has('program_name')) -->
                                <span class="invalid-feedback" role="alert" v-if="form.errors.has('company_name')" v-text="form.errors.get('company_name')"></span>
                            <!-- @endif -->
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <label for="company_desc" class="col-md-4 col-form-label text-md-right">Description</label>

                        <div class="col-md-6">
                            <textarea id="company_desc" class="form-control" placeholder="Enter organization description" v-model="form.company_desc" name="form.company_desc" value="" ></textarea>

                            <!-- @if ($errors->has('program_name')) -->
                                <span class="invalid-feedback" role="alert">
                                    <!-- <strong>{{ $errors->first('program_name') }}</strong> -->
                                </span>
                            <!-- @endif -->
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <label for="company_manager" class="col-md-4 col-form-label text-md-right">Organization Manager</label>
                        <div class="col-md-6">
                            <multiselect v-model="form.company_manager" :options="options" :multiple="true" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="Select Manager" label="name" track-by="id" :preselect-first="false" >
                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                            </multiselect>
                            <h5><b-badge pill variant="info" v-for="manager in form.company_manager">{{ manager.name }} </b-badge></h5>
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
        data() {
            return {
                form: new Form({
                    company_name: '',
                    company_desc: '',
                    company_manager: '',
                }),
                options: []
            }
        },
        methods: {
            onSubmit() {
                this.form.post('/company/store')
               .then(company => this.$emit('completed', company));
            },                                                  
            closeAddForm() {
                this.$emit('closeAddForm');
            }
        },
        mounted() {
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

