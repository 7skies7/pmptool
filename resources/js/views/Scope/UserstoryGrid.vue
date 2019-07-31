<template>
    <div>
        <v-card-title>
            <v-text-field
              v-model="search"
              append-icon="search"
              label="Search"
              single-line
              hide-details
            ></v-text-field>
            <v-spacer></v-spacer>
            <v-spacer></v-spacer>
            <v-spacer></v-spacer>
            <v-spacer></v-spacer>
        </v-card-title>
        <v-data-table
            :headers="headers"
            :items="userstories"
            :loading="isLoading"
            :search="search"
            :rows-per-page-items='[10, 20, 40, 80]'
            class="elevation-1 userstorytable"
        >
            <v-progress-linear v-show="isLoading" v-slot:progress color="blue" indeterminate></v-progress-linear>
            <template v-slot:items="props">
                <td>
                    <v-btn flat small color="primary"  @click="showUserstoryComments(props.item.id, props.item.cr_id)">{{ props.item.userstory_id }}</v-btn>
                    <!-- <v-btn flat small color="primary" :href="'home#/project/'+project_id+'/scope/'+props.item.id">{{ props.item.userstory_id }}</v-btn> -->
                </td>
                <td class="text-xs-left">{{ props.item.userstory_desc }}</td>
                <td class="text-xs-center"><b>{{ props.item.userstory_point }}</b></td>
                <td class="text-xs-center"><h5 v-if="props.item.status"><b-badge pill variant="info">{{ props.item.status.status_name }}</b-badge></h5><h5 v-else="-"></h5></td>
                <td class="text-xs-center"><h5 v-if="props.item.priority"><b-badge pill variant="info" :class="props.item.priority.priority_type">{{ props.item.priority.priority_type }}</b-badge></h5><h5 v-else="-"></h5></td>
                <td class="justify-center layout px-0 smallbtn" style="padding:7px 24px!important">
                    <v-btn v-if="isEditVisible" @click="showEditUserstory(props.item.id)" color="primary" fab depressed small dark><v-icon>edit</v-icon></v-btn>
                    <v-btn v-if="isDeleteVisible" @click="deleteUserstory(props.item.id)" color="error" fab depressed small dark><v-icon>delete</v-icon></v-btn>
                </td>
            </template>
            <template v-slot:no-results>
                <v-alert :value="true" color="error" icon="warning">
                    Your search for "{{ search }}" found no results.
                </v-alert>
            </template>
       </v-data-table>
    </div>

</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';
    import Userstory from '../../models/Userstory';
    export default {
        components: {
            Userstory
        },
        props: ['crid', 'isEditVisible', 'isDeleteVisible'],
        name: 'userstory',
        data() {
            return{
                search: '',
                userstories: [],
                headers:[
                            { text: 'CRD', align: 'center', value: 'userstory_id'},
                            { text: 'User Story', align: 'center', value: 'userstory_desc' },
                            { text: 'Story Point', align: 'center', value: 'userstory_point' },
                            { text: 'Status', align: 'center', value: 'status.status_name' },
                            { text: 'Priority', align: 'center', value: 'priority.priority_type' },
                            { text: 'Actions', align: 'center', value: 'actions', sortable: false }
                        ],
                scope: [],
                isLoading: true,
                project_id: this.$route.params.id,
            }
        },
        created() {
            Userstory.all(userstories => this.userstories = userstories, this.crid);
        },
        methods: {
            showEditUserstory(id) {
                this.$emit('showedituserstory', id)
            },
            deleteUserstory(id) {
                this.$emit('deleteuserstory', id)
            },
            showUserstoryComments(id, cr_id){
                this.$emit('showuserstorycomments', id, cr_id)
            }
        },
        mounted() {
            console.log('Userstory Grid Mounted.')
        },
        watch: {
            userstories(){
                this.isLoading = false;    
            }
        }
    }
</script>
