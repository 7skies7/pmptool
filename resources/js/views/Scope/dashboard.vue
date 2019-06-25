<template>
    <div class="row justify-content-center">
        <div :class="cardWidth">
            <div class="card card-default shadow-sm border-0">
                <div class="card-header">
                    <div class="d-flex">
                        <div class="flex-1">
                            <img src="/svg/project.svg" class="header-svg"/>
                            <span>Dashboard</span>
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
</template>

<script>
    export default {
        data() {
            return {
                cardWidth: 'col-md-10',
                isAddVisible: false    
            }
        },
        mounted() {
            console.log('Dashboard mounted.')
        }   
    }
</script>

