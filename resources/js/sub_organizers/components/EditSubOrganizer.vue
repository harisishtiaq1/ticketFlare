<template>

    <div class="custom_model">
    
        <div class="modal show" v-if="edit_sub_organizer > 0">
            <div class="modal-dialog modal-lg w-100">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5"> {{ trans('em.sub-organizer') }}</h1>
                        <button type="button" class="btn btn-sm bg-danger text-white close" @click="close()"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form ref="form" @submit.prevent="validateForm" method="POST" enctype="multipart/form-data">
                            <div class="modal-body">
                                <input type="hidden" class="form-control" v-model="sub_organizer_id" name="sub_organizer_id" >
                                
                                <div class="mb-3">
                                        <label class="form-label" for="name">{{ trans('em.name') }}<sup>*</sup></label>
                                        <input type="text" class="form-control" v-model="name" name="name" v-validate="'required'">
                                        <span v-show="errors.has('name')" class="help text-danger">{{ errors.first('name') }}</span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="email">{{ trans('em.email') }}<sup>*</sup></label>
                                    <input type="text" class="form-control" v-model="email" name="email" v-validate="'required'">
                                    <span v-show="errors.has('email')" class="help text-danger">{{ errors.first('email') }}</span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-sd-card"></i> {{ trans('em.save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>

<script>

import mixinsFilters from '../../../../eventmie-pro/resources/js/mixins.js';
import Multiselect from 'vue-multiselect';

export default {

    props : [
        'edit_sub_organizer',
        'sub_organizer'
    ],

    mixins:[
        mixinsFilters
    ],

    components: {
        Multiselect,
        
    },

    data() {
        return {
            name              : null,
            email             : null,
            sub_organizer_id  : null,

        }
    },


    methods: {
        
        // reset form and close modal
        close: function () {
            this.$refs.form.reset();
            this.$parent.edit_sub_organizer = 0;
            this.$parent.sub_organizer  = [];
            
            
        },

           // validate data on form submit
        validateForm(event) {
            this.$validator.validateAll().then((result) => {
                if (result) {
                    this.formSubmit(event);            
                }
            });
        },

        // show server validation errors
        serverValidate(serrors) {
            this.$validator.validateAll().then((result) => {
                this.$validator.errors.add(serrors);
            });
        },

        // submit form
        formSubmit(event) {
            // prepare form data for post request
            let post_url    = route('edit_sub_organizer');
            let post_data  = new FormData(this.$refs.form);
            
            // axios post request
            axios.post(post_url, post_data)
            .then(res => {

                if(res.data.status)
                {
                    this.showNotification('success', trans('em.sub-organizer')+' '+trans('em.saved')+' '+trans('em.successfully'));
                    // reload page   
                    setTimeout(function() {
                        location.reload(true);
                    }, 1000);
                }
            })
            .catch(error => {
                let serrors = Vue.helpers.axiosErrors(error);
                if (serrors.length) {
                    this.serverValidate(serrors);
                }
            });
        },

        edit(){
            console.log('hesi,to');
            this.sub_organizer_id   = this.sub_organizer.id;
            this.name               = this.sub_organizer.name;
            this.email              = this.sub_organizer.email;
        }


    },
    
    mounted(){
             
        this.edit();
    }

}
</script>