<template>
    <div class="col-md-6">
        <div class="card card-default shadow-sm">
            <div class="card-header">Add New Scope</div>

            <div class="card-body">
                <form @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                    <!-- <input type="hidden" > -->

                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Title</label>

                        <div class="col-md-6">
                            <input id="crd_title" type="text" class="form-control" placeholder="Enter CRD title" v-model="form.crd_title" name="crd_title" value="" >

                            <!-- @if ($errors->has('project_name')) -->
                                <span class="invalid-feedback" role="alert" v-if="form.errors.has('crd_title')" v-text="form.errors.get('crd_title')"></span>
                            <!-- @endif -->
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <label for="crd_desc" class="col-md-4 col-form-label text-md-right">Description</label>

                        <div class="col-md-6">
                            <textarea id="crd_desc" class="form-control" placeholder="Enter CRD description" v-model="form.crd_desc" name="form.project_desc" value="" ></textarea>

                            <!-- @if ($errors->has('project_name')) -->
                                <span class="invalid-feedback" role="alert" v-if="form.errors.has('crd_desc')" v-text="form.errors.get('crd_desc')"></span>
                            <!-- @endif -->
                        </div>
                    </div>                    
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <label for="crd_status" class="col-md-4 col-form-label text-md-right">Status</label>
                        <div class="col-md-6">
                            <multiselect v-model="form.crd_status" :options="status" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="Select Status" label="status_name" track-by="id" :preselect-first="false" >
                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                            </multiselect>
                            <h5><b-badge pill variant="info" v-if="form.crd_status">{{ form.crd_status.status_name }}</b-badge></h5>
                            <!-- @if ($errors->has('project_name')) -->
                                <span class="invalid-feedback" role="alert" v-if="form.errors.has('crd_status')" v-text="form.errors.get('crd_status')"></span>
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
                            <input type="hidden" :value="form.project_id" name="form.project_id">
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
                    crd_title: '',
                    crd_desc: '',
                    crd_status: '',
                    project_id: this.$route.params.id,
                    errors:'',
                }),
                status: []
            }
        },
        methods: {
            onSubmit() {
                this.form.post('/scope/store')
               .then(scope => this.$emit('completed', scope));
            },                                                  
            closeAddForm() {
                this.$emit('closeAddForm');
            },
            customFormatter(date) {
                return moment(date).format('YYYY-MM-DD');
            },
        },
        mounted() {

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

