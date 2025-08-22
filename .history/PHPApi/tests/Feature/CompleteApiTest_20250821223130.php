<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompleteApiTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;
    protected $category;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'password' => bcrypt('password123')
        ]);
        
        // Create regular user
        $this->user = User::factory()->create([
            'role' => 'user',
            'password' => bcrypt('password123')
        ]);
        
        // Create category
        $this->category = Category::factory()->create();
        
        // Create product
        $this->product = Product::factory()->create([
            'category_id' => $this->category->id
        ]);
    }

    public function test_public_endpoints_are_accessible()
    {
        // Test categories listing
        $response = $this->getJson('/api/categories');
        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'message', 'data']);

        // Test products listing
        $response = $this->getJson('/api/products');
        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'message', 'data']);

        // Test product details
        $response = $this->getJson("/api/products/{$this->product->id}");
        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'message', 'data']);
    }

    public function test_user_registration()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['token', 'user' => ['id', 'name', 'email', 'role']]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => 'user'
        ]);
    }

    public function test_user_login()
    {
        $response = $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['token', 'user']
            ]);
    }

    public function test_user_profile_operations()
    {
        $token = $this->user->createToken('test')->plainTextToken;

        // Test profile view
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson('/api/profile');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'message', 'data']);

        // Test profile update
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->putJson('/api/profile', [
            'name' => 'Updated Name'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'message', 'data']);
    }

    public function test_cart_operations()
    {
        $token = $this->user->createToken('test')->plainTextToken;

        // Test add to cart
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/cart/add', [
            'product_id' => $this->product->id,
            'quantity' => 2
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['success', 'message', 'data']);

        // Test view cart
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson('/api/cart');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'message', 'data']);
    }

    public function test_order_creation()
    {
        $token = $this->user->createToken('test')->plainTextToken;

        // Add item to cart first
        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/cart/add', [
            'product_id' => $this->product->id,
            'quantity' => 1
        ]);

        // Test order creation
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/orders');

        $response->assertStatus(201)
            ->assertJsonStructure(['success', 'message', 'data']);
    }

    public function test_admin_operations()
    {
        $token = $this->admin->createToken('test')->plainTextToken;

        // Test category creation
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/categories', [
            'name' => 'Electronics',
            'description' => 'Electronic products'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['success', 'message', 'data']);

        // Test product creation
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/products', [
            'name' => 'Smartphone',
            'description' => 'Latest smartphone',
            'price' => 999.99,
            'stock_quantity' => 50,
            'category_id' => $this->category->id
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['success', 'message', 'data']);

        // Test admin dashboard
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson('/api/admin/dashboard');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'message', 'data']);
    }

    public function test_non_admin_cannot_access_admin_endpoints()
    {
        $token = $this->user->createToken('test')->plainTextToken;

        // Test category creation (should fail)
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/categories', [
            'name' => 'Electronics',
            'description' => 'Electronic products'
        ]);

        $response->assertStatus(403);
    }

    public function test_product_filtering_and_search()
    {
        // Test category filtering
        $response = $this->getJson("/api/products?category_id={$this->category->id}");
        $response->assertStatus(200);

        // Test price filtering
        $response = $this->getJson("/api/products?min_price=10&max_price=1000");
        $response->assertStatus(200);

        // Test search
        $response = $this->getJson("/api/products?search={$this->product->name}");
        $response->assertStatus(200);

        // Test pagination
        $response = $this->getJson("/api/products?limit=5");
        $response->assertStatus(200);
    }

    public function test_validation_rules()
    {
        // Test registration validation
        $response = $this->postJson('/api/register', [
            'name' => 'A', // Too short
            'email' => 'invalid-email',
            'password' => '123' // Too short
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['success', 'message', 'errors']);

        // Test product creation validation
        $token = $this->admin->createToken('test')->plainTextToken;
        
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/products', [
            'name' => 'AB', // Too short
            'price' => -10, // Negative price
            'stock_quantity' => -5, // Negative stock
            'category_id' => 999 // Non-existent category
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['success', 'message', 'errors']);
    }

    public function test_rate_limiting()
    {
        // Test auth rate limiting
        for ($i = 0; $i < 11; $i++) {
            $response = $this->postJson('/api/login', [
                'email' => 'test@example.com',
                'password' => 'password123',
            ]);
        }

        // Should be rate limited after 10 attempts
        $response->assertStatus(429);
    }

    public function test_logout()
    {
        $token = $this->user->createToken('test')->plainTextToken;

        // Verify token works before logout
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson('/api/profile');
        
        $response->assertStatus(200);

        // Perform logout
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'message', 'data']);

        // Check if token still exists in database
        $this->assertDatabaseMissing('personal_access_tokens', [
            'token' => hash('sha256', $token)
        ]);

        // Token should be invalid after logout
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson('/api/profile');

        $response->assertStatus(401);
    }

    public function test_logout_debug()
    {
        // Create a token
        $token = $this->user->createToken('test')->plainTextToken;
        
        // Check if token exists in database
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $this->user->id,
            'tokenable_type' => User::class
        ]);
        
        // Debug: Check token count before logout
        $tokenCountBefore = $this->user->tokens()->count();
        echo "Tokens before logout: {$tokenCountBefore}\n";
        
        // Debug: Check the actual token hash in database
        $tokenRecord = $this->user->tokens()->first();
        echo "Token hash in DB: " . $tokenRecord->token . "\n";
        echo "Expected hash: " . hash('sha256', $token) . "\n";
        
        // Perform logout
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/logout');
        
        $response->assertStatus(200);
        
        // Debug: Check token count after logout
        $tokenCountAfter = $this->user->fresh()->tokens()->count();
        echo "Tokens after logout: {$tokenCountAfter}\n";
        
        // Check if token was deleted from database
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $this->user->id,
            'tokenable_type' => User::class
        ]);
        
        // Try to use the token - should fail
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson('/api/profile');
        
        $response->assertStatus(401);
    }
}
