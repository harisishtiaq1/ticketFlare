<template>
    <div class="tab-pane active">
        <div class="panel-group">
            <div class="panel panel-default lgx-panel">
                <div class="panel-heading">

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <button type="button" class="btn btn-primary" @click="openModal_1 = true" v-if="edit_index == null"><i class="fas fa-ticket-alt"></i> {{ trans('em.create_ticket') }}</button>
                            <ticket-component v-if="openModal_1" :taxes ="taxes" @changeItem="updateItem" :currency="currency" :openModal_1="openModal_1" ></ticket-component>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table text-wrap">
                                <thead class="table-light text-nowrap">
                                    <tr>
                                        <th>{{ trans('em.title') }}</th>
                                        <th>{{ trans('em.price') }}</th>
                                        <th>{{ trans('em.quantity') }}</th>
                                        <th>{{ trans('em.order') }}</th>
                                        <th>{{ trans('em.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(ticket, index) in tickets"
                                        v-bind:key="index" 
                                    >
                                        <td :data-title="trans('em.title')">{{ ticket.title }}</td>
                                        <td :data-title="trans('em.price')">{{ ticket.price+' '+ currency }}</td>
                                        <td :data-title="trans('em.quantity')">{{ ticket.quantity }}</td>
                                        <td :data-title="trans('em.order')">{{ ticket.order }}</td>
                                        
                                        <td :data-title="trans('em.actions')" class="text-nowrap">
                                            <div class="btn-group">
                                                 <!-- CUSTOM -->
                                                <button  type="button" class="btn btn-sm btn-primary" @click="seat_ticket_id = ticket.id">{{ trans('em.seating_chart') }} <span v-if="ticket.seatchart != null" class="badge bg-dark">{{ticket.seatchart.seats.length}}</span></button>
                                                <!-- CUSTOM -->
                                                <button type="button" class="btn btn-sm btn-info" @click="()=>{edit_index = index; openModal_2 = true}" ><i class="fas fa-edit"></i> {{ trans('em.edit') }}</button>
                                                <button type="button" class="btn btn-sm btn-danger" @click="deleteTicket(ticket.id)"><i class="fas fa-trash"></i> {{ trans('em.delete') }}</button>
                                            </div>

                                            <ticket-component 
                                                :taxes ="taxes"
                                                :edit_ticket="ticket" 
                                                v-if="edit_index == index" 
                                                @changeItem="updateItem"
                                                :currency="currency"
                                                :openModal_2="openModal_2"
                                            ></ticket-component>

                                            <!-- CUSTOM -->
                                            <file-upload v-if="ticket.id == seat_ticket_id" :ticket="ticket"></file-upload>
                                            <!-- CUSTOM -->
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import { mapState, mapMutations} from 'vuex';

import TicketComponent from './Ticket.vue';
import mixinsFilters from '../../../../../../eventmie-pro/resources/js/mixins.js';

//CUSTOM
import FileUpload from './custom/FileUpload';
//CUSTOM

export default {

    data() {
        return {
            edit_index : null,
            taxes      : null,
            currency   : null,
            openModal_1  : false,
            openModal_2  : false,
            
            // CUSTOM
            seat_ticket_id : 0,
            // CUSTOM
        }
    },

    mixins:[
        mixinsFilters
    ],
    components: {
        TicketComponent,
        
        //CUSTOM
        FileUpload,
        //CUSTOM
    },
    computed: {
        // get global variables
        ...mapState( ['tickets', 'event_id', 'organiser_id']),
    },
    methods: {
        // update global variables
        ...mapMutations(['add', 'update']),

        // delete tickets
        deleteTicket(ticket_id){
            this.showConfirm(trans('em.ticket_delete_ask')).then((res) => {
                if(res) {
                    axios.post(route('eventmie.tickets_delete'), {
                        ticket_id       : ticket_id,
                        event_id        : this.event_id,
                        organiser_id    : this.organiser_id
                    })
                    .then(res => {
                    
                        if(res.data.status)
                        {
                            this.showNotification('success', trans('em.ticket_delete_success'));
                            this.getTickets();
                        }
                    })
                    .catch(error => {
                        Vue.helpers.axiosErrors(error);
                    });
                }
            })

           
        },

        // get all tickets       
        getTickets() {
            axios.post(route('eventmie.tickets'),{
                'event_id'      : this.event_id,
                'organiser_id'  : this.organiser_id,
            })
            .then(res => {
                // fill data to global sponsors array
                if(res.data.status)
                {
                    this.add({  
                        tickets        : res.data.tickets,
                    });

                }
                else
                {
                    // if status false and have not tickets 
                    this.add({  
                        tickets        : [],
                    });
                }
                
                if(res.data.currency)
                {
                    this.currency  = res.data.currency;
                }
                
            })
            .catch(error => {
                Vue.helpers.axiosErrors(error);
            });
        },  

        getTaxes(){
            axios.get(route('eventmie.tickets_taxes'),{
                'event_id'      : this.event_id,
                'organiser_id'  : this.organiser_id,
            })
            .then(res => {
                if(res.data.status)
                {
                    this.taxes = res.data.taxes;
                }
            
            })
            .catch(error => {
                Vue.helpers.axiosErrors(error);
            });    
        },

        updateItem() {
            this.getTickets();
            // in edit case reload page 
            this.edit_index = null;
            
            
        },

        isDirtyReset() {
            this.add({is_dirty: false});
        },
    },
    
    mounted() {
        this.isDirtyReset();
        // if user have no event_id then redirect to details page
        let event_step  = this.eventStep();
        
        if(event_step)
        {
            this.getTaxes();

            if(this.event_id)
                this.getTickets();
        }    
    }
}
</script>