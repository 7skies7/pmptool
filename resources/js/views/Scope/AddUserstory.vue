<template>
    <div class="col-md-6">
        <div class="card card-default shadow-sm">
            <div class="card-header">Add New Story</div>

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
                              <!--   <v-flex xs12>
                                    <v-card color="white">
                                        <v-text-field type="number" :rules="rules.userstory_point" v-model="form.userstory_point" label="Userstory Point" placeholder="Userstory Point" :messages="form.errors.get('userstory_point')"></v-text-field>
                                    </v-card>
                                </v-flex> -->
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
    import Common from '../../models/Common.js';
    export default {
        components: {
            Datepicker,Multiselect
        },
        props: ['scopeid'],
        data() {
            return {
                form: new Form({
                    userstory_desc: '',
                    // userstory_point: '',
                    userstory_status: '',
                    userstory_priority: '',
                    project_id: this.$route.params.id,
                    cr_id: this.scopeid,
                    errors:'',
                }),
                status: [],
                priority: [],
                rules: {
                    userstory_point: [val => val < 21  || `Userstory point should be between 1 to 20`,val => val >= 1  || `Userstory point should be between 1 to 20`],
                },
                valid: true,
            }
        },
        methods: {
            onSubmit() {
                var self = this;
                this.form.post('/userstory/store')
               .then((scope) => {
                
                    this.$toasted.success('Congratulations! Your new User Story has been added successfully.');
                    this.$router.push({ name: 'userstory', params: { id: this.form.project_id, userstoryid: scope.id }})


                });
            },                                                  
            closeForm() {
                this.$emit('closeAddForm');
            },
            customFormatter(date) {
                return moment(date).format('YYYY-MM-DD');
            },
        },
        mounted() {
            Common.allStatus(status => this.status = status);
            Common.allPriority(priority => this.priority = priority);
            
        }
    }
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

