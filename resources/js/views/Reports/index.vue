<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div :class="cardWidth">
                <div class="card card-default shadow-sm border-0">
                    <div class="card-header text-xs-centre">
                        <div class="sflex spacebetween justify-content-center">
                            <div class="childFlex">
                                <span>Reports</span>
                            </div>
                        </div>
                    </div>
                    <router-view></router-view>
                </div>
            </div>
           <!--  <add-acl v-if="addCompany" @completed="addNewAcl" @closeAddForm="closeForm"></add-acl> -->
        </div>
    </div>
</template>

<script>


    export default {
        components: {
            
        },
        data() {
            return {
                
                cardWidth: 'col-md-12',
                
            }   
        },
        created() {

        },
        methods: {
            
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
