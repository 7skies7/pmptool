<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div :class="cardWidth">
                <div class="card card-default shadow-sm border-0">
                    <div class="card-header">
                        <div class="sflex spacebetween">
                            <div class="childFlex">
                                <span>Programs</span>
                            </div>
                            <div class="pb-11">
                                <v-btn color="info" v-if="isAddVisible" @click="showaddProgram">Add New</v-btn>
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
            <edit-program v-if="editProgram" :key="latestPrograms" :program="program" @updatecompleted="updateProgram" @closeEditForm="closeForm"></edit-program>
            <confirm ref="confirm"></confirm>

        </div>
    </div>
    
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';
    import addProgram from './AddProgram.vue';
    import editProgram from './EditProgram.vue';
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
                program:'',
                companies: [],
                cardWidth: 'col-md-10',
                form: new Form(),
                latestPrograms: 0,
                isAddVisible: true,
            }
        },
        created() {
            Program.addaccess(addaccess => this.isAddVisible = addaccess); 
        },
        methods: {
           showaddProgram() {
                if(this.isAddVisible == false)
                {
                    window.location.href = "/403";
                }
                this.addProgram = true;
                this.editProgram = false;
                this.cardWidth = 'col-md-6';
           },
           showEditProgram(id) {
                this.editProgram = false;
                this.program = id;
                this.addProgram = false;
                this.editProgram = true;
                this.cardWidth = 'col-md-6';
           },
           addNewProgram(program) {
                // this.companies.unshift(company);
                this.$toasted.success('Congratulations! Your new program has been added successfully.');
                this.latestPrograms += 1;
                this.closeForm();
            },
            updateProgram(programs) {
                //this.companies = companies;
                this.$toasted.success('Congratulations! Program has been updated successfully.');
                this.latestPrograms += 1;
                this.closeForm();
            },
            deleteprogram(programid) {
                var self = this;
                this.$refs.confirm.open('Delete', 'Are you sure?', { color: 'red',showAgree: true })
                .then((confirm) => {
                    if(confirm){
                        this.form.post('/program/delete/'+programid)
                        .then((company) => {
                            this.latestPrograms += 1
                            this.$toasted.success('Congratulations! Program has been deleted successfully.');  
                        })
                        .catch((error) => {
                            this.$refs.confirm.open('Delete Error', error.message, { color: 'red',showAgree: false });
                        });
                    }
                });

            },
            closeForm() {
                this.addProgram = false;
                this.editProgram = false;
                this.program = '';
                this.cardWidth = 'col-md-10';
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
