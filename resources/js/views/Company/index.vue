<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div :class="cardWidth">
                <div class="card card-default shadow-sm border-0">
                    <div class="card-header">   
                        <div class="sflex spacebetween">
                            <div class="childFlex">
                                <span>Organizations</span>
                            </div>
                            <div class="pb-11">
                                <v-btn color="info" v-if="isAddVisible" @click="showAddCompany">Add New</v-btn>
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
            <edit-company v-if="editCompany" :key="latestCompanies" :company="company" @updatecompleted="updateCompany" @closeEditForm="closeForm"></edit-company>
            <confirm ref="confirm"></confirm>
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
                company:'',
                companies: [],
                cardWidth: 'col-md-10',
                form: new Form(),
                latestCompanies: 0,
                isAddVisible: false,
            }
        },
        created() {
            Company.addaccess(addaccess => this.isAddVisible = addaccess);            
        },
        methods: {
           showAddCompany() {
                if(this.isAddVisible == false)
                {
                    window.location.href = "/403";
                }
                this.addCompany = true;
                this.editCompany = false;
                this.cardWidth = 'col-md-6';

           },
           showEditCompany(id) {
                this.editCompany = false;
                this.company = id;
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
                var self = this;
                this.$refs.confirm.open('Delete', 'Are you sure?', { color: 'red',showAgree: true })
                .then((confirm) => {
                    if(confirm){
                        this.form.post('/company/delete/'+companyid)
                        .then((company) => {
                            this.latestCompanies += 1
                            this.$toasted.success('Congratulations! Company has been deleted successfully.');  
                        })
                        .catch((error) => {
                            this.$refs.confirm.open('Delete Error', error.message, { color: 'red',showAgree: false });
                        });
                    }
                });
                

            },
            closeForm() {
                this.addCompany = false;
                this.editCompany = false;
                this.company = '';
                this.cardWidth = 'col-md-10';
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
