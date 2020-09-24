<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DomainCheckControllerTest extends TestCase
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

    public function testStore()
    {
        $dataForTest = file_get_contents(__DIR__ . '/../fixtures/fake.html');
        Http::fake([$this->parsedName => Http::response($dataForTest)]);

        $response = $this->post(route('domains.checks.store', $this->id));
        $response->assertSessionHasNoErrors();

        $response->assertRedirect(route('domains.show', $this->id));
        $this->assertDatabaseHas('domain_checks', [
            'domain_id' => $this->id,
            'status_code' => 200,
            'h1' => 'some h1 text there',
            'keywords' => 'some keywords there',
            'description' => 'some description there'
        ]);
    }
}
