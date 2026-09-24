<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Farmer;
use App\Models\Market;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FarmerPortalTest extends TestCase
{
    use RefreshDatabase;

    protected User $farmerUser;
    protected Farmer $farmer;
    protected Market $market;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->market = Market::create([
            'name' => 'Downtown Plaza',
            'address' => '100 Main St',
            'city' => 'Metropolis',
            'operating_days' => 'Saturday',
            'timings' => '08:00 AM - 01:00 PM',
            'latitude' => 40.7128,
            'longitude' => -74.0060,
        ]);

        $this->farmerUser = User::create([
            'name' => 'Green Farmer',
            'username' => 'greenfarmer',
            'email' => 'farmer@test.local',
            'contact_number' => '555-1234',
            'address' => 'Farm Valley',
            'role' => 'farmer',
            'is_active' => true,
            'is_approved' => true,
            'password' => bcrypt('password'),
        ]);

        $this->farmer = Farmer::create([
            'user_id' => $this->farmerUser->id,
            'market_id' => $this->market->id,
            'stall_name' => 'Green Valley Stall',
            'contact_person' => 'Green Farmer',
            'contact_number' => '555-1234',
            'address' => 'Stall 1',
            'operating_days' => 'Saturday',
            'pickup_time_windows' => '08:00 AM - 10:00 AM',
            'cutoff_hours' => 2,
        ]);

        $this->category = Category::create([
            'name' => 'Organic Vegetables',
            'description' => 'Fresh veggies',
        ]);
    }

    public function test_farmer_can_view_dashboard(): void
    {
        $response = $this->actingAs($this->farmerUser)->get(route('farmer.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Green Valley Stall');
    }

    public function test_farmer_can_create_and_replenish_product(): void
    {
        $response = $this->actingAs($this->farmerUser)->post(route('farmer.products.store'), [
            'category_id' => $this->category->id,
            'name' => 'Organic Carrots',
            'description' => 'Sweet bunch',
            'price' => 3.50,
            'unit' => 'bunch',
            'stock_quantity' => 15,
            'weekly_recurring_stock' => 20,
        ]);

        $response->assertRedirect(route('farmer.products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'Organic Carrots',
            'farmer_id' => $this->farmer->id,
            'stock_quantity' => 15,
        ]);

        // Test replenish template
        $product = Product::where('name', 'Organic Carrots')->first();
        $product->stock_quantity = 0;
        $product->is_sold_out = true;
        $product->save();

        $replenishResponse = $this->actingAs($this->farmerUser)->post(route('farmer.products.replenish'));
        $replenishResponse->assertRedirect();

        $product->refresh();
        $this->assertEquals(20, $product->stock_quantity);
        $this->assertFalse((bool)$product->is_sold_out);
    }
}
