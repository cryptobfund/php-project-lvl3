<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use DiDom\Document;
use Illuminate\Support\Facades\Validator;

class DomainControllerCheck extends Controller
{

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
