<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default shadow-sm">
                    <div class="card-header no-border">CNAAP Operate</div>

                    <!-- <div class="card-body"> -->
                        <div class="card-body project-tabs p-none">
                            <div style="d-flex flex-column">
                                <b-card no-body>
                                    <b-tabs>
                                        <b-tab title="Project Details" active>
                                            <form @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                                                <div class="form-group row">
                                                    <label for="email" class="col-sm-2 col-form-label">Project Name</label>
                                                    <div class="col-sm-10">
                                                      <input type="text"  class="form-control" id="email" placeholder="Enter Project Name" value="CNAAP Operate">
                                                    </div>
                                                  </div>
                                                  <div class="hr-line-dashed"></div>
                                                  <div class="form-group row">
                                                    <label for="inputPassword" class="col-sm-2 col-form-label">Start Date</label>
                                                    <div class="col-sm-10">
                                                      <input type="text" class="form-control" id="inputPassword" placeholder="Enter Start Date" value="2019-01-01">
                                                    </div>
                                                  </div>
                                                  <div class="hr-line-dashed"></div>
                                                <div class="form-group row">
                                                    <label for="inputPassword" class="col-sm-2 col-form-label">End Date</label>
                                                    <div class="col-sm-10">
                                                      <input type="text" class="form-control" id="inputPassword" placeholder="Enter Start Date" value="2020-01-01">
                                                    </div>
                                                  </div>
                                                  <div class="hr-line-dashed"></div>
                                                 <div class="form-group row">
                                                    <label for="inputPassword" class="col-sm-2 col-form-label">Project Manager</label>
                                                    <div class="col-sm-10">
                                                      <select class="form-control">
                                                          <option>John Doe</option>
                                                        </select>
                                                    </div>
                                                  </div>
                                                  <div class="hr-line-dashed"></div>
                                                  <div class="form-group row">
                                                    <label for="inputPassword" class="col-sm-2 col-form-label">Stake Holders</label>
                                                    <div class="col-sm-10">
                                                      <select class="form-control">
                                                          <option>Jon Doe</option>
                                                        </select>
                                                    </div>
                                                  </div>
                                                  <div class="hr-line-dashed"></div>
                                                  <div class="form-group row mb-0 justify-content-center align-items-center">
                                                    <div class="">
                                                        <button type="submit" class="btn btn-primary">
                                                            Update
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </b-tab>
                                    <b-tab title="Add Plan \ Estimation">
                                      Tab Contents 2
                                    </b-tab>
                                    <b-tab title="Add Risks">
                                      Tab Contents 2
                                    </b-tab>
                                    <b-tab title="Add Scopes">
                                      Tab Contents 2
                                    </b-tab>
                                  </b-tabs>
                                </b-card>
                            </div>
                        </div>
                    <!-- </div> -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
