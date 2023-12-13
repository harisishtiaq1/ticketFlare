<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Seat;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;


class SeatsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // language change
        $this->middleware('common');
    
        // authenticate except login_first
        $this->middleware('auth');

    }
    
    /**
     *  save seat
     */
    public function save_seats(Request $request)
    {
        // 1. validate data
        $request->validate([
            'seatchart_id'   => 'required|numeric|gt:0',
            'event_id'       => 'required|numeric|gt:0',
            'ticket_id'      => 'required|numeric|gt:0',
            
            'coordinates'    => 'required|array',
            'coordinates.*'  => 'required', 

            'ids'            => 'required|array',
            
            'seat_names'     => 'required|array',
            'seat_names.*'   => 'required', 


            // 'seat_width'     => 'required|array',
            // 'seat_width.*'   => 'required|numeric', 

            // 'seat_height'     => 'required|array',
            // 'seat_height.*'   => 'required|numeric', 

            // 'seat_border'     => 'required|array',
            // 'seat_border.*'   => 'required|numeric', 

            // 'font_size'     => 'required|array',
            // 'font_size.*'   => 'required|numeric', 
           

        ]);

        
        $params      = [];
        $coordinates = $request->coordinates;
        
        //prepare data
        foreach($coordinates as $key => $value)
        {
            $params['seatchart_id'] = $request->seatchart_id;
            $params['ticket_id']    = $request->ticket_id;
            $params['event_id']     = $request->event_id; 
            $params['coordinates']  = $value; 
            $params['name']         = $request->seat_names[$key]; 
            $params['created_at']   = Carbon::now()->toDateTimeString();
            $params['updated_at']   = Carbon::now()->toDateTimeString();

            
            // $params['width']  = $request->seat_width[$key];
            // $params['height'] = $request->seat_height[$key];
            // $params['border'] = $request->seat_border[$key];
            // $params['font_size'] = $request->font_size[$key];

            
            //save seats
            Seat::updateOrCreate(
                [   
                    'id' => $request->ids[$key]
                ],
                $params
            );
        
        }

        $ticket     = Ticket::with(['seatchart', 
                        'seatchart.seats'  => function ($query) {
                            // $query->where(['status' => 1]);
                        }
                    ])->where(['id' => $request->ticket_id])->first();


        return response()->json(['status' => true, 'ticket' => $ticket]);
        
    }


    /**
     *  delete seat
     */
    public function delete_seat(Request $request)
    {
        // 1. validate data
        $request->validate([
            'seat_id'        => 'required|numeric|gt:0',
            'ticket_id'      => 'required|numeric|gt:0',
        ]);

       $seat = Seat::with(['attendees'])->where(['id'=> $request->seat_id])->first();
        
       if(empty($seat))
            return response()->json(['status' => false, 'error' => __('eventmie-pro::em.seat_not_found')]);
            
        // seat will be not deleted if have bookings
       if($seat->attendees->isNotEmpty())
            return response()->json(['status' => false, 'error' => __('eventmie-pro::em.seat_cannot_delete')]);

        Seat::where(['id'=> $request->seat_id])->delete();

        $ticket     = Ticket::with(['seatchart', 
                            'seatchart.seats'  => function ($query) {
                                // $query->where(['status' => 1]);
                            }
                    ])->where(['id' => $request->ticket_id])->first();


        return response()->json(['status' => true, 'ticket' => $ticket]);
    }

    /**
     *  disable seat
     */
    public function disable_seat(Request $request)
    {
        // 1. validate data
        $request->validate([
            'seat_id'        => 'required|numeric|gt:0',
            'ticket_id'      => 'required|numeric|gt:0',
        ]);

        $params = [
            'status' => 0
        ];

        Seat::where(['id'=> $request->seat_id])->update($params);

        $ticket     = Ticket::with(['seatchart', 
                            'seatchart.seats'  => function ($query) {
                                // $query->where(['status' => 1]);
                            }
                    ])->where(['id' => $request->ticket_id])->first();

        return response()->json(['status' => true, 'ticket' => $ticket]);
    }

    /**
     *  enable seat
     */
    public function enable_seat(Request $request)
    {
        // 1. validate data
        $request->validate([
            'seat_id'        => 'required|numeric|gt:0',
            'ticket_id'      => 'required|numeric|gt:0',
        ]);

        $params = [
            'status' => 1
        ];

        Seat::where(['id'=> $request->seat_id])->update($params);

        $ticket     = Ticket::with(['seatchart', 
                            'seatchart.seats'  => function ($query) {
                                // $query->where(['status' => 1]);
                            }
                    ])->where(['id' => $request->ticket_id])->first();

        return response()->json(['status' => true, 'ticket' => $ticket]);
    }
    
    /**
     *  delete seat
     */
    public function delete_all_seat(Request $request)
    {
        // 1. validate data
        $request->validate([
            'ticket_id'      => 'required|numeric|gt:0',
        ]);

       $s = Seat::where(['ticket_id'=> $request->ticket_id])->delete();

        $ticket     = Ticket::with(['seatchart', 
                            'seatchart.seats'  => function ($query) {
                                // $query->where(['status' => 1]);
                            }
                    ])->where(['id' => $request->ticket_id])->first();


        return response()->json(['status' => true, 'ticket' => $ticket]);
    }
}