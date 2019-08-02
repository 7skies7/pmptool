<template>
    <div class="col-md-6">
        <div class="card card-default shadow-sm">
            <div class="card-header">Add New Company</div>

            <div class="card-body">
                <v-app id="editTask">
                    <v-form @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                        <v-container grid-list-md text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-card color="white">
                                        <v-text-field v-model="form.company_name" label="Organization Name" placeholder="Enter organization name" :messages="form.errors.get('company_name')"></v-text-field>
                                    </v-card>
                                </v-flex>
                                <v-flex xs12>
                                    <v-card color="white">
                                        <v-textarea v-model="form.company_desc" label="Organization Description" placeholder="Enter Organization Description" :messages="form.errors.get('company_desc')"></v-textarea>
                                    </v-card>
                                </v-flex>
                                <v-flex xs12>
                                    <v-card color="white">
                                        <div class="v-input v-text-field v-input--is-label-active theme--light">
                                            <div class="v-input__control">
                                                <div class="v-input__slot">
                                                    <div class="v-text-field__slot multiselectwps">
                                                        <label aria-hidden="true" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Managers</label>
                                                        <multiselect v-model="form.company_manager" :options="options" :multiple="true" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="Select Organization Manager" label="name" track-by="id" :preselect-first="false" >
                                                            <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                        </multiselect>
                                                        <h5><b-badge pill variant="info" v-for="manager in form.company_manager" v-bind:key="manager.id">{{ manager.name }} </b-badge></h5>
                                                    </div>                        
                                                </div>
                                                <div class="v-text-field__details"><div class="v-messages theme--light"><div class="v-messages__wrapper">{{form.errors.get('program_manager')}}</div></div></div>
                                            </div>
                                        </div>
                                    </v-card>
                                </v-flex>
                                <v-flex xs12>
                                    <v-card color="white">
                                        <v-progress-circular indeterminate v-show="isLoading" color="primary"></v-progress-circular>
                                        <v-btn type="submit" color="info" > Save</v-btn>
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
    export default {
        components: {
            Datepicker,Multiselect
        },
        props: ['companyid'],
        data() {
            return {
                form: new Form({
                    company_name: '',
                    company_desc: '',
                    company_manager: '',
                }),
                options: [],
                isLoading: false,
            }
        },
        methods: {
            onSubmit() {
                this.form.post('/company/store')
               .then(company => this.$emit('completed', company));
            },                                                  
            closeForm() {
                this.$emit('closeAddForm');
            }
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

