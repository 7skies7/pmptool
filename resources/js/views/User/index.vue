<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div :class="cardWidth">
                <div class="card card-default shadow-sm border-0">
                    <div class="card-header">
                        <div class="sflex spacebetween">
                            <div class="childFlex">
                                <span>Users</span>
                            </div>
                            <div class="pb-11">
                                <v-btn color="info" v-if="isAddVisible" @click="showaddUser">Add New</v-btn>
                            </div>
                        </div>
                    </div>
                <div class="card-body p-0">
                        <div class="d-flex flex-column">
                            <div class="flex-1">
                                <grid :key="latestUsers" @showedituser="showEditUser" @deleteuser="deleteuser"></grid>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <add-user v-if="addUser" :key="latestUsers" @completed="addNewUser" @closeForm="closeForm"></add-user>
            <edit-user v-if="editUser" :key="latestUsers" :user="user" @completed="updateUser" @closeForm="closeForm"></edit-user>
        </div>
    </div>
    
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';
    import addUser from './AddUser.vue';
    import editUser from './EditUser.vue';
    import Grid from './Grid.vue';
    import User from '../../models/User';
    export default {
        components: {
            Datepicker, addUser, editUser,Grid
        },
        data() {
            return{
                addUser: false,
                editUser: false,
                users: [],
                cardWidth: 'col-md-10',
                form: new Form(),
                latestUsers: 0,
                isAddVisible: true,
                user:'',
            }
        },
        created() {
            User.addaccess(addaccess => this.isAddVisible = addaccess); 
        },
        methods: {
           showaddUser() {
                if(this.isAddVisible == false)
                {
                    window.location.href = "/403";
                }
                this.addUser = true;
                this.editUser = false;
                this.cardWidth = 'col-md-6';
           },
           showEditUser(user) {
                this.user = user;
                this.addUser = false;
                this.editUser = true;
                this.username = user.first_name +' '+ user.last_name;
                this.cardWidth = 'col-md-6';
           },
           addNewUser(user) {
                // this.companies.unshift(company);
                this.$toasted.success('Congratulations! Your new user has been added successfully.');
                this.latestUsers += 1;
                this.closeForm();
            },
            updateUser(users) {
                //this.companies = companies;
                this.$toasted.success('Congratulations! User has been updated successfully.');
                this.latestUsers += 1;
                this.closeForm();
            },
            deleteuser(userid) {
                this.form.post('/user/delete/'+userid)
                    .then(user => this.latestUsers += 1);
                    this.$toasted.success('Congratulations! User has been deleted successfully.');  
                    this.latestUsers += 1;

            },
            closeForm() {
                this.addUser = false;
                this.editUser = false;
                this.user = '';
                this.cardWidth = 'col-md-10';
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
