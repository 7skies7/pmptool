<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div :class="cardWidth">
                <div class="card card-default shadow-sm border-0">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="flex-1">
                                <img src="/svg/project.svg" class="header-svg"/>
                                <span>Programs</span>
                            </div>
                            <div class="pb-11">
                                <button class="btn btn-add float-right" @click="showaddProgram">Add New</button>
                            </div>
                        </div>
                    </div>
                <div class="card-body p-0">
                        <div class="d-flex flex-column">
                            <div class="flex-1">
                                <grid :key="latestPrograms" @showeditprogram="showEditProgram" @deleteprogram="deleteprogram"></grid>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <add-program v-if="addProgram" :key="latestPrograms" @completed="addNewProgram" @closeAddForm="closeForm"></add-program>
            <edit-program v-if="editProgram" :key="latestPrograms" :programid="programid" @updatecompleted="updateProgram" @closeEditForm="closeForm"></edit-program>
        </div>
    </div>
    
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';
    import addProgram from './addProgram.vue';
    import editProgram from './editProgram.vue';
    import Grid from './Grid.vue';
    import Program from '../../models/Program';
    export default {
        components: {
            Datepicker,addProgram,editProgram,Grid
        },
        data() {
            return{
                addProgram: false,
                editProgram: false,
                programid:'',
                companies: [],
                cardWidth: 'col-md-10',
                form: new Form(),
                latestPrograms: 0
            }
        },
        created() {
            // Company.all(companies => this.companies = companies)            
                // .then(({data}) => this.statuses = data)
        },
        methods: {
           showaddProgram() {
                this.addProgram = true;
                this.editProgram = false;
                this.cardWidth = 'col-md-6';
           },
           showEditProgram(id) {
                this.editProgram = false;
                this.programid = id;
                this.addProgram = false;
                this.editProgram = true;
                this.cardWidth = 'col-md-6';
           },
           addNewProgram(company) {
                // this.companies.unshift(company);
                this.$toasted.success('Congratulations! Your new program has been added successfully.');
                this.latestPrograms += 1;
                this.closeForm();
            },
            updateProgram(companies) {
                this.companies = companies;
                this.$toasted.success('Congratulations! Program has been updated successfully.');
                this.latestPrograms += 1;
                this.closeForm();
            },
            deleteprogram(programid) {
                this.form.post('/company/delete/'+programid)
                    .then(company => this.latestPrograms += 1);
                    this.$toasted.success('Congratulations! Organization has been deleted successfully.');  
                    this.latestPrograms += 1;

            },
            closeForm() {
                this.addProgram = false;
                this.editProgram = false;
                this.programid = '';
                this.cardWidth = 'col-md-10';
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
