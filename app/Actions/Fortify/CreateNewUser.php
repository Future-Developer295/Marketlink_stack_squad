<?php

namespace App\Actions\Fortify;

use App\Models\FarmerProfile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'role' => ['required', 'in:farmer,customer'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'] ?? null,
            'address' => $input['address'],
            'role' => $input['role'],
            'password' => Hash::make($input['password']),
            'is_active' => true,
        ]);

        $user->syncRoles([$input['role']]);

        if ($input['role'] === 'farmer') {
            FarmerProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'stall_name' => $user->name,
                    'business_name' => $user->name,
                    'description' => 'Farmer profile pending completion.',
                    'address' => $user->address ?: 'N/A',
                    'city' => 'N/A',
                    'state' => 'N/A',
                    'country' => 'N/A',
                    'latitude' => 0,
                    'longitude' => 0,
                    'operating_days' => 'Mon',
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'approval_status' => 'pending',
                ]
            );
        }

        return $user;
    }
}
