<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="container py-3">
                <div class="row">
                    <div class="mx-auto col-sm-8">
                        <div class="card">
                            <div class="card-header card-head-btn">
                                <h4 class="mb-0">My Team</h4>
                                <button v-if="user.is_captain" v-on:click="downloadTeam()" type="button" class="btn btn-secondary btn-sm">
                                    Download
                                </button>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="user in users">
                                            <td>{{user.name}}</td>
                                            <td>{{user.email}}</td>
                                        </tr>
                                        <!-- <tr>
                                            <th scope="row">2</th>
                                            <td>Jacob</td>
                                            <td>Thornton</td>
                                        </tr> -->
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
      user: this.data.user
    };
  },
  methods: {
        downloadTeam() {
            window.location.href = "/profile/export-team";
        }
  },
  computed: {}
};
</script>
