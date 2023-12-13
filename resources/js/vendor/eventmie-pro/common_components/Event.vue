<template>

    <div class="px-2 py-2 w-100">
        <!-- listing block -->
        <div>
            <div class="position-relative">
                <a  :href="eventSlug(event.slug)" class="text-inherit">
                    <div class="back-image rounded-3 img-hover" :style="{ 'background-image': 'url(/storage/' + event.thumbnail + ')' }"></div>
                </a>

                <!-- repetitive events who Upcoming  -->
                <div>
                    <span class="badge bg-primary position-absolute top-0 ms-1 mt-2 start-0 w-25">
                        {{ changeDateFormat(userTimezone(event.start_date+' '+event.start_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD'), "YYYY-MM-DD") }}

                    </span>

                </div>
                <!-- online event -->
                <div v-if="event.online_location">
                    <span class="badge bg-primary position-absolute bottom-0 mx-1  mb-2 end-0 w-25"><i class="fas fa-signal"></i> {{ trans('em.online') }}</span>
                </div>

                <!-- CUSTOM -->
                <div v-if="event.sale_tickets.length > 0">
                    <div class="badge bg-gradient position-absolute bottom-0 mx-1  mb-2 start-0" v-if="event.sale_tickets[0].sale_start_date != null">
                        <div class="text-sm d-flex justify-content-between" v-if="
                            userTimezone(event.sale_tickets[0].sale_start_date, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD HH:mm:ss') <= moment().tz(Intl.DateTimeFormat().resolvedOptions().timeZone).format('YYYY-MM-DD HH:mm:ss') &&
                            userTimezone(event.sale_tickets[0].sale_end_date, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD HH:mm:ss') > moment().tz(Intl.DateTimeFormat().resolvedOptions().timeZone).format('YYYY-MM-DD HH:mm:ss')">

                            <div>
                                <span class="font-weight-semi-bold text-white">
                                    {{ trans('em.on_sale') }}
                                </span>
                            </div>

                            <div>
                                <i class='fas fa-clock fa-spin text-danger  ms-2'></i>
                                <span class="font-weight-semi-bold fw-light text-white">
                                    <vue-countdown :time="timerOnSale(event.sale_tickets[0].sale_start_date, event.sale_tickets[0].sale_end_date)" v-slot="{ days, hours, minutes, seconds }">
                                    {{ days }} {{ trans('em.days') }}, {{ hours }} : {{ minutes }} : {{ seconds }} {{ trans('em.left') }}
                                    </vue-countdown>
                                </span>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- CUSTOM -->

            </div>
            <div class="rounded-bottom border-0 mb-lg-0">
                <div class="mt-3">
                    <h5 class="text-left p-0 m-0">
                        <a  :href="eventSlug(event.slug)" class="text-inherit">
                        {{ event.title.substring(0, 30)+
                        `${event.title.length > 30 ? '...' : '' }`
                        }}
                        </a>
                    </h5>
                </div>
                <div class="text-sm">
                    {{ event.category_name }}
                </div>

                <div class="text-sm d-flex justify-content-between mt-2"
                    v-for="(ticket, index1) in event.tickets"
                    :key="index1"
                    v-if="index1 < 1"
                >
                    <div class="font-weight-semi-bold fw-light text-dark">
                        <span>
                            <i class="fas fa-map-marker-alt"></i>&nbsp;{{event.city}}
                        </span>
                    </div>
                    <div>
                        <span class="h6">{{`${ event.currency ? ticket.price+' '+ event.currency :  ticket.price+' '+ currency}` }}</span>
                        <span class="text-sm font-weight-semi-bold ms-1" >
                            / {{ ticket.title.substring(0, 15)+`${ticket.title.length > 15 ? '..' : '' }` }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!-- listing block -->
    </div>


</template>

<script>

import mixinsFilters from '../../../mixins.js';

//  CUSTOM
import VueCountdown from '@chenfengyuan/vue-countdown';
//  CUSTOM

export default {

    //  CUSTOM
    components: {
        VueCountdown,
    },
    //  CUSTOM
    props: ['event', 'currency', 'date_format'],


    mixins:[
        mixinsFilters
    ],

    data() {
        return {
        }
    },

    methods:{

        // check free tickets of events
        checkFreeTickets(event_tickets = []){
            let free = false;
            event_tickets.forEach(function(value, key) {
                if(parseFloat(value.price) <= parseFloat(0))
                {
                    free = true;
                }
            });
            return free;
        },


        // return route with event slug
        eventSlug: function eventSlug(slug) {
            return route('eventmie.events_show', [slug]);
        },

        /* CUSTOM */
        timerOnSale(sale_start_date = null, sale_end_date = null){
            if(sale_start_date == null || sale_end_date == null)
                return true;

            var local_tz = Intl.DateTimeFormat().resolvedOptions().timeZone;

            var a    = this.userTimezone(sale_end_date, 'YYYY-MM-DD HH:mm:ss').format('DD/MM/YYYY HH:mm:ss');
            var b    = moment().tz(local_tz).format('DD/MM/YYYY HH:mm:ss');
            var ms   = 0; // milliseconds

            if(moment(a,"DD/MM/YYYY HH:mm:ss") > moment(b,"DD/MM/YYYY HH:mm:ss")){
                ms = moment(a,"DD/MM/YYYY HH:mm:ss").tz(local_tz).diff(moment(b,"DD/MM/YYYY HH:mm:ss").tz(local_tz)); //milliseconds

            }

            return ms;
        },
        /* CUSTOM */


    },

    mounted(){
    }

}
</script>