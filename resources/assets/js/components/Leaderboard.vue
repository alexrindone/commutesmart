<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="container py-3">
                <div class="row">
                    <div class="mx-auto col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Leaderboard</h4>
                            </div>
                            <div class="card-body">
                                <table class="table" v-if="users.length > 0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">Miles</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="user in users">
                                            <td>{{user.name}}</td>
                                            <td>{{user.total_miles}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p v-else>No trips found.</p>
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
      required: false
    }
  },
  data() {
    return {
      users: this.data.users
    };
  },
  methods: {
    
  },
  computed: {
        
    }
};
</script>