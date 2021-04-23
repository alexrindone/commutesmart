<template>
    <div class="container">
        <div class="row justify-content-center">
                <div class="row">
                    <div class="col-sm-12 mx-auto">
                        <div class="container">
                            <img v-if="challenge.image_url" :src="challenge.image_url" class="img-fluid" :alt="challenge.name">
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
                                            <h4 class="head">{{totals.miles}}</h4>
                                            <span style="font-size: 12px;">Miles Saved</span>
                                        </div>
                                        <div class="col-4 total-tile">
                                            <h4 class="head">{{totals.co}} lbs</h4>
                                            <span style="font-size: 12px;">CO<sub>2</sub> Saved</span>
                                        </div>
                                        <div class="col-4 total-tile">
                                            <h4 class="head">{{totals.money | currency}}</h4>
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
                                        <div class="col-sm-3">
                                            <select class="form-control" id="filtered" v-model="filterBySize">
                                                <option selected value="unfiltered">Filter by Size</option>
                                                <option v-for="size in sizes" :value="size">{{size}}</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="pl-3"> 
                                                <input v-on:click="toggleSeacoast()" v-model="seacoastOnly" id="seacoast" class="form-check-input position-static" type="checkbox" />
                                                <label for="seacoast" class="form-check-label">Filter by Seacoast</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row table-responsive-sm">
                                        <table class="table leaderboard" v-if="sumTrips.length > 0">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Company</th>
                                                    <th scope="col">Trip Total</th>
                                                    <th scope="col">Miles</th>
                                                    <th scope="col">Per Capita</th>
                                                    <th scope="col">CO2 Saved</th>
                                                    <th scope="col">Money Saved</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="company in sumTrips">
                                                    <td>
                                                        <a class="company-link" :href="'/leaderboard/company/' + company.id + '/' + challenge.slug">{{company.name}}</a>
                                                        <br /><span style="font-size:12px;">{{company.size}}</span>
                                                    </td>
                                                    <td>{{company.totals.trips}}</td>
                                                    <td>{{company.totals.miles}}mi</td>
                                                    <td>{{company.totals.capita}}</td>
                                                    <td>{{company.totals.co}}lbs</td>
                                                    <td>{{company.totals.money | currency}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <p v-if="sumTrips.length <= 0">No trips found. <span v-if="challengeOpen">This challenge opens up {{challengeStartDate}}.</span></p>
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
      companies: this.data.companies,
      seacoastOnly: false,
      modes: this.data.modes,
      sizes: this.data.sizes,
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
      loading: false,
      filterBySize: 'unfiltered'
    };
  },
  methods: {
        calculateTotals(companies) {
            this.totals = {
                miles: _.sumBy(companies, 'totals.miles'),
                co: _.sumBy(companies, 'totals.co'),
                money: _.sumBy(companies, 'totals.money')
            }
        },
        toggleSeacoast(){
            this.seacoastOnly = !this.seacoastOnly;
            console.log("toggling working");
        }
  },
  computed: {
        challengeStartDate() {
            return moment(this.challenge.start_date).format('LL');;
        },
        challengeOpen() {
            return moment().format('X') <= moment(this.challenge.start_date).format('X');
        },
        sumTrips() {
            this.loading = true;
            let companies = this.companies;
            let filtered = this.filtered;
            this.totals = {
                miles: 0,
                co: 0,
                money: 0
            }
            let searchName = this.searchName;
            if (searchName) {
                companies = _.filter(companies, function(company){
                    return company.name.indexOf(searchName) != -1;
                });
            }
            
            // filter by company size
            let filterBySize = this.filterBySize;
            if (filterBySize && filterBySize != 'unfiltered') {
                companies = _.filter(companies, function(company){
                    return company.size == filterBySize;
                });
            }

            // filter by Seacoast
            let filterBySeacoast = this.seacoastOnly;
            if (filterBySeacoast) {
                companies = _.filter(companies, function(company){
                    console.log(company.region);
                    return company.region == "Seacoast";
                });
            }
            _.forEach(companies, function(company){
                 _.forEach(company.users, function(user) {
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
                company.totals = {
                    miles: _.sumBy(company.users, 'total_miles'),
                    co: _.sumBy(company.users, 'total_saved'),
                    money: _.sumBy(company.users, 'total_money'),
                    trips: _.sumBy(company.users, 'total_trips')
                };
                // per capita calculation
                let capita = _.round((company.totals.trips / company.employee_count), 3);
                company.totals.capita = _.isNaN(capita) ? 0 : capita;
            });

            this.calculateTotals(companies);
               
            this.loading = false;
            // order by per capita
            return _.orderBy(companies, ['totals.capita'], ['desc']);
        }
    }
};
</script>