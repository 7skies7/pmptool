<template>
    <div class="col-md-6">
        <div class="card card-default shadow-sm">
            <div class="card-header">Edit User Story</div>
            <div class="card-body">
                <v-app id="editTask">
                    <v-form v-model="valid" @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                        <v-container grid-list-md text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-card color="white">
                                        <v-text-field v-model="form.userstory_desc" label="Userstory Description" placeholder="Userstory Description" :messages="form.errors.get('userstory_desc')"></v-text-field>
                                    </v-card>
                                </v-flex>
                                <v-flex xs12>
                                    <v-card color="white">
                                        <v-text-field type="number" :rules="rules.userstory_point" v-model="form.userstory_point" label="Userstory Point" placeholder="Userstory Point" :messages="form.errors.get('userstory_point')"></v-text-field>
                                    </v-card>
                                </v-flex>
                                <v-flex xs6>
                                    <v-card color="white">
                                        <div class="v-input v-text-field v-input--is-label-active theme--light">
                                            <div class="v-input__control">
                                                <div class="v-input__slot">
                                                    <div class="v-text-field__slot multiselectwps">
                                                        <label aria-hidden="true" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Status</label>
                                                        <multiselect v-model="form.userstory_status" :options="status" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="Select Status" label="status_name" track-by="id" :preselect-first="false" >
                                                            <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                        </multiselect>
                                                    </div>                                   
                                                </div>
                                                <div class="v-text-field__details"><div class="v-messages theme--light"><div class="v-messages__wrapper">{{form.errors.get('userstory_status')}}</div></div></div>
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
                                                        <label aria-hidden="true" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Priority</label>
                                                        <multiselect v-model="form.userstory_priority" :options="priority" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="Select Priority" label="priority_type" track-by="id" :preselect-first="false" >
                                                            <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                        </multiselect>
                                                    </div>
                                                </div>
                                                <div class="v-text-field__details"><div class="v-messages theme--light"><div class="v-messages__wrapper">{{form.errors.get('userstory_priority')}}</div></div></div>
                                            </div>
                                        </div>
                                    </v-card>
                                </v-flex>
                                <v-flex xs12>
                                    <v-card color="white">
                                        <v-btn :disabled="!valid" type="submit" color="info"> Save</v-btn>
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
    // Import component
    import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/vue-loading.css';
    export default {
        components: {
            Datepicker,Multiselect,Loading
        },
        props: ['userstoryid'],
        data() {
            return {
                form: new Form({
                    userstory_desc: '',
                    userstory_point: '',
                    userstory_status: '',
                    userstory_priority: '',
                    project_id: this.$route.params.id,
                    userstory_id: this.userstoryid,
                    errors:'',
                }),
                status: [],
                priority: [],
                isLoading: false,
                rules: {
                    userstory_point: [val => val < 21  || `Userstory point should be between 1 to 20`,val => val >= 1  || `Userstory point should be between 1 to 20`],
                },
                valid: true,
            }
        },
        created() {
            axios.get('/project/getStatus').then((statuses) => {
                this.status = statuses.data;  
                    axios.get('/project/getPriority').then((priorities) => {
                        this.priority = priorities.data;  
                        axios.get('/userstory/edit/'+this.userstoryid).then((response) => {
                            this.form.userstory_desc = response.data.userstory_desc;
                            this.form.userstory_point = response.data.userstory_point;
                            this.form.userstory_status = response.data.status;
                            this.form.userstory_priority = response.data.priority;
                            this.isLoading = false;
                        });    
                    });    
            });  
        },
        methods: {
            onSubmit() {
                this.form.post('/userstory/update/'+this.userstoryid)
               .then(userstory => this.$emit('updatecompleted', userstory));
            },                                                  
            closeForm() {
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

