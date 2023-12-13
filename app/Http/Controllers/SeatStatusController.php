<?php

namespace App\Http\Controllers;

use App\Models\SeatStatus;
use Illuminate\Http\Request;

class SeatStatusController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'seat' => 'required'
        ]);

        $seat = json_decode($request->seat, true);
        
        // $seatModel = Attendee::where(['seat_id' => $seat['id'], 'ticket_id' => $seat['ticket_id'], 'status' => 1])->first();

        // if(!empty($seatModel))
        // {
        //     if(!empty($seatModel->booking))
        //     {
        //         if($seatModel->booking->event_start_date  == $seat['event_start_date'] && !empty($seatModel->booking->status))
        //         {
        //             $seat['checked'] = 1;
        //         }
        //     }
        // }
        
        SeatStatus::updateOrCreate(
            ['seat_id' => $seat['id'], 'event_start_date' => $seat['event_start_date']],
            ['seats' => $seat, 'is_checked' => $seat['is_checked']]
        );
        
        // SeatStatusEvent::dispatch();
        
        return response()->json(['status' => true]);
    }
}
