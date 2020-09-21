<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DomainControllerTest extends TestCase
{
    private $id;
    private $parsedName;

    protected function setUp(): void
    {
        parent::setUp();

        $url = Factory::create()->url;
        $name = parse_url($url);
        $this->parsedName = "{$name['scheme']}://{$name['host']}";
        $this->id = DB::table('domains')->insertGetId([
            'name' => $this->parsedName,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }

    public function testWelcome()
    {
        $response = $this->get(route('welcome'));
        $response->assertOk();
    }
    public function testIndex()
    {
        $response = $this->get(route('domains.index'));
        $response->assertOk();
    }

    public function testShow()
    {
        $response = $this->get(route("domains.show", ['domain' => $this->id]));
        $response->assertOk();
    }

    public function testStore()
    {
        $url = Factory::create()->url;

        $response = $this->post(route('domains.store'), ['name' => $url]);
        $response->assertSessionHasNoErrors();

        $name = parse_url($url);
        $parsedName = "{$name['scheme']}://{$name['host']}";
        $this->assertDatabaseHas('domains', ['name' => $parsedName]);
    }
}
