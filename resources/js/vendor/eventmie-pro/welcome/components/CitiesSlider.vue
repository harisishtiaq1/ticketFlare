<template>
    <carousel
      :autoplay="true"
      :autoplayTimeout="1000"
      :scrollPerPage="true"
      :perPage="local_item_count"
      :paginationEnabled="true"
      :rtl="dir"
      :class="'cities-paginate'"
    >
      <slide
        v-for="(item, index) in cities_events"
        :item="item"
        :index="index"
        :key="index"
        class="d-flex align-items-lg-stretch col-md-4 col-12 mb-4"
      >
        <div
          class="card w-100 border-0 overlay-bg img-hover mb-3 p-3"
          :style="{ 'background-image': 'url(/storage/' + JSON.parse(item.venues[0].images)[0] + ')'}"
          style="background-size: cover;margin-right: 11px;"
        >
          <span class="single-name"></span>
          <div class="z-1">
            <h3 style="position: absolute;bottom: 12px;" class="text-white mb-0 text-wrap text-start">{{ item.venues[0].title }}</h3>
          </div>
          <a :href="eventSlug(item.venues[0].city)" class="stretched-link"></a>
        </div>
      </slide>
    </carousel>
  </template>
<style>
.cities-paginate .VueCarousel-pagination{
  margin-bottom:35px!important;
  width: 84%!important;
}
.cities-paginate .VueCarousel-wrapper .VueCarousel-inner{
  height: 280px !important;
}
</style>

<script>
import { Carousel, Slide } from "vue-carousel";
Vue.prototype.base_url = window.base_url;

export default {
    components: {
        Carousel,
        Slide
    },
    props: [
        "cities_events",
        "item_count",
        // "is_logged",
        // "is_customer",
        // "is_organiser",
        // "is_admin",
        // "is_multi_vendor",
        // "demo_mode",
        // "check_session",
        // "s_host"
    ],

    data() {
        return {
            // check: 0,
            categories: [],
            // cities: [],
            // f_category: "",
            // f_city: "",
            // f_price: "",
            // route: route,
            dir: false,
            local_item_count  : this.item_count,
        };
    },

    methods: {
        // return route with event slug
        getRoute(name) {
            return route(name);
        },

        // return route with event slug
        eventSlug: function eventSlug(slug) {
            return route('eventmie.venues.index', {'search' : encodeURIComponent(slug)});
        },

        // verifyD() {
        //     this.check = this.check_session ? 1 : 0;

        //     if (this.check == 0) {
        //         axios
        //             .post("https://cblicense.classiebit.com/verifyd", {
        //                 domain: window.location.hostname,
        //                 s_host: this.s_host
        //             })
        //             .then(res => {
        //                 if (
        //                     typeof res.data.status !== "undefined" &&
        //                     res.data.status != 0
        //                 )
        //                     this.checkSession();
        //                 else window.location.href = base_url + "/404";
        //             })
        //             .catch(error => {});
        //     }
        // },

        // check Session
        // checkSession() {
        //     axios
        //         .post(route("eventmie.check_session"))
        //         .then(res => {})
        //         .catch(error => {});
        // },

        // get categories
        // getCategories() {
        //     axios
        //         .get(route("eventmie.myevents_categories"))
        //         .then(res => {
        //             if (res.status) this.categories = res.data.categories;
        //         })
        //         .catch(error => {});
        // },
        // // get cities
        // getCities() {
        //     axios
        //         .get(route("eventmie.myevents_cities"))
        //         .then(res => {
        //             if (res.status) this.cities = res.data.cities;
        //         })
        //         .catch(error => {});
        // },

        getDirection() {
            document.documentElement.dir == "rtl"
                ? (this.dir = true)
                : (this.dir = false);
        },
        mobileView(){
            var androidMobile = window.matchMedia("(max-width: 768px)");
            if (androidMobile.matches)
                this.local_item_count = 1;
        }
    },

    mounted() {
        // this.verifyD();
        // this.getCategories();
        // this.getCities();
        this.getDirection();
        this.mobileView();
        console.log(document.documentElement.dir);
    }
};
</script>
