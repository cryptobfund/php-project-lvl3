<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use DiDom\Document;
use Illuminate\Support\Facades\Validator;

class DomainController extends Controller
{

    public function index()
    {
        $domains = DB::table('domains')->get();
        $lastChecks = DB::table('domain_checks')
            ->select('domain_id', 'created_at', 'status_code')
            ->orderBy('domain_id')
            ->orderByDesc('created_at')
            ->distinct('domain_id')
            ->get()
            ->keyBy('domain_id');
        return view('domain.index', compact('domains', 'lastChecks'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['name' => 'required|url']);
        if ($validator->fails()) {
            flash('Not a valid url')->error();
            return redirect()->route('welcome');
        }
        $name = parse_url($request->input('name'));
        $parsedName = "{$name['scheme']}://{$name['host']}";
        $domain = DB::table('domains')->where('name', $parsedName)->first();
        if ($domain) {
            flash('Url already exists');
            return redirect()->route('domains.show', $domain->id);
        } else {
            $time = Carbon::now();
            $id = DB::table('domains')->insertGetId([
                'name' => $parsedName,
                'created_at' => $time,
                'updated_at' => $time
            ]);
            flash('Url has been added');
            return redirect()->route('domains.show', $id);
        }
    }

    public function show($id)
    {
        $domain = DB::table('domains')->find($id);
        $checks = DB::table('domain_checks')->where('domain_id', $id)->orderBy('id', 'desc')->get();
        return view('domain.show', compact('domain', 'checks'));
    }
}
