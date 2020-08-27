<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DomainControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        $response = $this->get(route('domains.index'));
        $response->assertOk();
    }

    public function testStore()
    {
        $url = Factory::create()->url;

        $response = $this->post(route('domains.store'), ['name' => $url]);
        $response->assertSessionHasNoErrors();

        $name = parse_url($url);
        $parsedName = $name['scheme'] . '://' . $name['host'];
        $this->assertDatabaseHas('domains', ['name' => $parsedName]);
    }

    public function testShow()
    {
        $url = Factory::create()->url;
        $name = parse_url($url);
        $parsedName = $name['scheme'] . '://' . $name['host'];

        $id = DB::table('domains')->insertGetId([
            'name' => $parsedName,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
            ]);
        $response = $this->get(route("domains.show", ['id' => $id]));
        $response->assertOk();
    }

    public function testWelcome()
    {
        $response = $this->get(route('welcome'));
        $response->assertOk();
    }

    public function testCheck()
    {
        $url = Factory::create()->url;
        $name = parse_url($url);
        $parsedName = $name['scheme'] . '://' . $name['host'];

        Http::fake(
            [
                $parsedName => Http::response([], 200, []),
            ]
        );

        $id = DB::table('domains')->insertGetId([
            'name' => $parsedName,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        $response = $this->post(route('domains.check', ['id' => $id]));
        $response->assertRedirect(route('domains.show', ['id' => $id]));
        $this->assertDatabaseHas('domain_checks', [
                'domain_id' => $id,
                'status_code' => 200,
            ]);
    }
}
