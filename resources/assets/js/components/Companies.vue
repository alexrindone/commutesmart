<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="container py-3">
                <div class="row">
                    <div class="mx-auto col-sm-12">
                        <!-- form user info -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Add Company</h4>
                            </div>
                            <div class="card-body">
                                <form class="form" role="form" autocomplete="off">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Name</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" type="text" v-model="form.name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Employee Count</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" type="number" v-model="form.employee_count">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Size</label>
                                        <div class="col-lg-9">
                                            <select id="size" class="form-control" v-model="form.size">
                                                <option value="Micro">Micro (2-9 employees)</option>
                                                <option value="Small">Small (10-24 employees)</option>
                                                <option value="Medium">Medium (25-99 employees)</option>
                                                <option value="Large">Large (100-499 employees)</option>
                                                <option value="Major">Major (500+ employees)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label"></label>
                                        <div class="col-lg-9">
                                            <input type="reset" class="btn btn-secondary" value="Cancel">
                                            <input v-on:click="addCompany()" type="button" class="btn btn-primary" value="Add">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /form user info -->
                    </div>
                </div>
            </div>
            <div class="container py-3">
                <div class="row">
                    <div class="mx-auto col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Companies</h4>
                            </div>
                            <div class="card-body">
                                <table class="table" v-if="companies.length > 0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">Size</th>
                                            <th scope="col"># of Employees</th>
                                            <th scope="col" class="text-right"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="company in companies">
                                            <td v-if="activeEditId == company.id">
                                                <input class="form-control" type="text" v-model="company.name">
                                            </td>
                                            <td v-else>{{company.name}}</td>
                                            <td v-if="activeEditId == company.id">
                                                <select id="size" class="form-control" v-model="company.size">
                                                    <option value="Micro">Micro (2-9 employees)</option>
                                                    <option value="Small">Small (10-24 employees)</option>
                                                    <option value="Medium">Medium (25-99 employees)</option>
                                                    <option value="Large">Large (100-499 employees)</option>
                                                    <option value="Major">Major (500+ employees)</option>
                                                </select>
                                            </td>
                                            <td v-else>{{company.size}}</td>
                                            <td v-if="activeEditId == company.id">
                                                <input class="form-control" type="number" v-model="company.employee_count">
                                            </td>
                                            <td v-else>{{company.employee_count}}</td>
                                            <td class="text-right">
                                                <div class="btn-group" role="group" aria-label="Modify">
                                                    <button v-if="activeEditId != company.id" type="button" class="btn btn-secondary" v-on:click="activeEditId = company.id">Edit</button>
                                                    <button v-if="activeEditId == company.id" v-on:click="editCompany(company)" type="button" class="btn btn-secondary">Save</button>
                                                    <button v-if="activeEditId == company.id" type="button" class="btn btn-secondary" v-on:click="activeEditId = ''">Cancel</button>
                                                    <button v-if="activeEditId != company.id" v-on:click="deleteCompany(company.id)" type="button" class="btn btn-danger">Delete</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p v-else>No companies found.</p>
                            </div>
                        </div>
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
      required: false
    }
  },
  data() {
    return {
      form: {
        size: "",
        employee_count: "",
        name: ""
      },
      companies: this.data.companies,
      activeEditId: ""
    };
  },
  methods: {
    addCompany() {
      axios
        .post(`/admin/add-company`, this.form)
        .then(response => {
          console.log(response.data.payload);
          this.form = {
            size: "",
            employee_count: "",
            name: ""
          };
          this.companies.push(response.data.payload);
        })
        .catch(error => {});
    },
    deleteCompany(companyId) {
        axios.delete(`/admin/${companyId}/delete-company`)
                .then(response => {
                    // Remove task from tasks array
                    let index = this.companies.findIndex(x => {
                        return x.id == companyId;
                    });
                    this.companies.splice(index, 1);
                })
                .catch(error => {
                   
                });
    },
    editCompany(company) {
        this.activeEditId = '';
        axios
        .put(`/admin/${company.id}/edit-company`, company)
        .then(response => {
          console.log(response.data.payload);

        })
        .catch(error => {});
    }
  },
  computed: {

  }
};
</script>