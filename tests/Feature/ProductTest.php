<?php

namespace Tests\Feature;

use App\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
   use RefreshDatabase;
    // CREATE-1 
    public function test_client_can_create_a_product()
    {
        // Given
        $productData = [
            'name' => 'Product',
            'price' => '20.00'
        ];
        // When
        $response = $this->json('POST', '/api/products', $productData); 
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(201);
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'id',
            'name',
            'price'
        ]);
        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'name' => 'Product',
            'price' => '20.00'
        ]);
        $body = $response->decodeResponseJson();
        // Assert product is on the database
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $body['id'],
                'name' => 'Product',
                'price' => '20.00'
            ]
        );
    }
    //CREATE-2 
    public function the_name_attribute_is_not_sent_in_the_request()
    {
        // Given
        $productData = [
            'price' => '20.00'
        ];
        // When
        $response = $this->json('POST', '/api/products', $productData); 
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            "errors" => [
                "code",
                "title"]
        ]);
    }
    // CREATE-3    
    public function the_price_attribute_is_not_sent_in_the_request()
    {
        // Given
        $productData = [
            'name' => 'Producto'
        ];
        // When
        $response = $this->json('POST', '/api/products', $productData); 
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            "errors" => [
                "code",
                "title"]
        ]);
    }
    /**     * CREATE-4     */
    /** @test */
    public function the_price_attribute_is_not_a_number_create()
    {
        // Given
        $productData = [
            'name' => 'Producto',
            'price' => 'word'
        ];
        // When
        $response = $this->json('POST', '/api/products', $productData); 
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            "errors" => [
                "code",
                "title"]
        ]);
    }
    // CREATE-5   
    public function the_price_attribute_is_less_than_or_equal_to_zero_create()
    {
        // Given
        $productData = [
            'name' => 'Producto',
            'price' => '-20.00'
        ];
        // When
        $response = $this->json('POST', '/api/products', $productData); 
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            "errors" => [
                "code",
                "title"]
        ]);
    }
    //SHOW-1  
    public function test_client_can_show_a_product()
    {
        //Given
        $producto = factory(Product::class)->create();
        $nombre = $producto->name;
        $precio = $producto->price;
        $id = $producto->id;
        $response = $this->json('GET', '/api/products/'. $id .'');
        $response->assertStatus(200);
        $valor = $response->decodeResponseJson();
        $this->assertJsonStringEqualsJsonString(
            json_encode($producto),
            json_encode($valor)
        );
    }
    // SHOW-2  
    public function the_ID_does_not_exist_show_a_product()
    {
        //Given
        $producto = factory(Product::class)->create();
        $nombre = $producto->name;
        $precio = $producto->price;
        $id = $producto->id;
        $response = $this->json('GET', '/api/products/-20');
        $response->assertStatus(404);
        $response->assertJsonStructure([
            "errors" => [
                "code",
                "title"]
        ]);
    }
    //  DELETE-1    
    public function test_client_can_delete_products()
    {
        //Given
        $producto = factory(Product::class)->create();
        $nombre = $producto->name;
        $precio = $producto->price;
        $id = $producto->id;
        $response = $this->call('DELETE', '/api/products/'.$id);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertDatabaseMissing('products', [
            'id' => $id,
            'name' => $nombre,
            'price' => $precio
        ]);
    }
    // DELETE-2     
    public function the_ID_does_not_exist_delete_products()
    {
        //Given
        $producto = factory(Product::class)->create();
        $nombre = $producto->name;
        $precio = $producto->price;
        $id = $producto->id;
        $response = $this->call('DELETE', '/api/products/-20');
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertDatabaseHas('products', [
            'id' => $id,
            'name' => $nombre,
            'price' => $precio
        ]);
    }
    // UPDATE-1   
    public function test_client_can_update_a_product()
    {
        //Given
        $producto = factory(Product::class)->create();
        $nombre = $producto->name;
        $precio = $producto->price;
        $id = $producto->id;
        $productData = [
            'name' => 'Producto 2',
            'price' => '20.00'
        ];
        $response = $this->json('PUT', '/api/products/'. $id .'', $productData);
        $this->assertEquals(200, $response->getStatusCode());
        $valor = $this->json('GET', '/api/products/'. $id .'');
        $this->assertJsonStringNotEqualsJsonString(
            json_encode($producto),
            json_encode($valor)
        );
        //print_r(json_encode($primerValor). ' =/= '. json_encode($segundoValor));
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $id,
                'name' => 'Producto 2',
                'price' => '20.00'
            ]
        );
    }
    //UPDATE-2   
    public function the_price_attribute_is_not_a_number_update()
    {
        //Given
        $producto = factory(Product::class)->create();
        $nombre = $producto->name;
        $precio = $producto->price;
        $id = $producto->id;
        $productData = [
            'name' => 'Producto 2',
            'price' => 'word'
        ];
        $response = $this->json('PUT', '/api/products/'. $id .'', $productData);
        $this->assertEquals(422, $response->getStatusCode());
        $response->assertJsonStructure([
            "errors" => [
                "code",
                "title"]
        ]);
        $valor = $this->json('GET', '/api/products/'. $id .'');
        $this->assertJsonStringEqualsJsonString(
            json_encode($producto),
            json_encode($valor->decodeResponseJson())
        );
        //print_r(json_encode($valor->decodeResponseJson()));
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $id,
                'name' => $nombre,
                'price' => $precio
            ]
        );
    }
    // UPDATE-3  
    public function the_price_attribute_is_less_than_or_equal_to_zero_update()
    {
        //Given
        $producto = factory(Product::class)->create();
        $nombre = $producto->name;
        $precio = $producto->price;
        $id = $producto->id;
        $productData = [
            'name' => 'Producto 2',
            'price' => '-20.00'
        ];
        $response = $this->json('PUT', '/api/products/'. $id .'', $productData);
        $this->assertEquals(422, $response->getStatusCode());
        $response->assertJsonStructure([
            "errors" => [
                "code",
                "title"]
        ]);
        $valor = $this->json('GET', '/api/products/'. $id .'');
        $this->assertJsonStringEqualsJsonString(
            json_encode($producto),
            json_encode($valor->decodeResponseJson())
        );
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $id,
                'name' => $nombre,
                'price' => $precio
            ]
        );
    }
    // UPDATE-4  
    public function the_ID_does_not_exist_update()
    {
        //Given
        $producto = factory(Product::class)->create();
        $nombre = $producto->name;
        $precio = $producto->price;
        $id = $producto->id;
        $productData = [
            'name' => 'Producto 2',
            'price' => '-20.00'
        ];
        $response = $this->json('PUT', '/api/products/-20.00', $productData);
        $this->assertEquals(422, $response->getStatusCode());
        $response->assertJsonStructure([
            "errors" => [
                "code",
                "title"]
        ]);
    }
    // LIST-1   
    public function test_client_can_show_all_products()
    {
        //Given
        $producto1 = factory(Product::class)->create();
        $nombre = $producto1->name;
        $precio = $producto1->price;
        $id = $producto1->id;
        //and
        //Given
        $producto2 = factory(Product::class)->create();
        $nombre = $producto2->name;
        $precio = $producto2->price;
        $id = $producto2->id;
        $response = $this->json('GET', '/api/products/');
        $this->assertEquals(200, $response->getStatusCode());
    }
    // LIST-2    
    public function test_client_can_show_all_products_when_is_empty(){
        $productData = '{"baseResponse":{"headers":{},"original":[],"exception":null}}';
        $response = $this->json('GET', '/api/products/');
        $valor = json_encode($response);
        $this->assertEquals($productData, $valor);
        $this->assertEquals(200, $response->getStatusCode());
    }
}