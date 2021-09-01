<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;

class ProductTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    
    public function testSuccessfulCreate()
    {
        $productData = [
            "key" => "123",
            "value" => json_encode("This is feature Test")
        ];

        $response = $this->json('POST', 'api/item', $productData, ['Accept' => 'application/json']);
        if ($response->getStatusCode() == 201) {
            $response->assertStatus(201)
                ->assertJsonStructure([
                    "message",
                    "product" => [
                        'key',
                        'value',
                        'created_at',
                        'updated_at'
                    ],
                    "Time"
                ]);
        } else {
            $response->assertStatus(400)
                ->assertJsonStructure([
                    "message"
                ]);
        }
    }

    use RefreshDatabase;
    public function testProductList()
    {
        Product::factory()->create([
            "key" => "222",
            "value" => json_encode("This is list test 1")
        ]);

        Product::factory()->create([
            "key" => "333",
            "value" => json_encode("This is list test 2")
        ]);


        $response = $this->json('GET', 'api/item/get_all_records', ['Accept' => 'application/json']);
        $response->assertStatus(200)
            ->assertJson([
                "message" => "Success",
                "product" => [
                    [
                        "id" => 1,
                        "key" => "222",
                        "value" => "\"This is list test 1\"",
                        "latest" => 1
                    ],
                    [
                        "id" => 2,
                        "key" => "333",
                        "value" => "\"This is list test 2\"",
                        "latest" => 1
                    ]
                ]
            ]);
    }
    
    use RefreshDatabase;
    public function testProductSearch()
    {
        Product::factory()->create([
            "key" => "444",
            "value" => json_encode("This is search product Test")
        ]);

        $key = "444";
        $response = $this->json('GET', 'api/item/' . $key, ['timestamp' => time()]);
        $response->assertStatus(200)
            ->assertJson([
                "message" => "Success",
                "data" => "\"This is search product Test\""
            ]);
    }
}
