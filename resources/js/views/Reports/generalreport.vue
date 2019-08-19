<template>
    <div class="card-body p-0">
        <div class="d-flex flex-column">
            <div class="flex-1">
                <div id="app" class="aclmain">
                <v-app id="inspire">
                    <div>
                        <v-flex xs12 sm3 d-flex justify-content-center>
                            <v-select :items="reports" item-text="report_name" item-value="id"  v-model="currentReport" label="Select Report" box @change="onReportTabChange"></v-select>
                        </v-flex>
                        <div id="acltablediv">
                            <v-progress-linear v-show="isLoading" v-slot:progress color="blue" indeterminate :height="3"></v-progress-linear>    
                            <component :is="dashboard"></component>
                            <router-view></router-view>
                        </div>
                    </div>
                </v-app>
                </div>
            </div>
        </div>
    </div>         
</template>

<script>
    import resourcereport from './resourcereport';
    import projectreport from './projectreport';
    import timecardreport from './timecardreport';

    export default {
        components: {
            resourcereport, projectreport, timecardreport
        },
        data() {
            return {
                reports: [ {id: 1, report_name: 'Resource Report'}, {id: 2, report_name: 'Project Report'}, {id: 3, report_name: 'Timecard Report'}],
                roleaccesslist:[],
                cardWidth: 'col-md-12',
                currentReport: 1,
                roles: [],
                isLoading: false,
                dashboard:'resourcereport',
            }   
        },
        created() {

        },
        methods: {
            onReportTabChange(id) {
                this.currentReport = id;
                if(id == 1) {
                    this.dashboard = 'resourcereport';
                }
                if(id == 2) {
                    this.dashboard = 'projectreport';
                }
                if(id == 3) {
                    this.dashboard = 'timecardreport';
                }
            },
        },
        mounted() {
            // this.isLoading = true;
            // Acl.roles(roles => this.roles = roles)      
            console.log('Component mounted.')
        },
        watch: {
        }
    }
</script>
