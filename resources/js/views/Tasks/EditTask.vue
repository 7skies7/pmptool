    <template>
    <div class="col-md-6">
        <div class="card card-default shadow-sm">
            <div class="card-header">Edit Sub Task</div>

            <div class="card-body">
                <v-app id="editTask">
                    <v-form @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                        <v-container grid-list-md text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-card color="white">
                                        <v-text-field v-model="form.task_desc" label="Task Description" placeholder="Task Description" :messages="form.errors.get('task_desc')"></v-text-field>
                                    </v-card>
                                </v-flex>
                                <v-flex xs6>
                                    <v-card color="white">
                                        <v-text-field v-model="form.task_point" label="Task Point" placeholder="Task Point" :messages="form.errors.get('task_point')" :suffix="calcRemainingPoint"></v-text-field>
                                    </v-card>
                                </v-flex>
                                <v-flex xs6>
                                    <v-card color="white">
                                        <div class="v-input v-text-field v-input--is-label-active theme--light">
                                            <div class="v-input__control">
                                                <div class="v-input__slot">
                                                    <div class="v-text-field__slot multiselectwps">
                                                        <label aria-hidden="true" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Assignee</label>
                                                        <multiselect v-model="form.task_assignee" :options="users" :searchable="true" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="Select Assignee" label="name" track-by="id" :preselect-first="false" >
                                                            <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                        </multiselect>
                                                    </div>                        
                                                </div>
                                                <div class="v-text-field__details"><div class="v-messages theme--light"><div class="v-messages__wrapper">{{form.errors.get('task_assignee')}}</div></div></div>
                                            </div>
                                        </div>
                                    </v-card>
                                </v-flex>
                                <v-flex xs6>
                                    <v-card color="white" >
                                        <v-menu v-model="start_date" :close-on-content-click="false" :nudge-right="40" lazy transition="scale-transition" offset-y full-width min-width="290px">
                                            <template v-slot:activator="{ on }">
                                                <v-text-field v-model="form.task_start_date" label="Select Start Date" prepend-icon="event" readonly v-on="on" :messages="form.errors.get('task_start_date')"></v-text-field>
                                          </template>
                                          <v-date-picker v-model="form.task_start_date" @input="start_date = false"></v-date-picker>
                                        </v-menu>
                                    </v-card>
                                </v-flex>
                                <v-flex xs6>
                                    <v-card color="white">
                                        <v-menu v-model="end_date" :close-on-content-click="false" :nudge-right="40" lazy transition="scale-transition" offset-y full-width min-width="290px">
                                            <template v-slot:activator="{ on }">
                                                <v-text-field v-model="form.task_end_date" label="Select End Date" prepend-icon="event" readonly v-on="on" :messages="form.errors.get('task_end_date')"></v-text-field>
                                          </template>
                                          <v-date-picker v-model="form.task_end_date" @input="end_date = false"></v-date-picker>
                                        </v-menu>
                                        </v-card>
                                    </v-card>
                                </v-flex>
                                <v-flex xs6>
                                    <v-card color="white">
                                        <div class="v-input v-text-field v-input--is-label-active theme--light">
                                            <div class="v-input__control">
                                                <div class="v-input__slot">
                                                    <div class="v-text-field__slot multiselectwps">
                                                        <label aria-hidden="true" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Status</label>
                                                        <multiselect v-model="form.task_status" :options="status" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="Select Status" label="status_name" track-by="id" :preselect-first="false" >
                                                            <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                        </multiselect>
                                                    </div>                                   
                                                </div>
                                                <div class="v-text-field__details"><div class="v-messages theme--light"><div class="v-messages__wrapper">{{form.errors.get('task_status')}}</div></div></div>
                                            </div>
                                        </div>
                                    </v-card>
                                </v-flex>
                                <v-flex xs6>
                                    <v-card color="white">
                                        <div class="v-input v-text-field v-input--is-label-active theme--light">
                                            <div class="v-input__control">
                                                <div class="v-input__slot">
                                                    <div class="v-text-field__slot multiselectwps">
                                                        <label aria-hidden="true" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Priority</label>
                                                        <multiselect v-model="form.task_priority" :options="priority" :close-on-select="false" :clear-on-select="true" :preserve-search="false" placeholder="Select Priority" label="priority_type" track-by="id" :preselect-first="false" >
                                                            <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                        </multiselect>
                                                    </div>
                                                </div>
                                                <div class="v-text-field__details"><div class="v-messages theme--light"><div class="v-messages__wrapper">{{form.errors.get('task_priority')}}</div></div></div>
                                            </div>
                                        </div>
                                    </v-card>
                                </v-flex>
                                <v-flex xs12>
                                    <v-card color="white">
                                        <v-btn type="submit" color="info"> Save</v-btn>
                                        <v-btn @click="closeForm"> Close</v-btn>
                                    </v-card>
                                </v-flex>
                                                      
                            </v-layout>
                        </v-container> 
                    </v-form>
                </v-app>
            </div>
        </div>
</div>
          
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import Task from '../../models/Task';
    import TaskGrid from './TaskGrid.vue';
    export default {
        components: {
            Multiselect,TaskGrid
        },
        props: ['task'],
        data() {
            return {
                form: new Form({
                    task_desc: this.task.task_desc,
                    task_start_date: this.task.task_start_date,
                    task_end_date: this.task.task_end_date,
                    errors:'',
                    project_id: this.$route.params.id,
                    task_assignee:{},
                    task_status:this.task.status,
                    task_priority:this.task.priority,
                    task_id: this.task.id,
                    task_point:this.task.task_point,
                    userstory_id: this.task.userstory_id,
                    task_heirarchy: this.task.task_heirarchy,
                    remainingPoint:0,
                }),
                start_date: false,
                end_date: false,
                cardWidth: 'col-md-6',
                isAddVisible: false,
                loader:null,
                uploadwbs: false,  
                isUploadVisible:false,
                wbserrors:'',
                dialog:false,
                latestTasks:0,
                users:[],
                status:[],
                priority:[],
                
            }
        },
        created() {
            
            Task.allUsers(users => this.users = users);
            Task.allStatus(status => this.status = status);
            Task.allPriority(priority => this.priority = priority);
        },
        methods: {
            onChangeFileUpload(){
                this.form.file = this.$refs.file.files[0];
                this.isUploadVisible = true;
            },
            onSubmit() {
                this.form.post('/task/update/' + this.task.id)
               .then(task => this.$emit('completed', task)); 
            },   
            closeWBSForm() {
                this.$emit('closeWBSForm');
            },
            closeForm()
            {
                this.$emit('closeForm');
            }
        },
        mounted() {
            console.log('Task mounted.');
            
            
        },
        computed: {
            calcRemainingPoint() {
                if(this.task.task_heirarchy == 1)
                {
                    Task.getTaskRemainingPoints(points => this.form.remainingPoint = points, this.task.userstory_id,1);    
                }else if(this.task.task_heirarchy == 2){
                    Task.getTaskRemainingPoints(points => this.form.remainingPoint = points, this.task.id,2);  
                }
                return "'" + this.form.remainingPoint + "'";
            }
        },
        watch: {
            priority() {
                this.form.task_assignee = {id: this.task.assignee.id, name:this.task.assignee.first_name + ' ' + this.task.assignee.last_name};
            }
        }   
    }
</script>

