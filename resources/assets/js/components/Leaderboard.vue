<template>
    <div class="container">
        <div class="row justify-content-center">
                <div class="row">
                    <div class="col-sm-12 mx-auto">
                        <div class="container">
                            <img :src="challenge.image_url" class="img-fluid" :alt="challenge.name">
                        </div>
                    </div>
                </div>
            <div class="container">
                <div class="row">
                    <div class="mx-auto col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-4 total-tile">
                                            <h4 v-if="users" class="head">{{totals.miles}}</h4>
                                            <span style="font-size: 12px;">Drive-alone miles saved</span>
                                        </div>
                                        <div class="col-4 total-tile">
                                            <h4 v-if="users" class="head">{{totals.co}} lbs</h4>
                                            <span style="font-size: 12px;">CO<sub>2</sub> Saved</span>
                                        </div>
                                        <div class="col-4 total-tile">
                                            <h4 v-if="users" class="head">{{totals.money | currency}}</h4>
                                            <span style="font-size: 12px;">Saved</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div v-if="!loading" class="container">
                                    <div class="form-group row justify-content-end">
                                        <div class="col-sm-3">
                                            <input v-model="searchName" type="search" class="form-control" id="search" placeholder="Search by Name">
                                        </div>
                                        <div class="col-sm-3">
                                            <select class="form-control" id="filtered" v-model="filtered">
                                                <option selected value="unfiltered">Filter by Mode</option>
                                                <option v-for="mode in modes" :value="mode">{{mode}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row table-responsive-sm">
                                        <table class="table leaderboard" v-if="users.length > 0">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Trip Total</th>
                                                    <th scope="col">Miles</th>
                                                    <th scope="col">CO2 Saved</th>
                                                    <th scope="col">Money Saved</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="user in sumTrips">
                                                    <td>
                                                        <span v-if="challenge.level_one_icon" style="float:left; max-width: 10%;">
                                                            <img v-if="user.trips_count < 16" :src="challenge.level_one_icon" class="img-fluid" :alt="challenge.level_one_label">
                                                            <img v-if="user.trips_count >= 16 && user.trips_count < 31" :src="challenge.level_two_icon" class="img-fluid" :alt="challenge.level_two_label">
                                                            <img v-if="user.trips_count >= 31 && user.trips_count < 46" :src="challenge.level_three_icon" class="img-fluid" :alt="challenge.level_three_label">
                                                            <img v-if="user.trips_count >= 46" :src="challenge.level_four_icon" class="img-fluid" :alt="challenge.level_four_label">
                                                        </span>
                                                        {{user.name}}<br />
                                                        {{user.company.name}}
                                                    </td>
                                                    <td>{{user.total_trips}}<span v-if="filtered != 'unfiltered'"> of {{user.trips_count}}</span></td>
                                                    <td>{{user.total_miles}}mi</td>
                                                    <td>{{user.total_saved}}lbs</td>
                                                    <td>{{user.total_money | currency}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <p v-if="sumTrips.length <= 0">No trips found.</p>
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
      filtered: 'unfiltered',
      totalMiles: 0,
      coSaved: 0,
      moneySaved: 0,
      searchName: '',
      totals: {
          miles: 0,
          co: 0,
          money: 0
      },
      loading: false
    };
  },
  methods: {
        calculateTotals(users) {
            this.totals = {
                miles: _.sumBy(users, 'total_miles'),
                co: _.sumBy(users, 'total_saved'),
                money: _.sumBy(users, 'total_money')
            }
        }
  },
  computed: {
        sumTrips() {
            this.loading = true;
            let users = this.users;
            let filtered = this.filtered;
            this.totals = {
                miles: 0,
                co: 0,
                money: 0
            }
            let searchName = this.searchName;
            if (searchName) {
                searchName = searchName.toLowerCase();
                users = _.filter(users, function(user){
                    return user.name.toLowerCase().indexOf(searchName) != -1;
                });
            }
            _.forEach(users, function(user) {
                let trips = user.trips;
                // trying to add filter by mode
                if (filtered != 'unfiltered') {
                    user.filtered_trips = _.filter(user.trips, function(trip) {
                        return trip.mode == filtered;
                    });
                } else {
                    user.filtered_trips = user.trips;
                }
                user.trips = trips;
                user.total_miles = _.sumBy(user.filtered_trips, 'miles');
                user.total_trips = user.filtered_trips.length;
                user.total_saved = _.round(user.total_miles * 0.9195);
                user.total_money = _.round(user.total_miles * 0.57);
            });
            // sum up totals after users are looped through each time
            this.calculateTotals(users);
            this.loading = false;
            return _.orderBy(users, ['total_trips'], ['desc']);
        }
    }
};
</script>