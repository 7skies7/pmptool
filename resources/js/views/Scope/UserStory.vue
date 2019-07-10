<template>

	<div class="row justify-content-center">
        <div :class="cardWidth">
            <div class="card card-default shadow-sm border-0">
                <div class="card-header">
                    <div class="sflex spacebetween">
                        <div>
                            <span>User Story</span>
                        </div>
                        <div class="pb-11">
                            <button v-if="isAddVisible" class="btn btn-add float-right" @click="showaddScope">Add New</button>
                        </div>
                    </div>
                </div>
            <div class="card-body p-0">
                    <div class="d-flex flex-column">
                        <div class="flex-1">
                            <userstory-grid :key="latestUserstory" @showedituserstory="showEditUserstory" @deleteuserstory="deleteUserstory" @showuserstorycomments="showUserstoryComments"></userstory-grid>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <add-userstory v-if="addUserstory" :key="latestUserstory" @completed="addNewUserstory" @closeAddForm="closeForm"></add-userstory>
       <!--  <edit-scope v-if="editUserstory" :key="latestUserstory" :scopeid="userstoryid" @updatecompleted="updateUserstory" @closeEditForm="closeForm"></edit-scope> -->
        <!-- <userstory-comments v-if="userstoryComments" :key="latestUserstoryCommented" :crdid="crdid" @completed="commentAdded" @closeCommentForm="closeForm"></userstory-comments> -->
    </div>
</template>

<script>
    import UserstoryGrid from './UserstoryGrid.vue';
    import Userstory from '../../models/Userstory';
    import AddScope from './AddScope.vue';
    import EditScope from './EditScope.vue';
    import UserstoryComments from './UserstoryComments.vue';
    import index from './index.vue';
    export default {
        components: {
            UserstoryGrid, Userstory, AddScope, EditScope, UserstoryComments, index
        },
        data() {
            return {
                cardWidth: 'col-md-10',
                isAddVisible: false,
                latestUserstory: 0,
                addUserstory: false,
                editUserstory: false,
                userstoryid:'',
                crdid:'',
                form: new Form(),
                userstoryComments: false,
                latestUserstoryCommented:0,
            }
        },
        methods: {
            showaddUserstory() {
                if(this.isAddVisible == false)
                {
                    window.location.href = "/403";
                }
                this.addUserstory = true;
                this.editUserstory = false;
                this.userstoryComments = false;
                this.cardWidth = 'col-md-6';
           },
           showEditUserstory(id) {
                this.editUserstory = false;
                this.userstoryid = id;
                this.addUserstory = false;
                this.editUserstory = true;
                this.userstoryComments = false;
                this.cardWidth = 'col-md-6';
           },
           showUserstoryComments(id) {
                this.editUserstory = false;
                this.addUserstory = false;
                this.editUserstory = false;
                this.userstoryComments = true;
                this.cardWidth = 'col-md-6';
                this.crdid = id;
           },
           addNewUserstory(userstory) {
                
                this.$toasted.success('Congratulations! Your new CRD has been added successfully.');
                this.latestUserstory += 1;
                this.closeForm();
            },
            updateUserstory(scopes) {
                // this.companies = companies;
                this.$toasted.success('Congratulations! Scope has been updated successfully.');
                this.latestUserstory += 1;
                this.closeForm();
            },
            deleteUserstory(scopeid) {
                this.form.post('/scope/delete/'+scopeid)
                    .then(scope => this.latestScope += 1);
                    this.$toasted.success('Congratulations! Scope has been deleted successfully.');  
                    this.latestScope += 1;

            },
            closeForm() {
                this.addUserstory = false;
                this.editUserstory = false;
                this.userstoryComments = false;
                this.userstoryid = '';
                this.cardWidth = 'col-md-10';
            },
            commentAdded() {
                this.$toasted.success('Congratulations! Comment has been added successfully.');
                this.latestUserstoryCommented += 1;
            }
        },
       	mounted() {
            //Scope.addaccess(addaccess => this.isAddVisible = addaccess); 
            console.log('Scope mounted.')
        }  	
    }
</script>

