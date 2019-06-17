<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div :class="cardWidth">
                <div class="card card-default shadow-sm border-0">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="flex-1">
                                <img src="/svg/project.svg" class="header-svg"/>
                                <span>ACL</span>
                            </div>
                            <div class="pb-11">
                                <button class="btn btn-add float-right" >Add New</button>
                            </div>
                        </div>
                    </div>
                <div class="card-body p-0">
                        <div class="d-flex flex-column">
                            <div class="flex-1">
                                <div id="app" class="aclmain">
                                <v-app id="inspire">
                                    <div>
                                        <v-flex xs12 sm3 d-flex>
                                            <v-select :items="roles" item-text="role_title" item-value="id"  v-model="currentRole" label="Select Role" box @change="onRoleTabChange"></v-select>
                                        </v-flex>
                                    <!-- <v-toolbar color="#1CBCEF" dark tabs :height="1">
                                        <template v-slot:extension>
                                            <v-tabs v-model="currentRole" color="transparent" fixed-tabs slider-color="#EF4667" @change="onRoleTabChange">
                                            <v-tab v-for="role in roles" :key="role.role_title" :href="'#'+role.id">
                                              {{ role.role_title }}
                                            </v-tab>
                                             </v-tabs>
                                        </template>
                                    </v-toolbar> -->
                                    <div id="acltablediv">
                                    <v-progress-linear v-show="isLoading" v-slot:progress color="blue" indeterminate :height="3"></v-progress-linear>
                                    <table class="table table-bordered" id="acltable">
                                        <thead>
                                            <tr>
                                                <th scope="col"><code class="primary-gradient">Modules </code>  <code class="secondary-gradient">Actions</code></th>
                                                <th v-for="action in actions" :key="action.id" scope="col" class="secondary-gradient">{{ action.action_name }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="module in modules" :key="module.id">
                                                <th class="primary-gradient">{{ module.module_name }}</th>
                                                
                                                <td v-for="action in actions" :key="action.id">
                                                    <v-checkbox :disabled="isDisabled" v-model="roleaccesslist" :value="module.id + '_' + action.id" color="info" @change="onAccessChange(module.id,action.id)" :success="isSuccess" :success-messages="successarr"></v-checkbox>     
                                                </td>
                                                
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                    </div>
                                    </div>
                                  </v-app>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           <!--  <add-acl v-if="addCompany" @completed="addNewAcl" @closeAddForm="closeForm"></add-acl> -->
        </div>
    </div>
</template>

<script>
    import Acl from '../../models/Acl';
    export default {
        components: {
        },
        data() {
            return {
                roleaccesslist:[],
                cardWidth: 'col-md-10',
                currentRole: 1,
                roles: [],
                modules: [],
                actions: [],
                isLoading: true,
                isDisabled: false,
                isSuccess: false,
                successarr: [],
                form: new Form({
                    module_id: '',
                    action_id: '',
                    role_id: '',
                    access_status:'',
                })
            }   
        },
        created() {

        },
        methods: {
            onRoleTabChange(id) {
                this.isLoading = true;
                Acl.getRoleAccessList(accesslist => this.roleaccesslist = accesslist, id);

            },
            onAccessChange(module_id, action_id) {
                
                this.isDisabled = true;
                this.form.module_id = module_id;
                this.form.action_id = action_id;
                this.form.role_id = this.currentRole;
                this.form.access_status = this.roleaccesslist.includes(module_id + '_' + action_id) ? 1 : 0;
                
                this.form.post('/acl/update')
               .then(accesslist => this.roleaccesslist = accesslist);
              
                this.isDisabled = false;
            },
            addNewAcl(acl) {
                // this.companies.unshift(company);
                this.$toasted.success('Congratulations! Your new organization has been added successfully.');
                this.latestCompanies += 1;
                this.closeForm();
            },
        },
        mounted() {
            this.isLoading = true;
            Acl.roles(roles => this.roles = roles)
            Acl.modules(modules => this.modules = modules)
            Acl.actions(actions => this.actions = actions)
            Acl.getRoleAccessList(accesslist => this.roleaccesslist = accesslist, this.currentRole);          
            console.log('Component mounted.')
        },
        watch: {
            roleaccesslist() {
                this.isLoading = false;
            },
            actions() {
                this.isLoading = false;  
            }
        }
    }
</script>
