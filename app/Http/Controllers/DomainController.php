<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status = $request->session()->get('status');
        $domains = DB::table('domains')->orderBy('id')->get();
        return view('domain.index', compact('domains', 'status'));
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        $name = parse_url($request->input('name'));
        $parsedName = $name['scheme'] . '://' . $name['host'];
        $domain = DB::table('domains')->where('name', $parsedName)->first();
        if ($domain) {
            $status = 'Url already exists';
        } else {
            $id = DB::table('domains')->insertGetId([
                'name' => $parsedName,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            $status = 'Url has been added';
            $domain = DB::table('domains')->find($id);
        }
        $request->session()->flash('status', $status);
        return view('domain.show', compact('domain', 'status'));
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $status = $request->session()->get('status');
        $domain = DB::table('domains')->find($id);
        return view('domain.show', compact('domain', 'status'));
    }
}
