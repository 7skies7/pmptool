<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div :class="cardWidth">
                <div class="card card-default shadow-sm border-0">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="flex-1">
                                <img src="/svg/project.svg" class="header-svg"/>
                                <span>Project</span>
                            </div>
                            <div class="pb-11">
                                <button class="btn btn-add float-right" @click="showAddProgram">Add New</button>
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
                                            <th scope="col">Start Date</th>
                                            <th scope="col">End Date</th>
                                            <!-- <th scope="col">Description</th> -->
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="program in programs">
                                            <td>{{ program.program_name }}</td>
                                            <td>{{ program.program_start_date }}</td>
                                            <td>{{ program.program_end_date }}</td>
                                            <!-- <td>{{ program.program_desc }}</td> -->
                                            <td>
                                                <router-link to="/project/update" class="btn btn-sm btn-primary no-link"><font-awesome-icon icon="edit" ></font-awesome-icon><Save</router-link>
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
            <add-program v-if="addProgram" @completed="addNewProgram" @closeAddForm="closeForm"></add-program>
        </div>
    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';
    import AddProgram from '../../components/AddProject.vue';
    import Program from '../../models/Program';
    export default {
        components: {
            Datepicker,AddProgram
        },
        data() {
            return{
                addProgram: false,
                programs: [],
                cardWidth: 'col-md-10'
            }
        },
        created() {
            Program.all(programs => this.programs = programs)            
                // .then(({data}) => this.statuses = data)
        },
        methods: {
           showAddProgram() {
                this.addProgram = true;
                this.cardWidth = 'col-md-6';
           },
           addNewProgram(program) {
                this.programs.unshift(program);
                this.$toasted.success('Congratulations! Your new project has been added successfully.')
            },
            closeForm() {
                this.addProgram = false;
                this.cardWidth = 'col-md-10';
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
