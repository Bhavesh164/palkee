<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Admin\ticket;
use App\Model\Api\complaintModel;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ticketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $page = 1;
        $limit = 10;
        $start = 0;

        if (isset($request->limit)) {
            $limit = $request->limit;
        }
        if (isset($request->page)) {
            $page =  $request->page;
        }

        $start = ($page - 1) * $limit;

        $where_condition[] = 1;

        $startdate = '';
        if (isset($request->startdate) && $request->startdate != '') {

            $startdate  = date('Y-m-d', strtotime($request->startdate));

            $where_condition[]  =   "DATE(c.created_on)>='$startdate'";
        }
        $enddate = '';
        if (isset($request->enddate) && $request->enddate != '') {

            $enddate    = date('Y-m-d', strtotime($request->enddate));

            $where_condition[]  =   "DATE(c.created_on)<='$enddate'";
        }



        if (!empty($where_condition)) {
            $where = implode(' and ', $where_condition);
        }

        $searchcolumn  =  $request->searchcolumn;

        $searchkeyword = $request->searchkeyword;

        if ($searchkeyword != '' &&   $searchcolumn != '') {
            $having_condition = "concat_ws(' ',$searchcolumn) like '%$searchkeyword%'";
        } else {

            if ($searchkeyword != '') {
                $search_having_columns = ('c.subject,c.description,c.created_by,c.driver_name,c.rider_name');

                $having_condition = "concat_ws(' ',$search_having_columns) like '%$searchkeyword%'";
            }
        }
        if (!isset($having_condition)) {
            $having_condition = 1;
        }

        if (isset($request->sort) && isset($request->sortorder)) {
            $sort = $request->sort;
            $sortorder = $request->sortorder;
        } else {
            $sort = 'c.id';
            $sortorder = 'desc';
        }

        $tickets = DB::table('complaint as c')
            ->leftjoin('driver', 'c.driver_id', '=', 'driver.id')
            ->leftjoin('rider', 'c.rider_id', '=', 'rider.id')
            ->select('c.*', 'driver.full_name as driver_name', 'rider.full_name as rider_name')
            ->whereRaw("$where")
            ->havingRaw("$having_condition")
            ->orderBy($sort, $sortorder)
            //->tosql();
            ->paginate($limit);
        $data = [
            'data' => $tickets,
            'start' => $start,
            'page' => $page,
            'limit' => $limit,
            'startdate' => $startdate,
            'enddate' => $enddate,
            'searchcolumn' => $searchcolumn,
            'searchkeyword' => $searchkeyword,
            'sort' => $sort,
            'sortorder' => $sortorder,
        ];
        return view('admin.ticket.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //         $vehicle_types = vehicletype::get_active_vehicle_type();
        //         
        //         $vehicle_makes = vehicle_make::get_active_vehicle_make();
        //         $dial_code = '+1';
        //         $phone = '';
        $dial_code = (old('dial_code')) ? old('dial_code') : '+1';
        $phone = (old('phone')) ? old('phone') : '';


        //$is_activated = 1;
        if (old('_token') !== null) {
            $is_activated = (old('is_activated')) ? 1 : 0;
        } else {

            $is_activated = 1;
        }
        //$is_activated = 1;

        return view('admin.tickets.add', compact('dial_code', 'phone', 'is_activated'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Admin\ticketss  $ticketss
     * @return \Illuminate\Http\Response
     */
    public function show(ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Admin\ticketss  $ticketss
     * @return \Illuminate\Http\Response
     */
    public function edit(complaintModel $ticket)
    {
        //
        $data['complaint'] = DB::table('complaint')->where('id', $ticket->id)->first();
        if ($data['complaint']->rider_id != 0) {
            $data['complaint_by'] = 'Rider';
            $data['detail'] = DB::table('rider')->find($data['complaint']->rider_id);
        } else {
            $data['complaint_by'] = 'Driver';
            $data['detail'] = DB::table('driver')->find($data['complaint']->driver_id);
        }
        $data['comments'] = DB::table('complaint_reply')->where('complaint_id', $ticket->id)->orderByDesc('id')->get();
        return view('admin.ticket.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Admin\ticketss  $ticketss
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, complaintModel $ticket)
    {
        //
        $ticket = complaintModel::find($ticket->id);


        $fieldvalues = [
            "complaint_id" => $ticket->id,
            "reply_by" => '0',
            "message" => $request->message,
            "created_on" => date("Y-m-d H:i:s")
        ];

        $updated = DB::table('complaint_reply')->insert($fieldvalues);

        return redirect('admin/ticket/' . $ticket->id . '/edit')->with('success', 'Comment added successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Admin\ticketss  $ticketss
     * @return \Illuminate\Http\Response
     */
    public function destroy(complaintModel $ticket)
    {
        //
        $ticket_id = DB::table('complaint')->where('id', $ticket->id)->delete();  // can also skip this line //
        DB::table('complaint_detail')->where('complaint_id', $ticket->id)->delete();  // can also skip this line //

        return response()->json(array('success' => 1, 'msg' => 'deleted successfully'), 200);
        // return redirect('admin/ticket/')->with('success', 'deleted successfully');
    }
}
