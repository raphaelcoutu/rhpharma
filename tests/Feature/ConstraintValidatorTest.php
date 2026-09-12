<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Constraint;
use App\Models\ConstraintType;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ConstraintValidatorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->branch = Branch::create(['name' => 'Pharmaciens']);
        $this->createSuperUser();
    }

    public function test_authenticated_validator_can_see_pending_constraints(): void
    {
        $constraint = $this->createPendingConstraint([
            'comment' => 'Demande de congé.',
        ]);

        $response = $this->actingAs($this->superUser)
            ->get(route('constraintsValidator.index'));

        $response->assertInertia(fn (Assert $page) => $page
            ->component('constraintsValidator/index', false)
            ->where('validatorId', $this->superUser->id)
            ->where('schedule', null)
            ->has('constraints', 1)
            ->where('constraints.0.id', $constraint->id)
            ->where('constraints.0.comment', 'Demande de congé.')
            ->where('constraints.0.user.id', $constraint->user_id)
            ->where('constraints.0.constraint_type.id', $constraint->constraint_type_id));
    }

    public function test_schedule_filter_only_returns_pending_constraints_in_the_schedule(): void
    {
        $schedule = Schedule::factory()->create([
            'branch_id' => $this->branch->id,
            'start_date' => Carbon::create(2026, 9, 6),
            'end_date' => Carbon::create(2026, 10, 3),
        ]);
        $insideConstraint = $this->createPendingConstraint([
            'start_datetime' => Carbon::create(2026, 9, 10, 8),
            'end_datetime' => Carbon::create(2026, 9, 10, 16),
        ]);
        $this->createPendingConstraint([
            'start_datetime' => Carbon::create(2026, 10, 5, 8),
            'end_datetime' => Carbon::create(2026, 10, 5, 16),
        ]);

        $response = $this->actingAs($this->superUser)
            ->get(route('constraintsValidator.index', ['schedule' => $schedule->id]));

        $response->assertInertia(fn (Assert $page) => $page
            ->component('constraintsValidator/index', false)
            ->where('schedule.id', $schedule->id)
            ->has('constraints', 1)
            ->where('constraints.0.id', $insideConstraint->id)
            ->missing('constraints.1'));
    }

    public function test_history_page_renders_filtered_validations(): void
    {
        $approvedConstraint = $this->createValidatedConstraint(['status' => 1]);
        $this->createValidatedConstraint(['status' => 2]);

        $response = $this->actingAs($this->superUser)
            ->get(route('constraintsValidator.history', ['status' => 1]));

        $response->assertInertia(fn (Assert $page) => $page
            ->component('constraintsValidator/history', false)
            ->has('constraints', 1)
            ->where('constraints.0.id', $approvedConstraint->id)
            ->where('constraints.0.status', 1)
            ->where('constraints.0.validator.id', $this->superUser->id));
    }

    public function test_validator_can_approve_a_constraint(): void
    {
        $constraint = $this->createPendingConstraint();

        $response = $this->actingAs($this->superUser)
            ->putAjax("/api/constraints-validator/{$constraint->id}", [
                'status' => 1,
                'validated_by' => $this->superUser->id,
            ]);

        $response->assertOk()->assertJson(['status' => 'ok']);
        $this->assertDatabaseHas('constraints', [
            'id' => $constraint->id,
            'status' => 1,
            'validated_by' => $this->superUser->id,
        ]);
    }

    public function test_validator_update_rejects_an_invalid_status(): void
    {
        $constraint = $this->createPendingConstraint();

        $response = $this->actingAs($this->superUser)
            ->putAjax("/api/constraints-validator/{$constraint->id}", [
                'status' => 3,
                'validated_by' => $this->superUser->id,
            ]);

        $response->assertUnprocessable()->assertJsonValidationErrors(['status']);
        $this->assertDatabaseHas('constraints', ['id' => $constraint->id, 'status' => 0, 'validated_by' => null]);
    }

    public function test_unauthenticated_user_is_redirected_from_constraint_validation(): void
    {
        $this->get(route('constraintsValidator.index'))->assertRedirect('/login');
    }

    public function test_user_without_constraint_read_permission_is_forbidden(): void
    {
        $user = User::factory()->create(['branch_id' => $this->branch->id]);

        $this->actingAs($user)->get(route('constraintsValidator.index'))->assertForbidden();
    }

    private function createPendingConstraint(array $attributes = []): Constraint
    {
        $user = User::factory()->create(['branch_id' => $this->branch->id]);
        $constraintType = ConstraintType::factory()->create(['branch_id' => $this->branch->id]);

        return Constraint::factory()
            ->for($user, 'user')
            ->for($constraintType, 'constraintType')
            ->create([
                'status' => 0,
                'validated_by' => null,
                ...$attributes,
            ]);
    }

    private function createValidatedConstraint(array $attributes = []): Constraint
    {
        return $this->createPendingConstraint([
            'status' => 1,
            'validated_by' => $this->superUser->id,
            ...$attributes,
        ]);
    }
}
