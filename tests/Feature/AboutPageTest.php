<?php

use App\Models\FarmerProfile;
use App\Models\Review;
use App\Models\User;

function aboutFarmer(): FarmerProfile
{
    $user = User::factory()->create(['role' => 'farmer', 'address' => 'Farm Road']);

    return FarmerProfile::create(['user_id' => $user->id, 'stall_name' => 'Seasonal Stall', 'business_name' => 'Local Farm',
        'description' => 'Seasonal harvest.', 'address' => 'Farm Road', 'city' => 'Lahore', 'state' => 'Punjab',
        'country' => 'Pakistan', 'latitude' => 31.52, 'longitude' => 74.35, 'operating_days' => 'Saturday',
        'start_time' => '08:00', 'end_time' => '15:00', 'approval_status' => 'approved']);
}

test('about page only displays published unflagged reviews of approved growers', function () {
    $farmer = aboutFarmer();
    $pending = aboutFarmer();
    $pending->update(['approval_status' => 'pending']);
    $customer = User::factory()->create(['role' => 'customer', 'address' => 'Local Road']);
    $review = Review::create(['user_id' => $customer->id, 'farmer_id' => $farmer->id, 'rating' => 4, 'comment' => 'A lovely market visit.']);
    Review::create(['user_id' => $customer->id, 'farmer_id' => $farmer->id, 'rating' => 5, 'comment' => 'Hidden feedback', 'is_active' => false]);
    Review::create(['user_id' => $customer->id, 'farmer_id' => $farmer->id, 'rating' => 5, 'comment' => 'Flagged feedback', 'is_flagged' => true]);
    Review::create(['user_id' => $customer->id, 'farmer_id' => $pending->id, 'rating' => 5, 'comment' => 'Pending grower feedback']);

    $this->get('/about')->assertSee('A lovely market visit.')
        ->assertViewHas('reviews', fn ($reviews) => $reviews->pluck('id')->all() === [$review->id])
        ->assertDontSee('Hidden feedback')->assertDontSee('Flagged feedback')->assertDontSee('Pending grower feedback');
});

test('about page renders a useful empty state without invented testimonials', function () {
    $this->get('/about')->assertSee('Every market visit has a story.')
        ->assertDontSee('data-review-carousel', false)->assertSee('Start your first visit');
});

test('about reviews escape customer content and show the latest eight entries', function () {
    $farmer = aboutFarmer();
    $customer = User::factory()->create(['name' => '<b>Customer</b>', 'role' => 'customer', 'address' => 'Local Road']);
    $farmer->update(['stall_name' => '<b>Stall</b>']);
    for ($index = 0; $index < 9; $index++) {
        Review::create(['user_id' => $customer->id, 'farmer_id' => $farmer->id, 'rating' => 4, 'comment' => $index === 8 ? '<script>alert(1)</script>' : 'Market story '.$index]);
    }

    $this->get('/about')->assertViewHas('reviews', fn ($reviews) => $reviews->count() === 8)
        ->assertDontSee('Market story 0')->assertSee('Market story 7')
        ->assertSee('<script>alert(1)</script>')->assertDontSee('<script>alert(1)</script>', false)
        ->assertSee('<b>Customer</b>')->assertDontSee('<b>Customer</b>', false)
        ->assertSee('<b>Stall</b>')->assertDontSee('<b>Stall</b>', false);
});
