<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="container py-3">
                <div class="row">
                    <div class="mx-auto col-sm-12">
                        <!-- form user info -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Add Challenge</h4>
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
                                        <label class="col-lg-3 col-form-label form-control-label">Slug</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" type="text" v-model="form.slug">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Image Url</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" type="text" v-model="form.image_url">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Start Date</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" type="date" v-model="form.start_date">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">End Date</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" type="date" v-model="form.end_date">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Type</label>
                                        <div class="col-lg-9">
                                            <select id="size" class="form-control" v-model="form.type">
                                                <option value="Individual">Individual</option>
                                                <option value="Company">Company</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Level One</label>
                                        <div class="col-lg-6">
                                            <input placeholder="Image Url for Icon" class="form-control" type="text" v-model="form.level_one_icon">
                                        </div>
                                        <div class="col-lg-3">
                                            <input placeholder="Level Label" class="form-control" type="text" v-model="form.level_one_label">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Level Two</label>
                                        <div class="col-lg-6">
                                            <input placeholder="Image Url for Icon" class="form-control" type="text" v-model="form.level_two_icon">
                                        </div>
                                        <div class="col-lg-3">
                                            <input placeholder="Level Label" class="form-control" type="text" v-model="form.level_two_label">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Level Three</label>
                                        <div class="col-lg-6">
                                            <input placeholder="Image Url for Icon" class="form-control" type="text" v-model="form.level_three_icon">
                                        </div>
                                        <div class="col-lg-3">
                                            <input placeholder="Level Label" class="form-control" type="text" v-model="form.level_three_label">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Level Four</label>
                                        <div class="col-lg-6">
                                            <input placeholder="Image Url for Icon" class="form-control" type="text" v-model="form.level_four_icon">
                                        </div>
                                        <div class="col-lg-3">
                                            <input placeholder="Level Label" class="form-control" type="text" v-model="form.level_four_label">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label"></label>
                                        <div class="col-lg-9">
                                            <input type="reset" class="btn btn-secondary" value="Cancel">
                                            <input v-on:click="addChallenge()" type="button" class="btn btn-primary" value="Add">
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
                <div class="row" v-for="challenge in challenges">
                    <div class="mx-auto col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Challenge</h4>
                            </div>
                            <div class="card-body">
                                <table class="table" v-if="challenges.length > 0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">Start Date</th>
                                            <th scope="col">End Date</th>
                                            <th scope="col">Type</th>
                                            <th scope="col" class="text-right"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td v-if="activeEditId == challenge.id">
                                                <input class="form-control" type="text" v-model="challenge.name">
                                            </td>
                                            <td v-else>{{challenge.name}}</td>
                                            <td v-if="activeEditId == challenge.id">
                                                <input class="form-control" type="date" v-model="challenge.start_date">
                                            </td>
                                            <td v-else>{{challenge.start_date}}</td>
                                            <td v-if="activeEditId == challenge.id">
                                                <input class="form-control" type="date" v-model="challenge.end_date">
                                            </td>
                                            <td v-else>{{challenge.end_date}}</td>
                                            <td v-if="activeEditId == challenge.id">
                                                <select id="size" class="form-control" v-model="challenge.type">
                                                    <option value="Individual">Individual</option>
                                                    <option value="Company">Company</option>
                                                </select>
                                            </td>
                                            <td v-else>{{challenge.type}}</td>
                                            <td class="text-right">
                                                <div class="btn-group" role="group" aria-label="Modify">
                                                    <button v-on:click="activeEditId = challenge.id" type="button" class="btn btn-secondary">Edit</button>

                                                    <button v-if="activeEditId == challenge.id" v-on:click="editChallenge(challenge)" type="button" class="btn btn-secondary">Save</button>
                                                    <button v-if="activeEditId == challenge.id" type="button" class="btn btn-secondary" v-on:click="activeEditId = ''">Cancel</button>
                                                    <button v-if="activeEditId != challenge.id" v-on:click="deleteChallenge(challenge.id)" type="button" class="btn btn-danger">Delete</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table" v-if="challenges.length > 0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Level One Icon</th>
                                            <th scope="col">Level Two Icon</th>
                                            <th scope="col">Level Three Icon</th>
                                            <th scope="col">Level Four Icon</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td v-if="activeEditId == challenge.id">
                                                <input class="form-control" type="text" v-model="challenge.level_one_icon">
                                            </td>
                                            <td v-else>{{challenge.level_one_icon}}</td>
                                            <td v-if="activeEditId == challenge.id">
                                                <input class="form-control" type="text" v-model="challenge.level_two_icon">
                                            </td>
                                            <td v-else>{{challenge.level_two_icon}}</td>
                                            <td v-if="activeEditId == challenge.id">
                                                <input class="form-control" type="text" v-model="challenge.level_three_icon">
                                            </td>
                                            <td v-else>{{challenge.level_three_icon}}</td>
                                            <td v-if="activeEditId == challenge.id">
                                                <input class="form-control" type="text" v-model="challenge.level_four_icon">
                                            </td>
                                            <td v-else>{{challenge.level_four_icon}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table" v-if="challenges.length > 0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Level One Label</th>
                                            <th scope="col">Level Two Label</th>
                                            <th scope="col">Level Three Label</th>
                                            <th scope="col">Level Four Label</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td v-if="activeEditId == challenge.id">
                                                <input class="form-control" type="text" v-model="challenge.level_one_label">
                                            </td>
                                            <td v-else>{{challenge.level_one_label}}</td>
                                            <td v-if="activeEditId == challenge.id">
                                                <input class="form-control" type="text" v-model="challenge.level_two_label">
                                            </td>
                                            <td v-else>{{challenge.level_two_label}}</td>
                                            <td v-if="activeEditId == challenge.id">
                                                <input class="form-control" type="text" v-model="challenge.level_three_label">
                                            </td>
                                            <td v-else>{{challenge.level_three_label}}</td>
                                            <td v-if="activeEditId == challenge.id">
                                                <input class="form-control" type="text" v-model="challenge.level_four_label">
                                            </td>
                                            <td v-else>{{challenge.level_four_label}}</td>
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
        type: "Individual",
        slug: "",
        start_date: "",
        end_date: "",
        image_url: "",
        name: ""
      },
      challenges: this.data.challenges,
      activeEditId: ""
    };
  },
  methods: {
    addChallenge() {
      axios
        .post(`/admin/add-challenge`, this.form)
        .then(response => {
          console.log(response.data.payload);
          this.form = {
            type: "Individual",
            slug: "",
            start_date: "",
            end_date: "",
            image_url: "",
            name: "",
            level_one_label: "",
            level_one_icon: "",
            level_two_label: "",
            level_two_icon: "",
            level_three_label: "",
            level_three_icon: "",
            level_four_label: "",
            level_four_icon: ""
          };
          this.challenges.push(response.data.payload);
        })
        .catch(error => {});
    },
    deleteChallenge(challengeId) {
        axios.delete(`/admin/${challengeId}/delete-challenge`)
                .then(response => {
                    // Remove task from tasks array
                    let index = this.challenges.findIndex(x => {
                        return x.id == challengeId;
                    });
                    this.challenges.splice(index, 1);
                })
                .catch(error => {
                   
                });
    },
    editChallenge(challenge) {
        console.log(challenge);
        this.activeEditId = '';
        axios
        .put(`/admin/${challenge.id}/edit-challenge`, challenge)
        .then(response => {
          console.log(response.data.payload);

        })
        .catch(error => {});
    }
  },
  computed: {}
};
</script>