<template>
<div class="container-fluid">
    <project-menus selected="2"></project-menus>
	<div class="row justify-content-center">
        <div :class="cardWidth">
            <div class="card card-default shadow-sm border-0">
                <div class="card-header">
                    <div class="sflex spacebetween">
                        <div>
                            <span>{{ this.userstory.userstory_desc}}</span>
                        </div>
                        <!-- <div class="pb-11">
                            <button v-if="isAddVisible" class="btn btn-add float-right" @click="showaddScope">Add New</button>
                        </div> -->
                    </div>
                </div>
                <div class="card-body p-0">
                    <v-app id="dev">
                        <v-container grid-list-md text-xs-center class="unsetWidth">
                            <v-layout row wrap>
                                <v-flex xs12 sm12 md5 :key="latestStatus">
                                    <v-layout row wrap>
                                        <v-flex xs12>
                                            <v-card>
                                                <v-list>
                                                    <v-list-tile avatar>
                                                        <v-list-tile-content>
                                                            <v-list-tile-title>Userstory Description</v-list-tile-title>
                                                            <v-list-tile-sub-title v-if="">{{ userstory.userstory_desc}}</v-list-tile-sub-title>
                                                        </v-list-tile-content>
                                                    </v-list-tile>
                                                </v-list>
                                            </v-card>
                                        </v-flex>
                                       
                                        <v-flex xs6>
                                            <v-card>
                                                <v-list>
                                                    <v-list-tile avatar>
                                                        <v-list-tile-content>
                                                            <v-list-tile-title>Userstory Point</v-list-tile-title>
                                                            <v-list-tile-sub-title v-if="">{{ userstory.userstory_point}}</v-list-tile-sub-title>
                                                        </v-list-tile-content>
                                                    </v-list-tile>
                                                </v-list>
                                            </v-card>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-card>
                                                <v-list>
                                                    <v-list-tile avatar>
                                                        <v-list-tile-content>
                                                            <v-list-tile-title>Status</v-list-tile-title>
                                                            <v-list-tile-sub-title v-if="userstory.status">{{ userstory.status.status_name}}</v-list-tile-sub-title>
                                                        </v-list-tile-content>
                                                    </v-list-tile>
                                                </v-list>
                                            </v-card>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-card>
                                                <v-list>
                                                    <v-list-tile avatar>
                                                        <v-list-tile-content>
                                                            <v-list-tile-title>Priority</v-list-tile-title>
                                                            <v-list-tile-sub-title v-if="userstory.priority">{{ userstory.priority.priority_type}}</v-list-tile-sub-title>
                                                        </v-list-tile-content>
                                                    </v-list-tile>
                                                </v-list>
                                            </v-card>
                                        </v-flex>
                                    </v-layout>
                                </v-flex>
                                <v-flex xs12 sm12 md7>
                                    <userstory-comments :key="latestUserstoryCommented" :userstoryid="userstory_id" @completed="onCommentAdded" @approvecompleted="onCommentApprove"></userstory-comments>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-app>
                </div>
            </div>
        </div>
        
    </div>
</div>
</template>

<script>
    import UserstoryGrid from './UserstoryGrid.vue';
    import Userstory from '../../models/Userstory';
    import ProjectMenus from  './ProjectMenus';
    import UserstoryComments from './UserstoryComments.vue';

    export default {
        components: {
            UserstoryGrid, Userstory, ProjectMenus, UserstoryComments
        },
        data() {
            return {
                cardWidth: 'col-md-10',
                form: new Form(),
                project_id: this.$route.params.id,
                userstory_id: this.$route.params.userstoryid,
                userstory: [],
                latestUserstoryCommented: 0,
                latestStatus:0,
            }
        },
        created() {
            Userstory.fetchUserstory(userstory => this.userstory = userstory, this.userstory_id)
        },
        methods: {
            onCommentAdded(){
                this.latestUserstoryCommented += 1;
                this.latestStatus += 1;
                this.$toasted.success('Congratulations! Comment has been added successfully.');

            },
            onCommentApprove()
            {   
                this.latestUserstoryCommented += 1;
                this.$toasted.success('Congratulations! Comment has been approved successfully.');
            }

        },
       	mounted() {
            //Scope.addaccess(addaccess => this.isAddVisible = addaccess); 
            console.log('Scope mounted.')
        }  	
    }
</script>

