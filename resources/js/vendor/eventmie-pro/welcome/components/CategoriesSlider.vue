<template>
    <div>
        <carousel
        :autoplay="true"
        :autoplayTimeout="1000"
        :perPage="local_item_count"
        :paginationEnabled="true"
        :rtl="dir"
        :class="'categories-paninate d-flex px-lg-5 px-3 p-0 m-0'"
    >
        <slide
            v-for="(item, index) in infiniteCategories"
            :key="index"
            :class="'category-card d-flex flex-column align-items-center justify-content-center'"
        >
            <a class="stretched-link" :href="eventSlug(item.name)">
            <div class="rounded-circle avatar avatar-lg bg-white border border-1  d-flex align-items-center justify-content-center">
                <img :src="'/storage/' + item.thumb" :alt="item.title" class="avatar avatar-sm">
            </div>
            <h6 class="text-center m-0 text-box">{{ item.name }}</h6>
           </a>
        </slide>
    </carousel>
    </div>
</template>
<style>
.py-6 {
  padding-top:0px!important;
  padding-bottom: 0px!important;
}
.categories-paninate .VueCarousel-wrapper{
  padding-top:1rem!important;
}
.categories-paninate .VueCarousel-wrapper .VueCarousel-inner{
  align-items: baseline;
}
.Cookie--gdpr{
    display:none!important;
}
.VueCarousel-dot{
    background-color: rgb(167 166 166)!important;
}
.VueCarousel-dot--active{
    background-color: rgb(0, 0, 0)!important;
}


.top-35 {
    top: 25%;
}
.avatar-lg{
margin-left:25px!important;

}
.text-center{
    color:#212529!important;
}
.VueCarousel-pagination{
    width:97%!important;
}
.VueCarousel-pagination{
    bottom: -25%!important;
}
.VueCarousel-pagination .categories-paninate{
    right: 50px!important;
}
.text-box {
  border: 2px; /* You can customize the border style, color, and width */
  padding: 10px; /* Optional: Add some padding inside the box */
  display: inline-block; /* This prevents the box from taking up the entire width of the page */
  font-size: small!important;
}
</style>
<script>
import { Carousel, Slide } from "vue-carousel";
Vue.prototype.base_url = window.base_url;

export default {
    components: {
        Carousel,
        Slide,
    },
    props: [
        "categories",
        "item_count",
    ],
    data() {
        return {
            categories: [],
            dir: false,
            local_item_count: this.item_count,
            currentIndex: 0,
        };
    },
    computed: {
        infiniteCategories() {
            const length = this.categories.length;
            const result = [];
            for (let i = 0; i < this.local_item_count * 2; i++) {
                const index = (this.currentIndex + i) % length;
                result.push(this.categories[index]);
            }
            return result;
        },
    },
    methods: {
        eventSlug(slug) {
            return route('eventmie.events_index', { 'category': encodeURIComponent(slug) });
        },
        getDirection() {
            document.documentElement.dir == "rtl"
                ? (this.dir = true)
                : (this.dir = false);
        },
        mobileView() {
            var androidMobile = window.matchMedia("(max-width: 768px)");
            if (androidMobile.matches) this.local_item_count = 2;
        },
        next() {
            this.currentIndex = (this.currentIndex + 1) % this.categories.length;
        },
        prev() {
            this.currentIndex =
                (this.currentIndex - 1 + this.categories.length) % this.categories.length;
        },
    },
    mounted() {
        this.getDirection();
        this.mobileView();
        console.log(document.documentElement.dir);
    },
};
</script>
