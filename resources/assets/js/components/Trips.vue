<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="container py-3">
                <div class="row">
                    <div class="mx-auto col-sm-12">
                        <!-- form user info -->
                        <div class="card" v-if="activeChallenge">
                            <div class="card-header">
                                <h4 class="mb-0">Add Trip</h4>
                            </div>
                            <div class="card-body">
                                <form class="form" role="form" autocomplete="off">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Challenge</label>
                                        <div class="col-lg-9">
                                            <select id="size" class="form-control" v-model="form.challenge_id">
                                                <option v-for="challenge in challenges" :value="challenge.id">{{challenge.name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Mode of Transportation</label>
                                        <div class="col-lg-9">
                                            <select id="size" class="form-control" v-model="form.mode">
                                                <option v-for="mode in modes" :value="mode">{{mode}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Miles Traveled (Round Trip)</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" type="number" min="0" v-model="form.miles">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Trip Date(s)</label>
                                        <div class="col-lg-6">
                                            <div>
                                                <input :min="activeChallenge.start_date" :max="activeChallenge.end_date" class="form-control" type="date" v-model="form.date">
                                            </div>
                                            <div>
                                                <div v-for="(date, key) in dates" v-if="dates && dates.length > 0" style="display:flex;">
                                                    <input :min="activeChallenge.start_date" :max="activeChallenge.end_date" class="form-control" type="date" v-model="dates[key]">
                                                    <button v-on:click="removeDate(key)" type="button" class="btn btn-secondary" aria-label="Close">&times;</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <input :disabled="addDayDisabled || form.date == activeChallenge.end_date" v-on:click="addDate()" type="button" class="btn btn-primary" value="Add Day">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label"></label>
                                        <div class="col-lg-9">
                                            <input v-on:click="resetForm()" type="reset" class="btn btn-secondary" value="Cancel">
                                            <input :disabled="form.miles == '' || form.mode == ''" v-on:click="addTrip()" type="button" class="btn btn-primary" value="Submit">
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
                                <h4 class="mb-0">My Trips</h4>
                            </div>
                            <div class="card-body">
                                <table class="table" v-if="trips.length > 0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Date</th>
                                            <th scope="col">Mode</th>
                                            <th scope="col">Miles</th>
                                            <th scope="col">Challenge</th>
                                            <th scope="col" class="text-right"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="trip in trips">
                                            <td v-if="activeEditId == trip.id">
                                                <input class="form-control" type="date" v-model="trip.date">
                                            </td>
                                            <td v-else>{{trip.date}}</td>
                                            <td v-if="activeEditId == trip.id">
                                                <select id="size" class="form-control" v-model="trip.mode">
                                                    <option v-for="mode in modes" :value="mode">{{mode}}</option>
                                                </select>
                                            </td>
                                            <td v-else>{{trip.mode}}</td>
                                            <td v-if="activeEditId == trip.id">
                                                <input class="form-control" type="number" v-model="trip.miles">
                                            </td>
                                            <td v-else>{{trip.miles}}</td>
                                            <td v-if="trip.challenge">{{trip.challenge.name}}</td>
                                            <td v-else></td>
                                            <td class="text-right">
                                                <div class="btn-group" role="group" aria-label="Modify">
                                                    <button v-on:click="activeEditId = trip.id" type="button" class="btn btn-secondary">Edit</button>
                                                    <button v-if="activeEditId == trip.id" v-on:click="editTrip(trip)" type="button" class="btn btn-secondary">Save</button>
                                                    <button v-if="activeEditId == trip.id" type="button" class="btn btn-secondary" v-on:click="activeEditId = ''">Cancel</button>
                                                    <button v-if="activeEditId != trip.id" v-on:click="deleteTrip(trip.id)" type="button" class="btn btn-danger">Delete</button>
                                                </div>
                                            </td>
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
      // if only one challenge open, have it default as selected
      if (this.challenges.length > 0) {
          this.form.challenge_id = this.challenges[0].id;
      }
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
        date: moment().format('YYYY-MM-DD'),
        challenge_id: "",
        mode: "",
        miles: ""
      },
      trips: this.data.trips,
      challenges: this.data.challenges,
      modes: this.data.modes,
      activeEditId: "",
      dates: [],
      addDayDisabled: false,
      firstTrips: this.data.trips.length > 0 ? false : true
    };
  },
  methods: {
    resetForm() {
        this.dates = [];
        this.form = {
            date: moment().format('YYYY-MM-DD'),
            mode: "",
            miles: "",
            challenge_id: this.form.challenge_id
        };
    },
    removeDate(key) {
        // Remove task from tasks array
        this.dates.splice(key, 1);
    },
    addDate() {
        this.validateDates();
        if (!this.addDayDisabled) {
            let currentDate = this.dates.length == 0 ? this.form.date : _.last(this.dates);
            let nextDate = moment(currentDate, 'YYYY-MM-DD').add(1, 'days').format('YYYY-MM-DD');
            // turn current date back into moment date to then add 1
            this.dates.push(nextDate);
        }
    },
    addTrip() {
        this.form.miles = Math.abs(this.form.miles);
        this.validateDates();
        // add initial form date to dates
        this.dates.unshift(this.form.date);

        // filter out dates that are past a week
        this.dates = _.filter(this.dates, function(date){
            let nowPlusWeek = moment().add(7, 'days').format('X');
            let currentDate = moment(date).format('X');
            return currentDate <= nowPlusWeek;
        });

        this.form.dates = this.dates;
        this.dates = [];
        // pass in whether or not these are the first set of trips so that the user can be notified
        this.form.first_trips = this.trips.length > 0 ? false : true;
      axios
        .post(`/trips/add`, this.form)
        .then(response => {
            this.trips = response.data.payload;
            // reset stuff
            this.resetForm();
            newTrip.challenge = this.activeChallenge;
        })
        .catch(error => {});
    },
    editTrip(trip) {
    this.activeEditId = '';
      axios
        .put(`/trips/${trip.id}/edit-trip`, trip)
        .then(response => {
            console.log(response.data.payload);
        })
        .catch(error => {});
    },
    deleteTrip(tripId) {
        axios.delete(`/trips/${tripId}/delete-trip`)
                .then(response => {
                    // Remove task from tasks array
                    let index = this.trips.findIndex(x => {
                        return x.id == tripId;
                    });
                    this.trips.splice(index, 1);
                })
                .catch(error => {
                   
                });
    },
    validateDates() {
        // make sure a date wasn't added that was a week out, if it was disabled the addDay button
        let dates = this.dates;
        let nowPlusWeek = moment().add(7, 'days').format('YYYY-MM-DD');
        if (dates.indexOf(nowPlusWeek) != -1) {
            this.addDayDisabled = true;
        }
    }
  },
  computed: {
        activeChallenge() {
            let activeId = this.form.challenge_id;
            return this.challenges.find(challenge => {
                return challenge.id == activeId;
            });
        }
    }
};
</script>