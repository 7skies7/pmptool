<template>
    <div class="col-md-6">
        <div class="card card-default shadow-sm">
            <div class="card-header">Edit Scope</div>
            <div class="card-body">
                <form @submit.prevent="onUpdate" @keydown="form.errors.clear()">
                    <!-- <input type="hidden" > -->

                    <div class="form-group row">
                        <label for="crd_title" class="col-md-4 col-form-label text-md-right">Name</label>

                        <div class="col-md-6">
                            <input id="crd_title" type="text" class="form-control" placeholder="Enter CRD title" v-model="form.crd_title" name="crd_title"  >

                            <!-- @if ($errors->has('project_name')) -->
                                <span class="invalid-feedback" role="alert" v-if="form.errors.has('crd_title')" v-text="form.errors.get('crd_title')"></span>
                            <!-- @endif -->
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <label for="crd_desc" class="col-md-4 col-form-label text-md-right">Description</label>

                        <div class="col-md-6">
                            <textarea id="crd_desc" class="form-control" placeholder="Enter CRD description" v-model="form.crd_desc" name="form.crd_desc" value="" ></textarea>

                            <!-- @if ($errors->has('project_name')) -->
                            <span class="invalid-feedback" role="alert" v-if="form.errors.has('crd_desc')" v-text="form.errors.get('crd_desc')"></span>

                            <!-- @endif -->
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <label for="crd_status" class="col-md-4 col-form-label text-md-right">Status</label>
                        <div class="col-md-6">
                            <multiselect v-model="form.crd_status" deselect-label="Can't remove this value" track-by="id" label="status_name" placeholder="Select one" :options="status" :searchable="false" :allow-empty="false">
                                <template slot="singleLabel" slot-scope="{ option }"><strong>{{ option.status_name }}</strong></template>
                            </multiselect>
                            <h5><b-badge pill variant="info" v-if="form.crd_status" v-text="form.crd_status.status_name">{{ form.crd_status.status_name }} </b-badge></h5>
                            <!-- @if ($errors->has('project_name')) -->
                                <span class="invalid-feedback" role="alert" v-if="form.errors.has('crd_status')" v-text="form.errors.get('crd_status')"></span>
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
    // Import component
    import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/vue-loading.css';
    export default {
        components: {
            Datepicker,Multiselect,Loading
        },
        props: ['scopeid'],
        data() {
            return {
                form: new Form({
                    crd_title: '',
                    crd_desc: '',
                    crd_status: '',
                    project_id: this.$route.params.id,
                    errors:'',
                }),
                status: [],
                isLoading:true,
                fullPage: true
            }
        },
        created() {
            
                axios.get('/project/getStatus').then((statuses) => {
                    this.status = statuses.data;  
                        axios.get('/scope/edit/'+this.scopeid).then((response) => {             
                            this.form.crd_title = response.data.crd_title;
                            this.form.crd_desc = response.data.crd_desc;
                            this.form.crd_status = response.data.status;
                            this.isLoading = false;
                        });    
                    });  
                
        },
        methods: {
            onUpdate() {
                this.form.post('/scope/update/'+this.scopeid)
               .then(scope => this.$emit('updatecompleted', scope));
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

