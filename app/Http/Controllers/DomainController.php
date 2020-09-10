<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use DiDom\Document;

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
        $domains = DB::table('domains')
            ->leftJoin('domain_checks', 'domains.id', '=', 'domain_checks.domain_id')
            ->orderBy('domains.id')
            ->orderByDesc('domain_checks.created_at')
            ->distinct('domains.id')
            ->select('domains.id', 'domains.name', 'domain_checks.created_at', 'domain_checks.status_code')
            ->get();
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
            $time = Carbon::now();
            $id = DB::table('domains')->insertGetId([
                'name' => $parsedName,
                'created_at' => $time,
                'updated_at' => $time
            ]);
            $status = 'Url has been added';
            $domain = DB::table('domains')->find($id);
        }
        $request->session()->flash('status', $status);
        return redirect()->route('domains.show', ['id' => $domain->id]);
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
        $checks = DB::table('domain_checks')->where('domain_id', $id)->orderBy('id', 'desc')->get();
        return view('domain.show', compact('domain', 'checks', 'status'));
    }

    public function check(Request $request, $id)
    {
        $domain = DB::table('domains')->find($id);
        $response = Http::get($domain->name);

        $body = new Document($response->body());
        $h1 = optional($body->first('h1'))->text();
        $keywords = optional($body->first('meta[name=keywords]'))->getAttribute('content');
        $description = optional($body->first('meta[name=description]'))->getAttribute('content');
        $time = Carbon::now();
        DB::table('domain_checks')->insert(
            [
                'domain_id' => $id,
                'status_code' => $response->status(),
                'h1' => $h1 ?? '',
                'keywords' => $keywords ?? '',
                'description' => $description ?? '',
                'created_at' => $time,
                'updated_at' => $time,
            ]
        );
        $status = 'Website has been checked!';
        $request->session()->flash('status', $status);
        return redirect()->route('domains.show', ['id' => $domain->id]);
    }
}
