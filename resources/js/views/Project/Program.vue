<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div :class="cardWidth">
                <div class="card card-default shadow-sm border-0">
                    <div class="card-header">
                            <div class="d-flex">
                                <div class="flex-1">
                                    <img src="/svg/program_bulb.svg" class="header-svg"/>
                                    <span>Program</span>
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
                                        <tr v-for="program in programs" :class="addBorder">
                                            <td>{{ program.program_name }}</td>
                                            <td>{{ program.program_start_date }}</td>
                                            <td>{{ program.program_end_date }}</td>
                                            <!-- <td>{{ program.program_desc }}</td> -->
                                            <td>
                                                <button class="btn btn-sm btn-primary"  @click="showAddProgram"><font-awesome-icon icon="edit" ></font-awesome-icon></button>
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
    import AddProgram from '../components/AddProgram.vue';
    import Program from '../models/Program';
    export default {
        components: {
            Datepicker,AddProgram
        },
        data() {
            return{
                addProgram: false,
                programs: [],
                cardWidth: 'col-md-10',
                addBorder: '',
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
                this.addBorder = '';
           },
           addNewProgram(program) {
                this.programs.unshift(program);
                this.$toasted.success('Your new program has been added successfully.')
            },
            closeForm() {
                this.addProgram = false;
                this.cardWidth = 'col-md-10';
                this.addBorder = '';
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
