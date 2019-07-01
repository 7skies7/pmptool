<template>
	<div class="row justify-content-center">
        <div :class="cardWidth">
            <div class="card card-default shadow-sm border-0">
                <div class="card-header">
                    <div class="sflex spacebetween">
                        <div>
                            <span>Scope</span>
                        </div>
                        <div class="pb-11">
                            <button v-if="isAddVisible" class="btn btn-add float-right" @click="showaddScope">Add New</button>
                        </div>
                    </div>
                </div>
            <div class="card-body p-0">
                    <div class="d-flex flex-column">
                        <div class="flex-1">
                            <scope-grid :key="latestScope" @showeditscope="showEditScope" @deletescope="deleteScope" @showscopecomments="showScopeComments"></scope-grid>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <add-scope v-if="addScope" :key="latestScope" @completed="addNewScope" @closeAddForm="closeForm"></add-scope>
        <edit-scope v-if="editScope" :key="latestScope" :scopeid="scopeid" @updatecompleted="updateScope" @closeEditForm="closeForm"></edit-scope>
        <scope-comments v-if="scopeComments" :key="latestScopeCommented" :crdid="crdid" @completed="commentAdded" @closeCommentForm="closeForm"></scope-comments>
    </div>
</template>

<script>
    import ScopeGrid from './ScopeGrid.vue';
    import Scope from '../../models/Scope';
    import AddScope from './AddScope.vue';
    import EditScope from './EditScope.vue';
    import ScopeComments from './ScopeComments.vue';
    export default {
        components: {
            ScopeGrid, Scope, AddScope, EditScope, ScopeComments
        },
        data() {
            return {
                cardWidth: 'col-md-10',
                isAddVisible: false,
                latestScope: 0,
                addScope: false,
                editScope: false,
                scopeid:'',
                crdid:'',
                form: new Form(),
                scopeComments: false,
                latestScopeCommented:0,
            }
        },
        methods: {
            showaddScope() {
                if(this.isAddVisible == false)
                {
                    window.location.href = "/403";
                }
                this.addScope = true;
                this.editScope = false;
                this.scopeComments = false;
                this.cardWidth = 'col-md-6';
           },
           showEditScope(id) {
                this.editScope = false;
                this.scopeid = id;
                this.addScope = false;
                this.editScope = true;
                this.scopeComments = false;
                this.cardWidth = 'col-md-6';
           },
           showScopeComments(id) {
                this.editScope = false;
                this.addScope = false;
                this.editScope = false;
                this.scopeComments = true;
                this.cardWidth = 'col-md-6';
                this.crdid = id;
           },
           addNewScope(scope) {
                
                this.$toasted.success('Congratulations! Your new CRD has been added successfully.');
                this.latestScope += 1;
                this.closeForm();
            },
            updateScope(scopes) {
                // this.companies = companies;
                this.$toasted.success('Congratulations! Scope has been updated successfully.');
                this.latestScope += 1;
                this.closeForm();
            },
            deleteScope(scopeid) {
                this.form.post('/scope/delete/'+scopeid)
                    .then(scope => this.latestScope += 1);
                    this.$toasted.success('Congratulations! Scope has been deleted successfully.');  
                    this.latestScope += 1;

            },
            closeForm() {
                this.addScope = false;
                this.editScope = false;
                this.scopeComments = false;
                this.scopeid = '';
                this.cardWidth = 'col-md-10';
            },
            commentAdded() {
                this.$toasted.success('Congratulations! Comment has been added successfully.');
                this.latestScopeCommented += 1;
            }
        },
       	mounted() {
            Scope.addaccess(addaccess => this.isAddVisible = addaccess); 
            console.log('Scope mounted.')
        }  	
    }
</script>

