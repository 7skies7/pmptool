<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div :class="cardWidth">
                <div class="card card-default shadow-sm border-0">
                    <div class="card-header">Dashboard</div>
                    <div class="card-body">
                        <div style="display:flex;flex-direction:column">
                                                        
                        </div>
                    </div>
                </div>
            </div>
            <add-program v-if="addProgram" @completed="addNewProgram"></add-program>
        </div>
    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';
    import AddProgram from '../components/AddProgram.vue';
    import Program from '../models/Program';
    export default {
        components: {
            Datepicker,AddProgram
        },
        data() {
            return{
                addProgram: false,
                programs: [],
                cardWidth: 'col-md-10'
            }
        },
        created() {
            Program.all(programs => this.programs = programs)            
                // .then(({data}) => this.statuses = data)
        },
        methods: {
           showAddProgram() {
                this.addProgram = true;
                this.cardWidth = 'col-md-6';
           },
           addNewProgram(program) {
                this.programs.unshift(program);
                alert("Your new program has been added successfully.");
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
