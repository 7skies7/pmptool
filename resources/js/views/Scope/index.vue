<template>

    <div class="container-fluid">
                <div class="projectTabs">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <!-- <div class="card card-default shadow-sm border-0">
                        <div class="card-header">
                            <h4>Project Scope Tabs</h4>
                        </div>
                    </div> -->
                    <v-toolbar tabs>
        <v-toolbar-side-icon></v-toolbar-side-icon>
  
        <v-toolbar-title>Page title</v-toolbar-title>
  
        <v-spacer></v-spacer>
  
        <v-btn icon>
          <v-icon>search</v-icon>
        </v-btn>
  
        <v-btn icon>
          <v-icon>more_vert</v-icon>
        </v-btn>
  
        <template v-slot:extension>
          <v-tabs
            v-model="tabs"
            fixed-tabs
            color="transparent"
          >
            <v-tabs-slider></v-tabs-slider>
            <v-tab href="#mobile-tabs-5-1" class="primary--text">
              <v-icon>phone</v-icon>
            </v-tab>
  
            <v-tab href="#mobile-tabs-5-2" class="primary--text">
              <v-icon>favorite</v-icon>
            </v-tab>
  
            <v-tab href="#mobile-tabs-5-3" class="primary--text">
              <v-icon>account_box</v-icon>
            </v-tab>
          </v-tabs>
        </template>
      </v-toolbar>
  

                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div :class="cardWidth">
                <div class="card card-default shadow-sm border-0">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="flex-1">
                                <img src="/svg/project.svg" class="header-svg"/>
                                <span>Scope</span>
                            </div>
                            <div class="pb-11">
                                <button v-if="isAddVisible" class="btn btn-add float-right" @click="showaddProject">Add New</button>
                            </div>
                        </div>
                    </div>
                <div class="card-body p-0">
                        <div class="d-flex flex-column">
                            <div class="flex-1">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';

    export default {
        components: {
            Datepicker
        },
        data() {
            return{     
                tabs: null,
      text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',        
                cardWidth: 'col-md-10',
                isAddVisible: false,
            }
        },
        created() {
            
        },
        methods: {
           
        },
        mounted() {
            
        }
    }
</script>
