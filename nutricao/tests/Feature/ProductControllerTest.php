<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test API details endpoint.
     *
     * @return void
     */
    public function testApiDetails()
    {
        $response = $this->get('/api');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'database_connection',
                     'last_cron_execution',
                     'uptime',
                     'memory_usage',
                 ]);
    }

    /**
     * Test searching for products.
     *
     * @return void
     */
    public function testSearchProducts()
    {
        $response = $this->get('/api/products/search', ['query' => 'Test Product']);
        $response->assertStatus(200)
                 ->assertJson([]);
    }
    
    /**
     * Test listing all products.
     *
     * @return void
     */
    public function testIndexProducts()
    {
        $response = $this->get('/api/products');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'current_page',
                     'data' => [
                         '*' => [
                             'id',
                             'code',
                             'status',
                             'imported_t',
                             'url',
                             'creator',
                             'created_at',
                             'updated_at',
                             'last_modified_t',
                             'product_name',
                             'quantity',
                             'brands',
                             'categories',
                             'labels',
                             'cities',
                             'purchase_places',
                             'stores',
                             'ingredients_text',
                             'traces',
                             'serving_size',
                             'serving_quantity',
                             'nutriscore_score',
                             'nutriscore_grade',
                             'main_category',
                             'image_url',
                         ],
                     ],
                     'first_page_url',
                     'from',
                     'last_page',
                     'last_page_url',
                     'links',
                     'next_page_url',
                     'path',
                     'per_page',
                     'prev_page_url',
                     'to',
                     'total',
                 ]);
    
        $responseData = $response->json();
    }
}
