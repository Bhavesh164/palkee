<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class service_areaController extends Controller
{

    public function __construct()
    {

        //$this->middleware('auth');

        //$this->middleware('admin')->only('login');

        // $this->middleware('admin')->except('login');
    }

    public function country(Request $request)
    {

        $countries = DB::table('country')->orderBy('name', 'asc')->get();

        return view('admin.service_area.country', ['data' => $countries]);
    }

    public function region(Request $request)
    {

        $regions = DB::table('region')
            ->join('country', 'region.countryId', '=', 'country.countryId')
            ->select('region.*', 'country.name as country_name')
            ->orderBy('country.name', 'asc')
            ->get();


        return view('admin.service_area.region', ['data' => $regions]);
    }
    public function city(Request $request)
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

        $searchcolumn  =  $request->searchcolumn;

        $searchkeyword = $request->searchkeyword;

        if ($searchkeyword != '' &&   $searchcolumn != '') {
            $having_condition = "concat_ws(' ',$searchcolumn) like '%$searchkeyword%'";
        } else {

            if ($searchkeyword != '') {
                $search_having_columns = ('country_name,region_name,name');

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
            $sort = 'country_name';
            $sortorder = 'asc';
        }
        $cities = DB::table('cities')
            ->join('region', 'cities.regionId', '=', 'region.regionId')
            ->join('country', 'cities.countryId', '=', 'country.countryId')
            ->select('cities.*', 'country.name as country_name', 'region.name as region_name')
            ->havingRaw("$having_condition")
            ->orderBy($sort, $sortorder)->orderBy('region.name', 'asc')
            ->Paginate($limit);

        return view('admin.service_area.city', ['data' => $cities, 'start' => $start, 'page' => $page, 'limit' => $limit, 'searchcolumn' => $searchcolumn, 'searchkeyword' => $searchkeyword, 'sort' => $sort, 'sortorder' => $sortorder]);
    }
}
