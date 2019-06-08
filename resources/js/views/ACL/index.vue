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
                                <div id="app">
                                <v-app id="inspire">
                                    <div>
                                    <v-toolbar color="#1CBCEF" dark tabs :height="1">
                                        <template v-slot:extension>
                                            <v-tabs v-model="currentItem" color="transparent" fixed-tabs slider-color="#EF4667">
                                            <v-tab v-for="item in items" :key="item.role_title" :href="'#tab-' + item.role_title">
                                              {{ item.role_title }}
                                            </v-tab>
                                             </v-tabs>
                                        </template>
                                    </v-toolbar>
                                    <div>
                                    <table class="table table-bordered" id="acltable">
                                        <thead>
                                            <tr>
                                                <th scope="col"><code class="primary-gradient">Modules </code>  <code class="secondary-gradient">Actions</code></th>
                                                <th scope="col" class="secondary-gradient">View</th>
                                                <th scope="col" class="secondary-gradient">Create</th>
                                                <th scope="col" class="secondary-gradient">Update</th>
                                                <th scope="col" class="secondary-gradient">Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th class="primary-gradient">Organization</th>
                                                <td>
                                                    <v-checkbox v-model="selected" value="1_1"></v-checkbox>     
                                                </td>
                                                <td>
                                                    <v-checkbox v-model="selected" value="1_2"></v-checkbox>     
                                                </td>
                                                <td>
                                                    <v-checkbox v-model="selected" value="1_3"></v-checkbox>     
                                                </td>
                                                <td>
                                                    <v-checkbox v-model="selected" value=""></v-checkbox>     
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="primary-gradient">Users</th>
                                                <td>Checkbox</td>
                                                <td>Checkbox</td>
                                                <td>Checkbox</td>
                                                <td>Checkbox</td>
                                            </tr>
                                            <tr>
                                                <th class="primary-gradient">ACL</th>
                                                <td>Checkbox</td>
                                                <td>Checkbox</td>
                                                <td>Checkbox</td>
                                                <td>Checkbox</td>
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
                selected:['1_1'],
                cardWidth: 'col-md-10',
                currentItem: 'tab-Super Admin',
                items: [],
            }   
        },
        created() {
            Acl.roles(roles => this.items = roles)            
        },
        methods: {
    
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
