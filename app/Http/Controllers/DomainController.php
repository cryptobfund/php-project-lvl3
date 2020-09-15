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
        $domains = DB::table('domains')
            ->leftJoin('domain_checks', 'domains.id', '=', 'domain_checks.domain_id')
            ->orderBy('domains.id')
            ->orderByDesc('domain_checks.created_at')
            ->distinct('domains.id')
            ->select('domains.id', 'domains.name', 'domain_checks.created_at', 'domain_checks.status_code')
            ->get();
        return view('domain.index', compact('domains'));
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
            return redirect()->route('domains.show', ['id' => $domain->id]);
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

    public function check($id)
    {
        $domain = DB::table('domains')->find($id);
        try {
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
            flash("Website has been checked!")->success();
        } catch (Exception $e) {
            flash($e->getMessage())->error();
        }
        return redirect()->route('domains.show', ['id' => $domain->id]);
    }
}
