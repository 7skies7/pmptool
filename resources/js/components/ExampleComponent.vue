<template>
    <div class="col-md-6">
        <div class="card card-default">
            <div class="card-header">Program Component</div>

            <div class="card-body">
                <form @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                    <!-- <input type="hidden" > -->

                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Program Name</label>

                        <div class="col-md-6">
                            <input id="program_name" type="text" class="form-control" placeholder="Enter program name" v-model="form.program_name" name="program_name" value="" >

                            <!-- @if ($errors->has('program_name')) -->
                                <span class="invalid-feedback" role="alert" v-if="form.errors.has('program_name')" v-text="form.errors.get('program_name')"></span>
                            <!-- @endif -->
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="program_start_date" class="col-md-4 col-form-label text-md-right">Program Start Date</label>

                        <div class="col-md-6">
                            <!-- <input id="program_start_date" v-model="form.program_start_date" type="text" class="form-control" name="program_start_date" value="" > -->
                            <Datepicker v-model="form.program_start_date" input-class="form-control" :format="customFormatter"></Datepicker>
                            <!-- @if ($errors->has('program_name')) -->
                                <span class="invalid-feedback" role="alert">
                                    <!-- <strong>{{ $errors->first('program_name') }}</strong> -->
                                </span>
                            <!-- @endif -->
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="program_end_date" class="col-md-4 col-form-label text-md-right">Program End Date</label>

                        <div class="col-md-6">
                            <!-- <input id="program_end_date" type="text" v-model="form.program_end_date" class="form-control" name="program_end_date" value="" > -->
                            <Datepicker v-model="form.program_end_date" input-class="form-control" :format="customFormatter"></Datepicker>

                            <!-- @if ($errors->has('program_name')) -->
                                <span class="invalid-feedback" role="alert">
                                    <!-- <strong>{{ $errors->first('program_name') }}</strong> -->
                                </span>
                            <!-- @endif -->
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="program_desc" class="col-md-4 col-form-label text-md-right">Description</label>

                        <div class="col-md-6">
                            <textarea id="program_desc" class="form-control" placeholder="Enter program description" v-model="form.program_desc" name="form.program_desc" value="" ></textarea>

                            <!-- @if ($errors->has('program_name')) -->
                                <span class="invalid-feedback" role="alert">
                                    <!-- <strong>{{ $errors->first('program_name') }}</strong> -->
                                </span>
                            <!-- @endif -->
                        </div>
                    </div>


                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary" :disabled="form.errors.any()">
                                Login
                            </button>
                        </div>
                    </div>
                </form>
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
            return {
                form: new Form({
                    program_name: '',
                    program_start_date: '',
                    program_end_date: '',
                    program_desc: '',
                })
            }
        },
        methods: {
            onSubmit() {
                this.form.post('/program/store')
               .then(program => alert('asdasdasd'));
               // .then(status => this.$emit('completed', status));
            },
            customFormatter(date) {
                return moment(date).format('YYYY-MM-DD');
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
