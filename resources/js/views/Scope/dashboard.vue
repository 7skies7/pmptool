<template>
<div id="dashboard" style="margin-top:-24px!important">
    <v-app id="inspire">
        <v-container grid-list-md text-xs-center class="unsetWidth">
          <v-layout row wrap>
            <v-flex flex xs12 sm6 md2>
                <v-card class="mx-auto" color="white" dark max-width="400">
                    <v-card-title>
                        <v-icon large left > mdi-twitter </v-icon>
                        <span class="title font-weight-light black--text" style="font-size:16px; margin-top:10px">Overall Progress</span>
                    </v-card-title>
                    <v-card-text class="headline font-weight-bold">   
                        <v-progress-circular :rotate="360" :size="100" :width="15" value="78%" color="#26c6da" >78%</v-progress-circular>     
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex flex xs12 sm6 md8>
                <v-card color="white lighten-2" dark>
                    <!-- <v-flex flex xs12>
                        
                         <div class="text-xs-center" style="padding-left:20px;padding-right:20px">
                            <v-progress-linear height="10" v-model="progressbar" color="#26c6da"></v-progress-linear>
                        </div>
                        
                    </v-flex> -->
                    <v-flex d-flex>
                        <v-layout row wrap>
                            <v-flex v-for="card in progresscards" :key="card" d-flex xs3>
                                <v-card color="white lighten-2" dark style=" box-shadow:unset!important">
                                    <v-card-title class="text-xs-center" style="margin-top:-8px!important;align-items:center">
                        <span class="title font-weight-light black--text">{{ card.title }}</span>
                    </v-card-title>
                                <v-card-text><v-progress-circular :rotate="360" :size="100" :width="15" :value="card.value" :color="card.color" class="vprog"><v-icon :color="card.color">{{card.icon}}</v-icon></v-progress-circular></v-card-text>
                                
                                </v-card>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                </v-card>
            </v-flex>   
            <v-flex flex xs12 sm6 md2>
                <v-card class="mx-auto" color="#26c6da" dark max-width="400">
                    <v-card-title>
                        <v-icon large left > mdi-twitter </v-icon>
                        <span class="title font-weight-light">ICM Deadline</span>
                    </v-card-title>
                    <v-card-text class="headline font-weight-bold">   
                        120 Days     
                    </v-card-text>
                    <v-card-actions>
                        <v-list-tile class="grow">
                            <v-list-tile-avatar color="#E34C6E">
                                <v-icon>flag</v-icon>
                            </v-list-tile-avatar>  
                            <v-list-tile-content>
                                <v-list-tile-title>Friday 21, 2019</v-list-tile-title>
                            </v-list-tile-content>
                            
                        </v-list-tile>
                    </v-card-actions>
                </v-card>
            </v-flex>
            
          </v-layout>
     
          <v-layout row wrap>
            
            
          </v-layout>
      </v-container>
    </v-app>
</div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';
    
    import {Chart} from 'highcharts-vue'
    export default {
        components: {
            Datepicker,highcharts: Chart
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
