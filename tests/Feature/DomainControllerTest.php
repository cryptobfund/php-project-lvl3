<?php

namespace Tests\Feature;

use Faker\Factory;
use Tests\TestCase;

class DomainControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->get(route('domains.index'));
        $response->assertOk();
    }

    //public function testStore()
    //{
    //    $url = Factory::create()->url;
    //    $data = ['name' => $url];
    //    $response = $this->post(route('domains.store'), $data);
    //    $response->assertSessionHasNoErrors();
    //    $response->assertRedirect();
    //    $this->assertDatabaseHas('domains', $data);
    //}
}
