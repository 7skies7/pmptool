<template>
    <div class="row justify-content-center">
        <div :class="cardWidth">
            <div class="card card-default shadow-sm border-0">
                <div class="card-header">
                    <div class="sflex spacebetween">
                        <div>
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
                            <task-grid :key="latestTasks"></task-grid>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <upload-wbs v-if="isUploadWbsVisible" :key="latestTasks" @closeWBSForm="closeWBSForm"></upload-wbs>
    
    </div>
          
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import Scope from '../../models/Scope';
    import TaskGrid from './TaskGrid.vue';
    import UploadWbs from './UploadWbs.vue';
    export default {
        components: {
            Multiselect, TaskGrid, UploadWbs
        },
        data() {
            return {
                cardWidth: 'col-md-10',
                isAddVisible: true,
                dialog:false,
                latestTasks:0,
                isUploadWbsVisible:false,
                project_id: this.$route.params.id,
            }
        },
        created() {
            Scope.allProjectScope(scope => this.scopes = scope, this.project_id);
        },
        methods: {
            showUploadWbs(){
                this.isUploadWbsVisible = true;
                this.cardWidth = 'col-md-6';
            },
            closeWBSForm(){
                this.isUploadWbsVisible = false;
                this.cardWidth = 'col-md-10';
            }
        },
        mounted() {
            console.log('Task mounted.')
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

