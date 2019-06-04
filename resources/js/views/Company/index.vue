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
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Managers</th>
                                            <!-- <th scope="col">Description</th> -->
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="company in companies">
                                            <td>{{ company.company_name }}</td>
                                            <td>{{ company.company_desc }}</td>
                                            <td>{{ company.company_manager }}</td>
                                            <!-- <td>{{ program.program_desc }}</td> -->
                                            <td>
                                                <button class="btn btn-sm btn-primary" @click="showEditCompany(company.id)"><font-awesome-icon icon="edit" ></font-awesome-icon></button>
                                                <!-- <button class="btn btn-sm btn-primary"><font-awesome-icon icon="edit" ></font-awesome-icon></button> -->
                                                <button class="btn btn-sm btn-danger"><font-awesome-icon icon="trash" ></font-awesome-icon></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <add-company v-if="addCompany" @completed="addNewCompany" @closeAddForm="closeForm"></add-company>
            <edit-company v-if="editCompany" :companyid="companyid" @completed="editCompany" @closeEditForm="closeForm"></edit-company>
        </div>
    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';
    import AddCompany from './AddCompany.vue';
    import EditCompany from './EditCompany.vue';
    import Company from '../../models/Company';
    export default {
        components: {
            Datepicker,AddCompany,EditCompany
        },
        data() {
            return{
                addCompany: false,
                editCompany: false,
                companyid:'',
                companies: [],
                cardWidth: 'col-md-10'
            }
        },
        created() {
            Company.all(companies => this.companies = companies)            
                // .then(({data}) => this.statuses = data)
        },
        methods: {
           showAddCompany() {
                this.addCompany = true;
                this.editCompany = false;
                this.cardWidth = 'col-md-6';
           },
           showEditCompany(id) {
                this.companyid = id;
                this.addCompany = false;
                this.editCompany = true;
                this.cardWidth = 'col-md-6';
           },
           addNewCompany(program) {
                this.companies.unshift(program);
                this.$toasted.success('Congratulations! Your new organizaions has been added successfully.')
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
