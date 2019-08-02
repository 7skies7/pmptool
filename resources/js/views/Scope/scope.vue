<template>
    <div class="container-fluid">
        <project-menus selected="1"></project-menus>
    	<div class="row justify-content-center">
            <div :class="cardWidth">
                <div class="card card-default shadow-sm border-0">
                    <div class="card-header">
                        <div class="sflex spacebetween">
                            <div class="childFlex">
                                <span>Scope</span>
                            </div>
                            <div class="pb-11">
                                <v-btn color="info" v-if="isAddVisible" @click="showaddScope">Add New</v-btn>
                            </div>
                        </div>
                    </div>
                <div class="card-body p-0">
                        <div class="d-flex flex-column">
                            <div class="flex-1">
                                <scope-grid :key="latestScope" @showeditscope="showEditScope" @deletescope="deleteScope" @showuserstorycomments="showUserstoryComments" @showadduserstory="showAddUserstory" @showedituserstory="showEditUserstory" @deleteuserstory="deleteUserstory" ></scope-grid>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <add-scope v-if="addScope" :key="latestScope" @completed="addNewScope" @closeAddForm="closeForm"></add-scope>
            <edit-scope v-if="editScope" :key="latestScope" :scopeid="scopeid" @updatecompleted="updateScope" @closeEditForm="closeForm"></edit-scope>
            <add-userstory v-if="addUserstory" :scopeid="scopeid" @completed="addNewUserstory" @closeAddForm="closeForm"></add-userstory>
            <edit-userstory v-if="editUserstory" :userstoryid="userstoryid" @updatecompleted="updateUserstory" @closeEditForm="closeForm"></edit-userstory>
            <userstory-comments v-if="userstoryComments" :key="latestUserstoryCommented" :userstoryid="userstoryid" :scopeid="scopeid" @completed="commentAdded" @closeCommentForm="closeForm" @approvecompleted="approvedComment"></userstory-comments>
            <confirm ref="confirm"></confirm>

        </div>
    </div>
</template>

<script>
    import ProjectMenus from './ProjectMenus.vue';
    import ScopeGrid from './ScopeGrid.vue';
    import Scope from '../../models/Scope';
    import AddScope from './AddScope.vue';
    import EditScope from './EditScope.vue';
    import UserstoryComments from './UserstoryComments.vue';
    import UserstoryGrid from './UserstoryGrid.vue';
    import AddUserstory from './AddUserstory.vue';
    import EditUserstory from './EditUserstory.vue';
    export default {
        components: {
            ScopeGrid, Scope, AddScope, EditScope, UserstoryComments, UserstoryGrid, AddUserstory, EditUserstory,ProjectMenus
        },
        data() {
            return {
                cardWidth: 'col-md-10',
                isAddVisible: false,
                addUserstory: false,
                editUserstory: false,
                latestScope: 0,
                addScope: false,
                editScope: false,
                scopeid:'',
                crdid:'',
                userstoryid: '',
                form: new Form(),
                userstoryComments: false,
                latestUserstoryCommented:0,
                project_id: this.$route.params.id,
                project: {}
            }
        },
        methods: {
            showaddScope() {
                if(this.isAddVisible == false)
                {
                    window.location.href = "/403";
                }
                this.closeform;
                this.addScope = true;
                this.cardWidth = 'col-md-6';
           },
           showEditScope(id) {
                this.closeForm;
                this.editScope = true;
                this.cardWidth = 'col-md-6';
                this.scopeid = id;
           },
           showAddUserstory(id) {
                this.closeForm;
                this.addUserstory = true;
                this.scopeid = id;
                this.cardWidth = 'col-md-6';
           },
            showEditUserstory(id) {
                this.closeForm;
                this.editUserstory = true;
                this.userstoryid = id;
                this.cardWidth = 'col-md-6';
           },
           showUserstoryComments(id, cr_id) {
                this.closeform;
                this.userstoryComments = true;
                this.cardWidth = 'col-md-6';
                this.userstoryid = id;
                this.scopeid = cr_id;
           },
           addNewScope(scope) {
                this.$toasted.success('Congratulations! Your new CRD has been added successfully.');
                this.latestScope += 1;
                this.closeForm();
            },
            addNewUserstory(userstory) {
                this.$toasted.success('Congratulations! Your new User Story has been added successfully.');
                this.latestScope += 1;
                this.closeForm();  
            },
            updateScope(scopes) {
                // this.companies = companies;
                this.$toasted.success('Congratulations! Scope has been updated successfully.');
                this.latestScope += 1;
                this.closeForm();
            },
            updateUserstory(userstory) {
                // this.companies = companies;
                this.$toasted.success('Congratulations! Userstory has been updated successfully.');
                this.latestScope += 1;
                this.closeForm();
            },
            deleteScope(scopeid) {
                var self = this;
                this.$refs.confirm.open('Delete', 'Are you sure?', { color: 'red',showAgree: true })
                .then((confirm) => {
                    if(confirm){
                        this.form.post('/scope/delete/'+scopeid)
                        .then((company) => {
                            this.latestScope += 1
                            this.$toasted.success('Congratulations! Scope has been deleted successfully.');  
                        })
                        .catch((error) => {
                            this.$refs.confirm.open('Delete Error', error.message, { color: 'red',showAgree: false });
                        });
                    }
                });

            },
            deleteUserstory(userstoryid) {
                
                var self = this;
                this.$refs.confirm.open('Delete', 'Are you sure?', { color: 'red',showAgree: true })
                .then((confirm) => {
                    if(confirm){
                        this.form.post('/userstory/delete/'+userstoryid)
                        .then((company) => {
                            this.latestScope += 1
                            this.$toasted.success('Congratulations! Userstory has been deleted successfully.');  
                        })
                        .catch((error) => {
                            this.$refs.confirm.open('Delete Error', error.message, { color: 'red',showAgree: false });
                        });
                    }
                });
                

            },
            closeForm() {
                this.addScope = false;
                this.editScope = false;
                this.userstoryComments = false;
                this.addUserstory = false;
                this.editUserstory = false;
                this.scopeid = '';
                this.cardWidth = 'col-md-10';
            },
            commentAdded() {
                this.$toasted.success('Congratulations! Comment has been added successfully.');
                this.latestUserstoryCommented += 1;
            },
            approvedComment() {
                this.$toasted.success('Congratulations! Comment has been approved successfully.');
                this.latestUserstoryCommented += 1;
            }
        },
       	mounted() {
            Scope.addaccess(addaccess => this.isAddVisible = addaccess); 

            console.log('Scope mounted.')
        }  	
    }
</script>

