<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div :class="cardWidth">
                <div class="card card-default shadow-sm border-0">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="flex-1">
                                <img src="/svg/project.svg" class="header-svg"/>
                                <span>Organizations</span>
                            </div>
                            <div class="pb-11">
                                <button class="btn btn-add float-right" @click="showAddCompany">Add New</button>
                            </div>
                        </div>
                    </div>
                <div class="card-body p-0">
                        <div class="d-flex flex-column">
                            <div class="flex-1">
                                <grid :key="latestCompanies" @showeditcompany="showEditCompany" @deletecompany="deleteCompany"></grid>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <add-company v-if="addCompany" :key="latestCompanies" @completed="addNewCompany" @closeAddForm="closeForm"></add-company>
            <edit-company v-if="editCompany" :key="latestCompanies" :companyid="companyid" @updatecompleted="updateCompany" @closeEditForm="closeForm"></edit-company>
        </div>
    </div>
    
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';
    import AddCompany from './AddCompany.vue';
    import EditCompany from './EditCompany.vue';
    import Grid from './Grid.vue';
    import Company from '../../models/Company';
    export default {
        components: {
            Datepicker,AddCompany,EditCompany,Grid
        },
        data() {
            return{
                addCompany: false,
                editCompany: false,
                companyid:'',
                companies: [],
                cardWidth: 'col-md-10',
                form: new Form(),
                latestCompanies: 0
            }
        },
        created() {
            // Company.all(companies => this.companies = companies)            
                // .then(({data}) => this.statuses = data)
        },
        methods: {
           showAddCompany() {
                this.addCompany = true;
                this.editCompany = false;
                this.cardWidth = 'col-md-6';
           },
           showEditCompany(id) {
                this.editCompany = false;
                this.companyid = id;
                this.addCompany = false;
                this.editCompany = true;
                this.cardWidth = 'col-md-6';
           },
           addNewCompany(company) {
                // this.companies.unshift(company);
                this.$toasted.success('Congratulations! Your new organization has been added successfully.');
                this.latestCompanies += 1;
                this.closeForm();
            },
            updateCompany(companies) {
                this.companies = companies;
                this.$toasted.success('Congratulations! Organization has been updated successfully.');
                this.latestCompanies += 1;
                this.closeForm();
            },
            deleteCompany(companyid) {
                this.form.post('/company/delete/'+companyid)
                    .then(company => this.latestCompanies += 1);
                    this.$toasted.success('Congratulations! Organization has been deleted successfully.');  
                    this.latestCompanies += 1;

            },
            closeForm() {
                this.addCompany = false;
                this.editCompany = false;
                this.companyid = '';
                this.cardWidth = 'col-md-10';
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
