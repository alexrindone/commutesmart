<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 mx-auto">
                        <div class="container">
                            <img :src="challenge.image_url" class="img-fluid" :alt="challenge.name">
                        </div>
                    </div>
                </div>
            </div>
            <div class="container py-4">
                <div class="row">
                    <div class="mx-auto col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="container py-3">
                                    <div class="row">
                                        <div class="card col-sm-4">
                                            <div class="card-body">
                                                <h5 class="card-title">Miles Saved</h5>
                                                <p class="card-text">{{totalMiles}}</p>
                                            </div>
                                        </div>
                                        <div class="card col-sm-4">
                                            <div class="card-body">
                                                <h5 class="card-title">CO<sub>2</sub> Saved</h5>
                                                <p class="card-text">{{coSaved}}</p>
                                            </div>
                                        </div>
                                        <div class="card col-sm-4">
                                            <div class="card-body">
                                                <h5 class="card-title">Saved</h5>
                                                <p class="card-text">{{moneySaved | currency}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- <div class="container py-3">
                                    <div class="row">
                                        <div class="card col-sm-4">
                                            <div class="card-body">
                                                <h5 class="card-title">Miles Saved</h5>
                                                <p class="card-text">{{totalMiles}}</p>
                                            </div>
                                        </div>
                                        <div class="card col-sm-4">
                                            <div class="card-body">
                                                <h5 class="card-title">CO<sub>2</sub> Saved</h5>
                                                <p class="card-text">{{coSaved}}</p>
                                            </div>
                                        </div>
                                        <div class="card col-sm-4">
                                            <div class="card-body">
                                                <h5 class="card-title">Saved</h5>
                                                <p class="card-text">{{moneySaved | currency}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                <!-- <div>
                                    <select id="filtered-by" v-model="filtered">
                                        <option v-for="mode in modes" :value="mode">{{mode}}</option>
                                    </select>
                                </div> -->
                                <div class="container py-3">
                                    <div class="row table-responsive-sm">
                                        <table class="table" v-if="users.length > 0">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Miles</th>
                                                    <th scope="col">Trip Total</th>
                                                    <th scope="col">CO2 Saved</th>
                                                    <th scope="col">Money Saved</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="user in sumTrips">
                                                    <td>{{user.name}}<br />{{user.company.name}}</td>
                                                    <td>{{user.total_miles}}mi</td>
                                                    <td>{{user.total_trips}}</td>
                                                    <td>{{user.total_saved}}lbs</td>
                                                    <td>{{user.total_money | currency}}</td>
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
      users: this.data.users,
      modes: this.data.modes,
      challenge: this.data.challenge,
      filtered: false,
      totalMiles: 0,
      coSaved: 0,
      moneySaved: 0,
    };
  },
  methods: {
    
  },
  computed: {
        sumTrips() {
            let users = this.users;
            let filtered = this.filtered;
            let sumMiles = this.totalMiles;
            let coSaved = this.coSaved;
            let moneySaved = this.moneySaved;
            _.forEach(users, function(user) {
                if (filtered) {
                    _.filter(user.trips, function(trip) { 
                        return trip.mode != filtered;
                    });
                }
                user.total_miles = _.sumBy(user.trips, 'miles');
                user.total_trips = user.trips.length;
                user.total_saved = _.round(user.total_miles * 0.9195);
                user.total_money = _.round(user.total_miles * 0.57);
                sumMiles = sumMiles + user.total_miles;
                coSaved = coSaved + user.total_saved;
                moneySaved = moneySaved + user.total_money;
            });
            this.totalMiles = sumMiles; 
            this.coSaved = coSaved;
            this.moneySaved = moneySaved;
            return users;
        }
    }
};
</script>