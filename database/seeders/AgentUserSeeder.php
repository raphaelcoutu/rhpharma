<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AgentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! app()->environment('local')) {
            throw new RuntimeException(
                'AgentUserSeeder can only run in the local environment.'
            );
        }

        $agentUser = User::updateOrCreate(
            ['email' => 'agent@example.test'],
            [
                'firstname' => 'AI',
                'lastname' => 'Agent',
                'password' => Hash::make('@i-agent-Password!'),
                'workdays_per_week' => 5,
                'branch_id' => 1,
            ]
        );

        $superUserRole = Role::firstOrCreate(
            ['name' => 'Super User'],
            ['description' => 'Administration']
        );

        $superUserRole->permissions()->sync(Permission::query()->pluck('code')->all());
        $agentUser->roles()->sync([$superUserRole->getKey()]);
    }
}
