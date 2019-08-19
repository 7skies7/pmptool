    <template>
    <div id="app">
      <v-app id="inspire">
            <v-card-title>
            <v-spacer></v-spacer>
            <v-spacer></v-spacer>
            <v-spacer></v-spacer>
            <v-spacer></v-spacer>
            <v-text-field
              v-model="search"
              append-icon="search"
              label="Search"
              single-line
              hide-details
            ></v-text-field>
            </v-card-title>
            <v-data-table :headers="headers" :search="search" :items="records" class="elevation-1">
                <template v-slot:items="props">
                    <td>{{ props.item.resource_name }}</td>
                    <td class="text-xs-centre">{{ props.item.project_name }}</td>
                    <td class="text-xs-left">{{ props.item.crd_title }}</td>
                    <td class="text-xs-left">{{ props.item.userstory_desc }}</td>
                    <td class="text-xs-left">{{ props.item.task_title }}</td>
                    <td class="text-xs-left">{{ props.item.sub_task_title }}</td>
                    <td class="text-xs-center">{{ props.item.created_at }}</td>
                    <td class="text-xs-left">{{ props.item.comments }}</td>
                    <td class="text-xs-center">{{ props.item.task_hours }}</td>
                    <td class="text-xs-center">{{ props.item.timecard }}</td>
                </template>
            </v-data-table>
        </v-app>
    </div>
</template>

<script>
    import Report from '../../models/Report';
    export default {
        components: {
        },
        data() {
            return {
                cardWidth: 'col-md-12',
                search: '',
                headers: [
                    { text: 'Resource Name', align: 'left', value: 'resource_name'},
                    { text: 'Project Name', value: 'project_name' },
                    { text: 'CR Title', value: 'crd_title' },
                    { text: 'Userstory Name', value: 'userstory_desc' },
                    { text: 'Task Name', value: 'task_title' },
                    { text: 'Sub Task Name', value: 'sub_task_title' },
                    { text: 'Date', value: 'created_at' },
                    { text: 'Comment', value: 'comments' },
                    { text: 'Hours Logged', value: 'task_hours', class: 'hourstd'},
                    { text: 'Face Capture', value: 'timecard' }
                ],
                records: []
            }   
        },
        created() {
            Report.getResourceReport(records => this.records = records);
        },
        methods: {
            onReportTabChange(id) {
                this.isLoading = true;
            },
        },
        mounted() {
            this.isLoading = true;
            // Acl.roles(roles => this.roles = roles)      
            console.log('Component mounted.')
        },
        watch: {
        }
    }
</script>
