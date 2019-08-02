<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div :class="cardWidth">
                <div class="card card-default shadow-sm border-0">
                    <div class="card-header">
                        <div class="sflex spacebetween">
                            <div class="childFlex">
                                <span>Projects</span>
                            </div>
                            <div class="pb-11">
                                <v-btn color="info" v-if="isAddVisible" @click="showaddProject">Add New</v-btn>
                            </div>
                        </div>
                    </div>
                <div class="card-body p-0">
                        <div class="d-flex flex-column">
                            <div class="flex-1">
                                <grid :key="latestProjects" @showeditproject="showEditProject" @deleteproject="deleteproject"></grid>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <add-project v-if="addProject" :key="latestProjects" @completed="addNewProject" @closeAddForm="closeForm"></add-project>
            <edit-project v-if="editProject" :key="latestProjects" :project="project" @updatecompleted="updateProject" @closeEditForm="closeForm"></edit-project>
            <confirm ref="confirm"></confirm>
            
        </div>
    </div>
    
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';
    import addProject from './addProject.vue';
    import editProject from './editProject.vue';
    import Grid from './Grid.vue';
    import Project from '../../models/Project';
    export default {
        components: {
            Datepicker,addProject,editProject,Grid
        },
        data() {
            return{
                addProject: false,
                editProject: false,
                project:'',
                companies: [],
                cardWidth: 'col-md-10',
                form: new Form(),
                latestProjects: 0,
                isAddVisible: false,
            }
        },
        created() {
            
        },
        methods: {
           showaddProject() {
                if(this.isAddVisible == false)
                {
                    window.location.href = "/403";
                }
                this.addProject = true;
                this.editProject = false;
                this.cardWidth = 'col-md-6';
           },
           showEditProject(project) {
                this.editProject = false;
                this.project = project;
                this.addProject = false;
                this.editProject = true;
                this.cardWidth = 'col-md-6';
           },
           addNewProject(project) {
                
                this.$toasted.success('Congratulations! Your new project has been added successfully.');
                this.latestProjects += 1;
                this.closeForm();
            },
            updateProject(projects) {
                // this.companies = companies;
                this.$toasted.success('Congratulations! Project has been updated successfully.');
                this.latestProjects += 1;
                this.closeForm();
            },
            deleteproject(projectid) {
                var self = this;
                this.$refs.confirm.open('Delete', 'Are you sure?', { color: 'red',showAgree: true })
                .then((confirm) => {
                    if(confirm){
                        this.form.post('/project/delete/'+projectid)
                        .then((company) => {
                            this.latestProjects += 1
                            this.$toasted.success('Congratulations! Project has been deleted successfully.');  
                        })
                        .catch((error) => {
                            this.$refs.confirm.open('Delete Error', error.message, { color: 'red',showAgree: false });
                        });
                    }
                });

            },
            closeForm() {
                this.addProject = false;
                this.editProject = false;
                this.projectid = '';
                this.cardWidth = 'col-md-10';
            }
        },
        mounted() {
            Project.addaccess(addaccess => this.isAddVisible = addaccess); 
            console.log('Component mounted.')
        }
    }
</script>
