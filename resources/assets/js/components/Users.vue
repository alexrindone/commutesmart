<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="container py-3">
                <div class="row">
                    <div class="mx-auto col-sm-8">
                        <div class="card">
                            <div class="card-header card-head-btn">
                                <h4 class="mb-0">My Team</h4>
                                <button v-on:click="saveAll()" type="button" class="btn btn-secondary btn-sm">
                                    Save
                                </button>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Company</th>
                                            <th scope="col">Captain</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="users && users.length">
                                        <tr v-for="user in users">
                                            <td>{{user.name}}</td>
                                            <td>{{user.email}}</td>
                                            <td>{{user.company.name}}</td>
                                            <td>
                                                <div class="form-check is-captain">
                                                    <input v-model="user.is_captain" v-on:click="saveUser(user.id, user.is_captain)" class="form-check-input position-static" type="checkbox" :id="user.name" aria-label="...">
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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
  },
  props: {
    data: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      users: this.data.users,
      modifyUsers: {}
    };
  },
  methods: {
        saveUser(id, isCaptain) {
            // if was captain, toggle
            let usr = {
                id: id,
                is_captain: isCaptain ? false : true
            };
            // store the key of the array as the user id
            this.modifyUsers[id] = usr;
        },
        saveAll(){
            axios
            .put('/admin/update-captains', this.modifyUsers)
            .then(response => {
                console.log(response);
                alert(response.data.message);
            })
            .catch(error => {
                alert(JSON.stringify(error));
            });
        }
  },
  computed: {}
};
</script>
