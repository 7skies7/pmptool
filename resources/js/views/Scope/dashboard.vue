<template>
<div class="container-fluid">
    <project-menus selected="1"></project-menus>
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card" >
                <div class="card-body">
                    <div class="sflex spacebetween">
                        <div class="childFlex">
                            <span><h5 class="card-title" style="margin-bottom:0">All Members</h5></span>
                        </div>
                        <div class="pb-11">
                            <!-- <button v-if="isAddVisible" class="btn btn-add float-right" @click="showUploadWbs">Add Tasks</button> -->
                            <v-btn color="info" small> Invite</v-btn>

                        </div>
                    </div>
                    <div><v-chip color="default" small >Abner Williams</v-chip><v-chip color="default" small >Abner Williams</v-chip><v-chip color="default" small >Abner Williams</v-chip><v-chip color="default" small >Abner Williams</v-chip><v-chip color="default" small >Abner Williams</v-chip><v-chip color="default" small >Abner Williams</v-chip></div>

                </div>
            </div>
            <div class="card" >
                <div class="card-body">
                    <h5 class="card-title">Description</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <div class="hr-line-dashed"></div>    
                    <h5 class="card-title">Project Managers</h5>
                    <p><v-chip color="teal" text-color="white">Abner Williams</v-chip><v-chip color="teal" text-color="white">John Doe</v-chip></p>
                    <div class="hr-line-dashed"></div>    
                    <h5 class="card-title">Project Start & End Date</h5>
                    <p>
                        <v-chip color="primary" text-color="white" small>
                            2019-08-01
                        </v-chip>
                        <v-chip color="primary" text-color="white" small>
                            2020-08-01
                        </v-chip>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="col-md-12">
                <div class="card" >
                    <div class="card-body">
                        <v-app id="inspire">
                        <v-stepper alt-labels>
                          <v-stepper-header>
                            <v-stepper-step step="3" complete>Design</v-stepper-step>
                            <v-divider></v-divider>
                            <v-stepper-step step="4" complete>Development</v-stepper-step>
                            <v-divider></v-divider>
                            <v-stepper-step step="5" complete>Testing</v-stepper-step>
                            <v-divider></v-divider>
                            <v-stepper-step step="6" complete>Staging</v-stepper-step>
                            <v-divider></v-divider>
                            <v-stepper-step step="7">UAT</v-stepper-step>
                          </v-stepper-header>
                        </v-stepper>
                    </v-app>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card" >
                    <div class="card-body">
                        asdasd
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</template>

<script>
    import ProjectMenus from './ProjectMenus.vue';
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';
    
    import {Chart} from 'highcharts-vue'
    export default {
        components: {
            Datepicker,highcharts: Chart, ProjectMenus
        },
        data() {
            return{
                addProgram: false,
                programs: [],
                cardWidth: 'col-md-10',
                progressbar: '80',
                progresscards: [
                    {title:"In Planning", value:"100", icon:"check_circle", color:"teal"},
                    {title:"Design", value:"100", icon:"check_circle", color:"pink"},
                    {title:"Development", value:"90", icon:"important_devices", color:"info"},
                    {title:"Testing", value:"10", icon:"bug_report", color:"teal"},


                ],        
                items: [
        { header: 'Project Feed' },
        {
          avatar: 'https://cdn.vuetifyjs.com/images/lists/1.jpg',
          title: 'Brunch this weekend?',
          subtitle: "<span class='text--primary'>Ali Connors</span> &mdash; I'll be in your neighborhood doing errands this weekend. Do you want to hang out?"
        },
        { divider: true, inset: true },
        {
          avatar: 'https://cdn.vuetifyjs.com/images/lists/2.jpg',
          title: 'Summer BBQ <span class="grey--text text--lighten-1">4</span>',
          subtitle: "<span class='text--primary'>to Alex, Scott, Jennifer</span> &mdash; Wish I could come, but I'm out of town this weekend."
        },
        { divider: true, inset: true },
        {
          avatar: 'https://cdn.vuetifyjs.com/images/lists/3.jpg',
          title: 'Oui oui',
          subtitle: "<span class='text--primary'>Sandra Adams</span> &mdash; Do you have Paris recommendations? Have you ever been?"
        },
        { divider: true, inset: true },
        {
          avatar: 'https://cdn.vuetifyjs.com/images/lists/4.jpg',
          title: 'Birthday gift',
          subtitle: "<span class='text--primary'>Trevor Hansen</span> &mdash; Have any ideas about what we should get Heidi for her birthday?"
        },
        { divider: true, inset: true },
        {
          avatar: 'https://cdn.vuetifyjs.com/images/lists/5.jpg',
          title: 'Recipe to try',
          subtitle: "<span class='text--primary'>Britta Holt</span> &mdash; We should eat this: Grate, Squash, Corn, and tomatillo Tacos."
        }
      ],
      items2: [
        { icon: 'assignment', iconClass: 'blue white--text', title: 'Risks', subtitle: 'Jan 20, 2014' },
        { icon: 'call_to_action', iconClass: 'amber white--text', title: 'Overdue ', subtitle: 'Jan 10, 2014' },
        { icon: 'assignment', iconClass: 'blue white--text', title: 'Budget', subtitle: 'Jan 20, 2014' },
        { icon: 'call_to_action', iconClass: 'amber white--text', title: 'Tasks ', subtitle: 'Jan 10, 2014' }
      ],headers: [
        {
          text: 'Dessert (100g serving)',
          align: 'left',
          sortable: false,
          value: 'name'
        },
        { text: 'Calories', value: 'calories' },
        { text: 'Fat (g)', value: 'fat' },
        { text: 'Carbs (g)', value: 'carbs' },
     
      ],
      desserts: [
        {
          name: 'Frozen Yogurt',
          calories: 159,
          fat: 6.0,
          carbs: 24,
          protein: 4.0,
          iron: '1%'
        },
        {
          name: 'Ice cream sandwich',
          calories: 237,
          fat: 9.0,
          carbs: 37,
          protein: 4.3,
          iron: '1%'
        },
        {
          name: 'Eclair',
          calories: 262,
          fat: 16.0,
          carbs: 23,
          protein: 6.0,
          iron: '7%'
        },
        {
          name: 'Cupcake',
          calories: 305,
          fat: 3.7,
          carbs: 67,
          protein: 4.3,
          iron: '8%'
        },
        {
          name: 'Gingerbread',
          calories: 356,
          fat: 16.0,
          carbs: 49,
          protein: 3.9,
          iron: '16%'
        },
        {
          name: 'Jelly bean',
          calories: 375,
          fat: 0.0,
          carbs: 94,
          protein: 0.0,
          iron: '0%'
        },
        {
          name: 'Lollipop',
          calories: 392,
          fat: 0.2,
          carbs: 98,
          protein: 0,
          iron: '2%'
        },
        {
          name: 'Honeycomb',
          calories: 408,
          fat: 3.2,
          carbs: 87,
          protein: 6.5,
          iron: '45%'
        },
        {
          name: 'Donut',
          calories: 452,
          fat: 25.0,
          carbs: 51,
          protein: 4.9,
          iron: '22%'
        },
        {
          name: 'KitKat',
          calories: 518,
          fat: 26.0,
          carbs: 65,
          protein: 7,
          iron: '6%'
        }
      ]        
            }
        },
        created() {
        },
        methods: {
           
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
