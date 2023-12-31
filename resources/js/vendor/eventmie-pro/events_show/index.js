/**
 * This is a page specific seperate vue instance initializer
 */

// include vue common libraries, plugins and components
require("../../../vue_common");

/**
 * Local Third-party Lib Imports
 */
/* Instances */
import Vuex from "vuex";
window.Vuex = Vuex;
Vue.use(Vuex);

/* Components */
Vue.component("v-select", require("vue-select").default);

import * as VueGoogleMaps from "vue2-google-maps";
Vue.use(VueGoogleMaps, {
    load: {
        key: google_map_key,
        libraries: "places" // necessary for places input
    }
});

/**
 * Local Components
 */
Vue.component("select-dates", require("./components/SelectDates.vue").default);
Vue.component(
    "gallery-images",
    require("../../../../../eventmie-pro/resources/js/events_show/components/GalleryImages.vue")
        .default
);

Vue.component(
    "g-component",
    require("../../../../../eventmie-pro/resources/js/events_show/components/GMap.vue")
        .default
);

/**
 * Local Vuex Store
 */
const store = new Vuex.Store({
    state: {
        tickets: [],
        booking_date: null,
        booking_end_date: null,
        start_time: null,
        end_time: null,
        booked_date_server: null,
        disableSeats : [],
        seat : {}
    },
    mutations: {
        add(
            state,
            {
                tickets,
                booking_date,
                start_time,
                end_time,
                booking_end_date,
                booked_date_server,
                disableSeats,
                seat
            }       
        ) {
            if (typeof booking_date !== "undefined") {
                state.booking_date = booking_date;
            }

            if (typeof booking_end_date !== "undefined") {
                state.booking_end_date = booking_end_date;
            }

            if (typeof start_time !== "undefined") {
                state.start_time = start_time;
            }

            if (typeof end_time !== "undefined") {
                state.end_time = end_time;
            }

            if (typeof tickets !== "undefined") {
                state.tickets = tickets;
            }

            if (typeof booked_date_server !== "undefined") {
                state.booked_date_server = booked_date_server;
            }

        },
        update(state, { tickets, seat}) {
            if (typeof tickets !== "undefined") {
                // in case of multiple items
                if (tickets.length > 1) state.tickets.push(...tickets);
                else state.tickets.push(tickets);
            }

            if (typeof seat !== "undefined") {
                
                if(state.disableSeats.length > 0) {
                    
                    let seat_exist =  state.disableSeats.findIndex((value) => value.id == seat.id );

                    console.log(seat_exist);
                    if(seat_exist != -1) {

                        if(seat.is_checked <= 0) {
                            state.disableSeats.splice(seat_exist, 1)
                      
                        } else {
                            state.disableSeats[seat_exist] = seat;
                        }

                    } else {
                        state.disableSeats.push(seat);
                    }
                }else {
                    state.disableSeats[0] = seat;
                }
                console.log(state.disableSeats, 'dis');
            }
        }
    }
});

/**
 * This is where we finally create a page specific
 * vue instance with required configs
 * element=app will remain common for all vue instances
 *
 */
window.app = new Vue({
    el: "#eventmie_app",
    store: store
});
