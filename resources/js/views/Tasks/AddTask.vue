    <template>
    <div class="col-md-6">
        <div class="card card-default shadow-sm">
            <div class="card-header">Add Sub Task</div>

            <div class="card-body">
                <v-app id="editTask">
                    <v-form @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                        <v-container grid-list-md text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-card color="white">
                                        <v-text-field v-model="form.task_title" label="Task name" placeholder="Task Name" :messages="form.errors.get('task_title')"></v-text-field>
                                    </v-card>
                                </v-flex>
                                <v-flex xs12>
                                    <v-card color="white">
                                        <v-text-field v-model="form.task_desc" label="Task Description" placeholder="Task Description" :messages="form.errors.get('task_desc')"></v-text-field>
                                    </v-card>
                                </v-flex>
                                <v-flex xs12>
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
                                                        <multiselect v-model="form.task_assignee" :options="users" :searchable="true" :clear-on-select="true" :preserve-search="false" placeholder="Select Assignee" label="name" track-by="id" :preselect-first="false" >
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
                                    <v-card color="white">
                                        <div class="v-input v-text-field v-input--is-label-active theme--light">
                                            <div class="v-input__control">
                                                <div class="v-input__slot">
                                                    <div class="v-text-field__slot multiselectwps">
                                                        <label aria-hidden="true" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Task Type</label>
                                                        <multiselect v-model="form.task_type" :options="tasktypes" :searchable="true" :clear-on-select="true" :preserve-search="false" placeholder="Select Type" label="type" track-by="id" :preselect-first="false" >
                                                            <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                        </multiselect>
                                                    </div>                        
                                                </div>
                                                <div class="v-text-field__details"><div class="v-messages theme--light"><div class="v-messages__wrapper">{{form.errors.get('task_type')}}</div></div></div>
                                            </div>
                                        </div>
                                    </v-card>
                                </v-flex>
                                <v-flex xs6>
                                    <v-card color="white" >
                                        <v-menu v-model="start_date" :close-on-content-click="true" :nudge-right="40" lazy transition="scale-transition" offset-y full-width min-width="290px">
                                            <template v-slot:activator="{ on }">
                                                <v-text-field v-model="form.task_start_date" label="Select Start Date" prepend-icon="event" readonly v-on="on" :messages="form.errors.get('task_start_date')"></v-text-field>
                                          </template>
                                          <v-date-picker v-model="form.task_start_date" @input="start_date = false"></v-date-picker>
                                        </v-menu>
                                    </v-card>
                                </v-flex>
                                <v-flex xs6>
                                    <v-card color="white">
                                        <v-menu v-model="end_date" :close-on-content-click="true" :nudge-right="40" lazy transition="scale-transition" offset-y full-width min-width="290px">
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
                                                        <multiselect v-model="form.task_status" :options="status" :clear-on-select="true" :preserve-search="false" placeholder="Select Status" label="status_name" track-by="id" :preselect-first="false" >
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
                                                        <multiselect v-model="form.task_priority" :options="priority"  :clear-on-select="true" :preserve-search="false" placeholder="Select Priority" label="priority_type" track-by="id" :preselect-first="false" >
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
        props: ['taskid', 'point'],
        data() {
            return {
                form: new Form({
                    task_title: '',
                    task_desc: '',
                    task_start_date: new Date().toISOString().substr(0, 10),
                    task_end_date: new Date().toISOString().substr(0, 10),
                    errors:'',
                    project_id: this.$route.params.id,
                    task_assignee:'',
                    task_status:'',
                    task_priority:'',
                    parent_id: this.taskid,
                    task_point:'',
                    remainingPoint:0,
                    task_type:'',
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
                tasktypes:[],
                
            }
        },
        created() {
            Task.allUsers(users => this.users = users);
            Task.allStatus(status => this.status = status);
            Task.allPriority(priority => this.priority = priority);
            Task.allType(types => this.tasktypes = types);
        },
        methods: {
            onChangeFileUpload(){
                this.form.file = this.$refs.file.files[0];
                this.isUploadVisible = true;
            },
            onSubmit() {
                this.form.post('/task/store')
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
            console.log('Task mounted.')
        },
        computed: {
            calcRemainingPoint() {
                Task.getRemainingPoints(points => this.form.remainingPoint = points, this.point, this.taskid);
                return "'Remaining Points:" + this.form.remainingPoint + "'";
            }
        },
        watch: {

        }   
    }
</script>

