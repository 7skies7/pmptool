<template>
  <v-app style="background:transparent!important" v-if="userdetails">
    <div class="text-xs-center" style="display:flex">
        <v-chip v-if="userdetails.companies" small color="#EF4667" medium text-color="white" style="margin-top:5%">
            {{ this.userdetails.companies[0].company_name }}
        </v-chip>
        <v-menu v-if="userdetails" v-model="menu" :close-on-content-click="true" :nudge-width="100" offset-x open-on-hover="true">
            <template v-slot:activator="{ on }">
                <v-btn color="indigo" dark v-on="on" v-if="userdetails">
                    {{ userName }}
                </v-btn>
            </template>
  
            <v-card>
                <v-list>
                    <v-list-tile avatar>
                        <v-list-tile-avatar>
                            <img src="/images/User.png" alt="John">
                        </v-list-tile-avatar>
      
                        <v-list-tile-content>
                            <v-list-tile-title>{{ userName}}</v-list-tile-title>
                            <v-list-tile-sub-title>{{ this.userdetails.email }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
                <v-divider></v-divider>
            <v-list>
                <v-list-tile>
                    <v-list-tile-action>
                        <!-- <v-switch v-model="message" color="purple"></v-switch> -->
                        <v-icon>security</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-title style="cursor:pointer" @click="onChangePassword">Change Password</v-list-tile-title>
                </v-list-tile>
                <v-list-tile>
                    <v-list-tile-action>
                        <v-switch v-model="hints" color="purple"></v-switch>
                    </v-list-tile-action>
                    <v-list-tile-title>Enable hints</v-list-tile-title>
                </v-list-tile>
            </v-list>  
            <v-card-actions>
                <v-spacer></v-spacer>  
                    <v-btn small flat @click="menu = false">Cancel</v-btn>
                    <slot></slot>
                </v-card-actions>
            </v-card>
        </v-menu>
    </div>
    <v-dialog v-model="passwordchange" width="500" persistent>
        <v-card>
            <v-card-title class="headline grey lighten-2" primary-title>
            Change Password
            </v-card-title>
            <v-card-text>
                <v-app id="editTask">
                    <v-form @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                        <v-container grid-list-md text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-card color="white">
                                        <v-text-field type="password" v-model="form.password" label="Password" placeholder="Password" :messages="form.errors.get('password')"></v-text-field>
                                    </v-card>
                                </v-flex>
                                <v-flex xs12>
                                    <v-card color="white">
                                        <v-text-field type="password" v-model="form.password_confirmation" label="Password Confirmation" placeholder="Password Confirmation" :messages="form.errors.get('password')"></v-text-field>
                                    </v-card>
                                </v-flex>
                                <v-flex xs12>
                                    <v-card color="white">
                                        <v-progress-circular indeterminate v-show="isLoading" color="primary"></v-progress-circular>
                                        <v-btn type="submit" color="info" > Save</v-btn>
                                        <v-btn v-if="isCloseVisible" @click="passwordchange = false"> Close</v-btn>
                                    </v-card>
                                </v-flex>
                                                      
                            </v-layout>
                        </v-container> 
                    </v-form>
                </v-app> 
            </v-card-text>
            <v-divider></v-divider>
           <!--  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" flat @click="passwordchange = false">Close</v-btn>
            </v-card-actions> -->
        </v-card>
    </v-dialog>
</v-app>

</template>

<script>
    import Acl from '../models/Acl';
    export default {
        
        data() {
            return {
                form: new Form({
                    password: '',
                    password_confirmation: '',
                    errors:'',
                }),
                fav: true,
                menu: false,
                message: false,
                hints: true,
                userdetails:[],
                passwordchange:false,
                isLoading:false,
                isCloseVisible:false,

            }
        },
        created() {
            Acl.getUserDetails(userdetails => this.userdetails = userdetails);
            // console.log(this.userdetails);
        },
        methods: {
            onSubmit() {
                var self = this;
                this.form.post('/user/changepassword')
               .then((user) => {
                    this.passwordchange = false;
                    this.$toasted.success('Congratulations! Your password has been successfully.');

                });
            },
            onChangePassword() {
                this.passwordchange = true;
                this.isCloseVisible = true;
            }
        },
        computed: {
            userName: function() {
                return this.userdetails.first_name + ' ' + this.userdetails.last_name
            }
        },
        watch: {
            userdetails() {
                this.passwordchange = !this.userdetails.password_changed;
            }
        }
    }
</script>
