<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Admin\home;
use Illuminate\Http\Request;
use App\Model\Admin\commonModel;

class homeController extends Controller
{
    public function index()
    {
        $data['vehicles'] = commonModel::total_vehicles();
        $data['drivers'] = commonModel::total_drivers();
        $data['riders'] = commonModel::total_riders();
        $data['rides'] = commonModel::total_rides();
        $data['rides_by_vehicle_types'] = commonModel::rides_count_by_vehicle_type();
        $sale = commonModel::sale();
        $data['total_sale'] = number_format($sale->total_sale, 2);
        $data['total_revenue'] = number_format($sale->total_sale - $sale->admin_earning, 2);
        return view('admin.dashboard', $data);
    }
}
