<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\District;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        if($request->ajax())
        {
            $district = District::select('boundary')->find($request->input('id'));

            return $district->boundary;
        }

        $districts = District::all('id', 'name');

        return view('home.home', [
            'districts' => $districts,
        ]);
    }
}