<template>
    <div class="col-md-6">
        <div class="card card-default shadow-sm">
            <div class="card-header">Add New User</div>

            <div class="card-body">
                <v-app id="editTask">
                    <v-form @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                        <v-container grid-list-md text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs6>
                                    <v-card color="white">
                                        <v-text-field v-model="form.first_name" label="First Name" placeholder="First Name" :messages="form.errors.get('first_name')"></v-text-field>
                                    </v-card>
                                </v-flex>
                                <v-flex xs6>
                                    <v-card color="white">
                                        <v-text-field v-model="form.last_name" label="Last Name" placeholder="Last Name" :messages="form.errors.get('last_name')"></v-text-field>
                                    </v-card>
                                </v-flex>
                                <v-flex xs12>
                                    <v-card color="white">
                                        <v-text-field v-model="form.email" label="Email" placeholder="Email" :messages="form.errors.get('email')"></v-text-field>
                                    </v-card>
                                </v-flex>
                                <v-flex xs12>
                                    <v-card color="white">
                                        <v-text-field v-model="form.alternate_email" label="Alternate Email" placeholder="Alternate Email" :messages="form.errors.get('alternate_email')"></v-text-field>
                                    </v-card>
                                </v-flex>
                                <v-flex xs12>
                                    <v-card color="white">
                                        <div class="v-input v-text-field v-input--is-label-active theme--light">
                                            <div class="v-input__control">
                                                <div class="v-input__slot">
                                                    <div class="v-text-field__slot multiselectwps">
                                                        <label aria-hidden="true" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Roles</label>
                                                        <multiselect v-model="form.roles" :options="roles" :multiple="true" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="Select Roles" label="role_title" track-by="id" :preselect-first="false" >
                                                            <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                        </multiselect>
                                                        <h5><b-badge pill variant="info" v-for="role in form.roles" v-bind:key="role.id">{{ role.role_title }} </b-badge></h5>
                                                    </div>                        
                                                </div>
                                                <div class="v-text-field__details"><div class="v-messages theme--light"><div class="v-messages__wrapper">{{form.errors.get('roles')}}</div></div></div>
                                            </div>
                                        </div>
                                    </v-card>
                                </v-flex>
                                <v-flex xs12>
                                    <v-card color="white">
                                        <div class="v-input v-text-field v-input--is-label-active theme--light">
                                            <div class="v-input__control">
                                                <div class="v-input__slot">
                                                    <div class="v-text-field__slot multiselectwps">
                                                        <label aria-hidden="true" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Organizations</label>
                                                        <multiselect v-model="form.companies" :options="companies" :multiple="true" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="Select Organizations" label="company_name" track-by="id" :preselect-first="false" >
                                                            <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                        </multiselect>
                                                        <h5><b-badge pill variant="info" v-for="company in form.companies" v-bind:key="company.id">{{ company.company_name }} </b-badge></h5>
                                                    </div>                        
                                                </div>
                                                <div class="v-text-field__details"><div class="v-messages theme--light"><div class="v-messages__wrapper">{{form.errors.get('companies')}}</div></div></div>
                                            </div>
                                        </div>
                                    </v-card>
                                </v-flex>
                                <v-flex xs12>
                                    <v-card color="white">
                                        <v-btn type="submit" color="info"> Save</v-btn>
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
    import User from '../../models/User';
    import Role from '../../models/Role';
    import Company from '../../models/Company';
    export default {
        components: {
            Datepicker,Multiselect, Role, Company
        },
        props: ['programid'],
        data() {
            return {
                form: new Form({
                    first_name: '',
                    last_name: '',
                    email: '',
                    alternate_email: '',
                    roles: '',
                    companies: '',
                    errors:'',
                }),
                roles: [],
                companies:[]
            }
        },
        created() {
            Role.all(roles => this.roles = roles);
            Company.fetchCompanies(companies => this.companies = companies);
        },
        methods: {
            onSubmit() {
                this.form.post('/user/store')
               .then(user => this.$emit('completed', user));
            },                                                  
            closeForm() {
                this.$emit('closeForm');
            },
            customFormatter(date) {
                return moment(date).format('YYYY-MM-DD');
            },
        },
        mounted() {
            // console.log('this is add company comp');
            
        }
    }
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

