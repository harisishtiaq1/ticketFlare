<template>
    <div class="custom_model">
        <div class="modal show" v-if="booking_id > 0">
            <div class="modal-dialog modal-lg w-100">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">{{ trans('em.online_details') }} </h1>
                        <button type="button" class="btn btn-sm bg-danger text-white close" @click="close()"><span aria-hidden="true">&times;</span></button>
                    </div>
                    
                    <div class="modal-body">
                        
                        <div class="form-group">
                            <div v-html="online_location"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    props: ["booking_id","online_location"],

    methods: {
        // reset form and close modal
        close: function () {
            this.$parent.booking_id = 0;
        },
    },
}
</script>