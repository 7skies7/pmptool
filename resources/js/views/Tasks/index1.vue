<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div :class="cardWidth">
                <div class="card card-default shadow-sm border-0">
                    <div class="card-header">
                            <img src="/svg/tasks.svg" class="header-svg"/>
                            <span>Tasks</span>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="pb-10">
                                <button class="btn btn-primary float-right" @click="showAddProgram">Add New</button>
                            </div>
                            <div class="flex-1">
                                <table class="table task-table" >
                                    <thead>
                                        <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Due Date</th>
                                            <th scope="col">Assigned User</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="first-td">Update Program create form new design</td>
                                            <td><span class="badge badge-pill badge-primary">Primary</span></td>
                                            <td>2019-01-01</td>
                                            <td><span class="rounded-circle usercard task-card">XY</span></td>
                                            <td>
                                                <router-link to="/project/update" class="btn btn-sm btn-primary no-link"><font-awesome-icon icon="edit" ></font-awesome-icon><Save</router-link>
                                                <!-- <button class="btn btn-sm btn-primary"><font-awesome-icon icon="edit" ></font-awesome-icon></button> -->
                                                <button class="btn btn-sm btn-danger"><font-awesome-icon icon="trash" ></font-awesome-icon></button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="first-td">Trigger new cron for enkins</td>
                                            <td><span class="badge badge-pill badge-success"> Completed</span></td>
                                            <td>2019-01-01</td>
                                            <td><span class="rounded-circle usercard task-card">AK</span></td>
                                            <td>
                                                <router-link to="/project/update" class="btn btn-sm btn-primary no-link"><font-awesome-icon icon="edit" ></font-awesome-icon><Save</router-link>
                                                <!-- <button class="btn btn-sm btn-primary"><font-awesome-icon icon="edit" ></font-awesome-icon></button> -->
                                                <button class="btn btn-sm btn-danger"><font-awesome-icon icon="trash" ></font-awesome-icon></button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="first-td">Insert new design with tailwindcss</td>
                                            <td><span class="badge badge-pill badge-danger"> Pending </span></td>
                                            <td>2019-01-01</td>
                                            <td><span class="rounded-circle usercard task-card">PM</span></td>
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
    import AddProgram from '../../components/AddProgram.vue';
    import Program from '../../models/Program';
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
           onShowComments() {
                this.isTaskCommentVisible = !this.isTaskCommentVisible;
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
