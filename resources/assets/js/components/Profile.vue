<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="container py-3">
                <div class="row">
                    <div class="mx-auto col-sm-6">
                        <!-- form user info -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">User Information</h4>
                            </div>
                            <div class="card-body">
                                <form class="form" role="form" autocomplete="off">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">First name</label>
                                        <div class="col-lg-9">
                                            <input :disabled="updated" class="form-control" type="text" v-model="user.name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Email</label>
                                        <div class="col-lg-9">
                                            <input :disabled="updated" class="form-control" type="email" v-model="user.email">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Company</label>
                                        <div class="col-lg-9">
                                            <select :disabled="updated" id="user.company_id" class="form-control" v-model="user.company_id">
                                                <option v-for="company in companies" :value="company.id">{{company.name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- New Fields-->
                        <div class="form-group row">
                            <label for="street" class="col-lg-3 col-form-label form-control-label">Street Address</label>
                            <div class="col-lg-9">
                                <input :disabled="updated" id="street" type="string" class="form-control" name="street" v-model="user.street" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="city" class="col-lg-3 col-form-label form-control-label">City</label>
                            <div class="col-lg-9">
                                <input :disabled="updated" id="city" type="string" class="form-control" name="city" v-model="user.city" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="state" class="col-lg-3 col-form-label form-control-label">State</label>
                            <div class="col-lg-9">
                                <input :disabled="updated" id="state" type="string" class="form-control" name="state" v-model="user.state" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="zip" class="col-lg-3 col-form-label form-control-label">Zip</label>
                            <div class="col-lg-9">
                                <input :disabled="updated" id="zip" type="string" class="form-control" name="zip" v-model="user.zip" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="commutesmart_frequency" class="col-lg-3 col-form-label form-control-label">Commutesmart Frequency</label>
                            <div class="col-lg-9">
                                <select :disabled="updated" id="commutesmart_frequency" type="string" class="form-control" name="commutesmart_frequency" v-model="user.commutesmart_frequency" required>
                                    <option value="Most Days">Most Days</option>
                                    <option value="Once or twice a week">Once or twice a week</option>
                                    <option value="Once or twice a month">Once or twice a month</option>
                                    <option value="I almost always drive myself">I almost always drive myself</option>
                                </select>
                            </div>
                        </div>
                        <!-- End New Fields -->
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Password</label>
                                        <div class="col-lg-9">
                                            <input :disabled="updated" class="form-control" type="password" value="11111122333">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Confirm</label>
                                        <div class="col-lg-9">
                                            <input :disabled="updated" class="form-control" type="password" value="11111122333">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label"></label>
                                        <div class="col-lg-9">
                                            <input :disabled="updated" v-on:click="saveChanges()" type="button" class="btn btn-primary" value="Save Changes">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /form user info -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  mounted() {
    console.log(this.data);
  },
  props: {
    data: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      user: this.data.user,
      companies: this.data.companies,
      updated: false
    };
  },
  methods: {
    saveChanges() {
        console.log(this.user);
        axios
        .put(`/profile/user-update`, this.user)
        .then(response => {
            this.updated = response.data.status;
        })
        .catch(error => {});
    }
  },
  computed: {}
};
</script>
