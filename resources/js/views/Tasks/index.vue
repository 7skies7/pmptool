<template>
 <div class="container-fluid">
    <project-menus selected="3"></project-menus>
    <v-app id="tasksScreen">
        <div class="row justify-content-center">
            <div :class="cardWidth">
                <div class="card card-default shadow-sm border-0">
                    <div class="card-header">
                        <div class="sflex spacebetween">
                            <div class="childFlex">
                                <span>Tasks</span>
                            </div>
                            <div class="pb-11">
                                <!-- <button v-if="isAddVisible" class="btn btn-add float-right" @click="showUploadWbs">Add Tasks</button> -->
                                <v-btn color="info" v-if="isAddVisible" @click="showUploadWbs"> Add Tasks</v-btn>

                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="d-flex flex-column">
                            <div class="flex-1">
                                <task-grid :addAccess="isAddVisible" :key="latestTasks" @showaddtask="showAddTask" @showedittask="showEditTask" @closeForm="closeForm" @deletetask="deleteTask"></task-grid>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <add-task v-if="isAddTaskVisible" :taskid="taskid" :point="point" @closeForm="closeForm" @completed="onTaskAdd"></add-task>
            <edit-task v-if="isEditTaskVisible" :task="task" @completed="onTaskUpdate" @closeForm="closeForm"></edit-task>
            <upload-wbs v-if="isUploadWbsVisible" :key="latestTasks" @wbsuploaded="onWbsUpload" @closeWBSForm="closeWBSForm"></upload-wbs>
            <confirm ref="confirm"></confirm>
        </div>
    </v-app>
</div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import Scope from '../../models/Scope';
    import TaskGrid from './TaskGrid.vue';
    import UploadWbs from './UploadWbs.vue';
    import EditTask from './EditTask.vue';
    import AddTask from './AddTask.vue';
    import ProjectMenus from '../Scope/ProjectMenus.vue';
    export default {
        components: {
            Multiselect, TaskGrid, UploadWbs, EditTask, AddTask, ProjectMenus
        },
        data() {
            return {
                form: new Form(),
                cardWidth: 'col-md-10',
                isAddVisible: true,
                isEditTaskVisible: false,
                isAddTaskVisible: false,
                dialog:false,
                latestTasks:0,
                isUploadWbsVisible:false,
                project_id: this.$route.params.id,
                taskid: '',
                point:'',
                task:'',
            }
        },
        created() {
            
        },
        methods: {
            showUploadWbs(){
                this.isUploadWbsVisible = true;
                this.cardWidth = 'col-md-6';
            },
            showEditTask(task){
                
                this.task = task;
                this.isEditTaskVisible = true;
                this.cardWidth = 'col-md-6';
            },
            showAddTask(id, point){
                this.isAddTaskVisible = true;
                this.taskid = id;
                this.point = point;
                this.cardWidth = 'col-md-6';
            },
            closeWBSForm(){
                this.isUploadWbsVisible = false;
                this.cardWidth = 'col-md-10';
            },
            onWbsUpload(){
                this.latestTasks += 1;
                this.$toasted.success('Congratulations! Your tasks has been uploaded successfully.');
                this.closeWBSForm;
            },
            closeForm(){
                this.isEditTaskVisible = false;
                this.isAddTaskVisible = false;
                this.cardWidth = 'col-md-10';
            },
            onTaskAdd(){
                this.latestTasks += 1;
                this.$toasted.success('Congratulations! Your tasks has been added successfully.');
                this.isAddTaskVisible = false;
                this.cardWidth = 'col-md-10';
                
            },
            onTaskUpdate(){
                this.latestTasks += 1;
                this.$toasted.success('Congratulations! Your tasks has been updated successfully.');
                this.isEditTaskVisible = false;
                this.cardWidth = 'col-md-10';
                
            },
            deleteTask(taskid){
                var self = this;
                this.$refs.confirm.open('Delete', 'Are you sure?', { color: 'red',showAgree: true })
                .then((confirm) => {
                    if(confirm){
                        this.form.post('/task/delete/'+taskid)
                        .then((company) => {
                            this.latestTasks += 1
                            this.$toasted.success('Congratulations! Task has been deleted successfully.');  
                        })
                        .catch((error) => {
                            this.$refs.confirm.open('Delete Error', error.message, { color: 'red',showAgree: false });
                        });
                    }
                });

                
            }

        },
        mounted() {
            console.log('Task mounted.');
             

        },
        watch: {
            loader () {
              const l = this.loader
              this[l] = !this[l]

              setTimeout(() => (this[l] = false), 3000)

              this.loader = null
            }
          }   
    }
</script>

