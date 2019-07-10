<template>
    <div class="col-md-6">
        <div class="card card-default shadow-sm">
            <div class="card-header">Add New Story</div>

            <div class="card-body">
                <form @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                    <!-- <input type="hidden" > -->
                    <div class="form-group row">
                        <label for="userstory_desc" class="col-md-4 col-form-label text-md-right">User Story</label>

                        <div class="col-md-6">
                            <textarea id="userstory_desc" class="form-control" placeholder="Enter User story" v-model="form.userstory_desc" name="form.userstory_desc" value="" ></textarea>

                            <!-- @if ($errors->has('project_name')) -->
                                <span class="invalid-feedback" role="alert" v-if="form.errors.has('userstory_desc')" v-text="form.errors.get('crd_desc')"></span>
                            <!-- @endif -->
                        </div>
                    </div>                    
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <label for="userstory_point" class="col-md-4 col-form-label text-md-right">Story Points</label>

                        <div class="col-md-6">
                            <input id="userstory_point" type="number" class="form-control" placeholder="Enter Userstory point" v-model="form.userstory_point" name="userstory_point" value="" >

                            <!-- @if ($errors->has('project_name')) -->
                                <span class="invalid-feedback" role="alert" v-if="form.errors.has('userstory_point')" v-text="form.errors.get('userstory_point')"></span>
                            <!-- @endif -->
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group row">
                        <label for="userstory_status" class="col-md-4 col-form-label text-md-right">Status</label>
                        <div class="col-md-6">
                            <multiselect v-model="form.userstory_status" :options="status" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="Select Status" label="status_name" track-by="id" :preselect-first="false" >
                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                            </multiselect>
                            <h5><b-badge pill variant="info" v-if="form.userstory_status">{{ form.userstory_status.status_name }}</b-badge></h5>
                            <!-- @if ($errors->has('project_name')) -->
                                <span class="invalid-feedback" role="alert" v-if="form.errors.has('userstory_status')" v-text="form.errors.get('userstory_status')"></span>
                            <!-- @endif -->
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <label for="userstory_priority" class="col-md-4 col-form-label text-md-right">Priority</label>
                        <div class="col-md-6">
                            <multiselect v-model="form.userstory_priority" :options="priority" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="Select Priority" label="priority_type" track-by="id" :preselect-first="false" >
                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                            </multiselect>
                            <h5><b-badge pill variant="info" v-if="form.userstory_priority">{{ form.userstory_priority.priority_type }}</b-badge></h5>
                            <!-- @if ($errors->has('project_name')) -->
                                <span class="invalid-feedback" role="alert" v-if="form.errors.has('userstory_priority')" v-text="form.errors.get('userstory_priority')"></span>
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
        props: ['scopeid'],
        data() {
            return {
                form: new Form({
                    userstory_desc: '',
                    userstory_point: '',
                    userstory_status: '',
                    userstory_priority: '',
                    project_id: this.$route.params.id,
                    cr_id: this.scopeid,
                    errors:'',
                }),
                status: [],
                priority: []
            }
        },
        methods: {
            onSubmit() {
                this.form.post('/userstory/store')
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
            $.ajax({
                url:"/project/getPriority",
                method:"GET",
                dataType: "JSON",
                success: results => this.priority = results
            });
        }
    }
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

