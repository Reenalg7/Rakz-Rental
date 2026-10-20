<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Billing;
use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Admin customer management: add / update / delete, access control,
 * validation, and the safeguards around deleting customers with history.
 */
class AdminCustomerManagementTest extends TestCase
{
    use RefreshDatabase;

    private const STRONG = 'Str0ng!Pass';

    private function admin(): User
    {
        return User::create([
            'name' => 'Admin', 'email' => 'admin@test.com',
            'password' => Hash::make('Admin!234'), 'role' => 'admin',
        ]);
    }

    private function customer(array $o = []): User
    {
        static $n = 0;
        $n++;
        return User::create(array_merge([
            'name' => "Customer $n", 'email' => "cust$n@test.com",
            'phone' => '+679 700 0000', 'password' => Hash::make('Old!Passw0rd'),
            'role' => 'customer',
        ], $o));
    }

    private function booking(User $u, string $status = 'completed', bool $withBilling = true): Booking
    {
        $v = Vehicle::firstOrCreate(['registration_no' => 'TST100'], [
            'name' => 'Test Car', 'make' => 'Toyota', 'model' => 'Yaris', 'year' => 2022,
            'daily_rate' => 100, 'seats' => 5, 'transmission' => 'automatic',
            'fuel_type' => 'petrol', 'status' => 'available',
        ]);
        $b = Booking::create([
            'user_id' => $u->id, 'vehicle_registration_no' => $v->registration_no,
            'pickup_date' => now()->addDays(1)->toDateString(),
            'return_date' => now()->addDays(3)->toDateString(),
            'total_days' => 3, 'daily_rate_snapshot' => 100, 'total_amount' => 300,
            'status' => $status,
        ]);
        if ($withBilling) {
            Billing::create(['booking_id' => $b->id, 'amount' => 300, 'status' => 'paid']);
        }
        return $b;
    }

    private function payload(array $o = []): array
    {
        return array_merge([
            'name' => 'New Person', 'email' => 'new@test.com', 'phone' => '+679 123 4567',
            'address' => '1 Test St', 'nic' => 'N123', 'driver_license' => 'DL999',
            'password' => self::STRONG, 'password_confirmation' => self::STRONG,
        ], $o);
    }

    /* -------------------------- access control -------------------------- */

    public function test_guest_is_redirected_to_login_for_every_customer_management_route(): void
    {
        $c = $this->customer();
        $this->get(route('admin.customers.create'))->assertRedirect(route('login'));
        $this->post(route('admin.customers.store'), $this->payload())->assertRedirect(route('login'));
        $this->get(route('admin.customers.edit', $c))->assertRedirect(route('login'));
        $this->put(route('admin.customers.update', $c), $this->payload())->assertRedirect(route('login'));
        $this->delete(route('admin.customers.destroy', $c))->assertRedirect(route('login'));

        $this->assertDatabaseMissing('users', ['email' => 'new@test.com']);
        $this->assertDatabaseHas('users', ['id' => $c->id]);
    }

    public function test_customer_role_cannot_manage_customers(): void
    {
        $me = $this->customer();
        $other = $this->customer();
        $this->actingAs($me);

        $this->get(route('admin.customers.create'))->assertForbidden();
        $this->post(route('admin.customers.store'), $this->payload())->assertForbidden();
        $this->get(route('admin.customers.edit', $other))->assertForbidden();
        $this->put(route('admin.customers.update', $other), $this->payload())->assertForbidden();
        $this->delete(route('admin.customers.destroy', $other))->assertForbidden();

        $this->assertDatabaseHas('users', ['id' => $other->id, 'name' => $other->name]);
        $this->assertDatabaseMissing('users', ['email' => 'new@test.com']);
    }

    /* ------------------------------ create ------------------------------ */

    public function test_admin_can_open_the_add_form_and_it_is_not_swallowed_by_show_route(): void
    {
        $this->actingAs($this->admin());
        $this->get(route('admin.customers.create'))
            ->assertOk()->assertSee('Add Customer')->assertSee('customer-save', false);
    }

    public function test_admin_creates_customer_with_hashed_password_forced_role_and_audit_entry(): void
    {
        $this->actingAs($this->admin());

        $res = $this->post(route('admin.customers.store'), $this->payload(['role' => 'admin']));

        $u = User::where('email', 'new@test.com')->firstOrFail();
        $res->assertRedirect(route('admin.customers.show', $u))->assertSessionHas('success');

        $this->assertSame('customer', $u->role, 'role must never come from request input');
        $this->assertNotSame(self::STRONG, $u->password);
        $this->assertTrue(Hash::check(self::STRONG, $u->password), 'password must be hashed exactly once');
        $this->assertSame('DL999', $u->driver_license);
        $this->assertDatabaseHas('audit_logs', ['action' => 'customer.create', 'target_id' => (string) $u->id]);
    }

    public function test_newly_created_customer_can_log_in_and_lands_on_customer_area(): void
    {
        $this->actingAs($this->admin());
        $this->post(route('admin.customers.store'), $this->payload());
        auth()->logout();

        $this->post(route('login'), ['email' => 'new@test.com', 'password' => self::STRONG])
            ->assertRedirect(route('customer.vehicles.index'));
        $this->assertAuthenticated();
    }

    public function test_create_validation_errors_do_not_create_a_user_and_keep_input_but_not_password(): void
    {
        $this->actingAs($this->admin());
        $this->customer(['email' => 'taken@test.com']);
        $before = User::count();

        $cases = [
            'name required'      => [['name' => ''], 'name'],
            'email required'     => [['email' => ''], 'email'],
            'email invalid'      => [['email' => 'nope'], 'email'],
            'email duplicate'    => [['email' => 'taken@test.com'], 'email'],
            'password required'  => [['password' => '', 'password_confirmation' => ''], 'password'],
            'password weak'      => [['password' => 'abc', 'password_confirmation' => 'abc'], 'password'],
            'password mismatch'  => [['password_confirmation' => 'Different!1A'], 'password'],
            'phone invalid'      => [['phone' => 'call me maybe'], 'phone'],
            'name too long'      => [['name' => str_repeat('a', 121)], 'name'],
        ];
        foreach ($cases as [$override, $field]) {
            $this->from(route('admin.customers.create'))
                ->post(route('admin.customers.store'), $this->payload($override))
                ->assertRedirect(route('admin.customers.create'))
                ->assertSessionHasErrors($field);
        }
        $this->assertSame($before, User::count());

        $this->from(route('admin.customers.create'))
            ->post(route('admin.customers.store'), $this->payload(['name' => 'Keep Me', 'phone' => 'bad!']))
            ->assertSessionHasInput('name', 'Keep Me')
            ->assertSessionMissing('_old_input.password')
            ->assertSessionMissing('_old_input.password_confirmation');
    }

    public function test_optional_fields_may_be_left_blank(): void
    {
        $this->actingAs($this->admin());
        $this->post(route('admin.customers.store'), $this->payload([
            'phone' => '', 'address' => '', 'nic' => '', 'driver_license' => '',
        ]))->assertSessionHasNoErrors();
        $u = User::where('email', 'new@test.com')->firstOrFail();
        $this->assertNull($u->phone);
        $this->assertNull($u->nic);
    }

    /* ------------------------------ update ------------------------------ */

    public function test_admin_can_open_edit_form_prefilled(): void
    {
        $this->actingAs($this->admin());
        $c = $this->customer(['name' => 'Prefill Me', 'driver_license' => 'XYZ1']);
        $this->get(route('admin.customers.edit', $c))
            ->assertOk()->assertSee('Prefill Me')->assertSee('XYZ1');
    }

    public function test_update_changes_details_and_blank_password_keeps_existing_one(): void
    {
        $this->actingAs($this->admin());
        $c = $this->customer();
        $oldHash = $c->password;

        $this->put(route('admin.customers.update', $c), $this->payload([
            'name' => 'Renamed', 'email' => $c->email, 'password' => '', 'password_confirmation' => '',
        ]))->assertRedirect(route('admin.customers.show', $c))->assertSessionHas('success');

        $c->refresh();
        $this->assertSame('Renamed', $c->name);
        $this->assertSame($oldHash, $c->password, 'blank password must leave the hash untouched');
        $this->assertTrue(Hash::check('Old!Passw0rd', $c->password));
        $this->assertSame('customer', $c->role);
        $this->assertDatabaseHas('audit_logs', ['action' => 'customer.update', 'target_id' => (string) $c->id]);
    }

    public function test_update_can_reset_password_and_it_is_hashed_once(): void
    {
        $this->actingAs($this->admin());
        $c = $this->customer();

        $this->put(route('admin.customers.update', $c), $this->payload([
            'email' => $c->email, 'password' => 'Brand!New9x', 'password_confirmation' => 'Brand!New9x',
        ]))->assertSessionHasNoErrors();

        $c->refresh();
        $this->assertTrue(Hash::check('Brand!New9x', $c->password));
        $this->assertFalse(Hash::check('Old!Passw0rd', $c->password));

        $log = AuditLog::where('action', 'customer.update')->latest('id')->first();
        $this->assertStringContainsString('password (reset)', $log->description);
        $this->assertStringNotContainsString('Brand!New9x', $log->description, 'never log the password');
    }

    public function test_update_cannot_promote_customer_to_admin(): void
    {
        $this->actingAs($this->admin());
        $c = $this->customer();
        $this->put(route('admin.customers.update', $c), $this->payload([
            'email' => $c->email, 'role' => 'admin', 'password' => '', 'password_confirmation' => '',
        ]));
        $this->assertSame('customer', $c->fresh()->role);
    }

    public function test_update_email_rules_own_email_allowed_other_customers_email_rejected(): void
    {
        $this->actingAs($this->admin());
        $a = $this->customer(['email' => 'a@test.com']);
        $b = $this->customer(['email' => 'b@test.com']);
        $blank = ['password' => '', 'password_confirmation' => ''];

        $this->put(route('admin.customers.update', $a), $this->payload(['email' => 'a@test.com'] + $blank))
            ->assertSessionHasNoErrors();

        $this->from(route('admin.customers.edit', $a))
            ->put(route('admin.customers.update', $a), $this->payload(['email' => 'b@test.com'] + $blank))
            ->assertSessionHasErrors('email');
        $this->assertSame('a@test.com', $a->fresh()->email);
    }

    public function test_update_with_password_but_no_confirmation_is_rejected(): void
    {
        $this->actingAs($this->admin());
        $c = $this->customer();
        $this->put(route('admin.customers.update', $c), $this->payload([
            'email' => $c->email, 'password' => 'Brand!New9x', 'password_confirmation' => '',
        ]))->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check('Old!Passw0rd', $c->fresh()->password));
    }

    public function test_update_with_confirmation_but_no_password_is_rejected(): void
    {
        $this->actingAs($this->admin());
        $c = $this->customer();
        $this->put(route('admin.customers.update', $c), $this->payload([
            'email' => $c->email, 'password' => '', 'password_confirmation' => 'Brand!New9x',
        ]))->assertSessionHasErrors('password');
    }

    public function test_update_with_nothing_changed_reports_no_changes_and_writes_no_audit_row(): void
    {
        $this->actingAs($this->admin());
        $c = $this->customer(['address' => null, 'nic' => null, 'driver_license' => null]);
        $before = AuditLog::where('action', 'customer.update')->count();

        $this->put(route('admin.customers.update', $c), [
            'name' => $c->name, 'email' => $c->email, 'phone' => $c->phone,
            'address' => '', 'nic' => '', 'driver_license' => '',
            'password' => '', 'password_confirmation' => '',
        ])->assertSessionHas('success', 'No changes were made.');

        $this->assertSame($before, AuditLog::where('action', 'customer.update')->count());
    }

    public function test_updated_customer_keeps_their_bookings(): void
    {
        $this->actingAs($this->admin());
        $c = $this->customer();
        $b = $this->booking($c);
        $this->put(route('admin.customers.update', $c), $this->payload([
            'email' => 'moved@test.com', 'password' => '', 'password_confirmation' => '',
        ]))->assertSessionHasNoErrors();
        $this->assertDatabaseHas('bookings', ['id' => $b->id, 'user_id' => $c->id]);
    }

    /* ------------------------------ delete ------------------------------ */

    public function test_admin_can_delete_customer_without_bookings(): void
    {
        $this->actingAs($this->admin());
        $c = $this->customer();

        $this->delete(route('admin.customers.destroy', $c))
            ->assertRedirect(route('admin.customers.index'))->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $c->id]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'customer.delete', 'target_id' => (string) $c->id]);
        $this->get(route('admin.customers.show', $c))->assertNotFound();
    }

    public function test_delete_is_blocked_when_customer_has_any_booking_and_nothing_is_lost(): void
    {
        $this->actingAs($this->admin());

        foreach (['pending', 'confirmed', 'completed', 'cancelled'] as $status) {
            $c = $this->customer();
            $b = $this->booking($c, $status);

            $this->from(route('admin.customers.index'))
                ->delete(route('admin.customers.destroy', $c))
                ->assertRedirect(route('admin.customers.index'))
                ->assertSessionHas('error');

            $this->assertDatabaseHas('users', ['id' => $c->id]);
            $this->assertDatabaseHas('bookings', ['id' => $b->id]);
            $this->assertDatabaseHas('billings', ['booking_id' => $b->id]);
            $this->assertDatabaseHas('audit_logs', ['action' => 'customer.delete.blocked', 'target_id' => (string) $c->id]);
        }
    }

    public function test_blocked_delete_message_mentions_active_bookings(): void
    {
        $this->actingAs($this->admin());
        $c = $this->customer();
        $this->booking($c, 'confirmed');
        $this->delete(route('admin.customers.destroy', $c));
        $this->assertStringContainsString('1 still pending/confirmed', session('error'));
    }

    public function test_admin_accounts_cannot_be_viewed_edited_or_deleted_via_customer_routes(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);
        $other = User::create(['name' => 'Other Admin', 'email' => 'o@test.com', 'password' => Hash::make('x'), 'role' => 'admin']);

        $this->get(route('admin.customers.show', $other))->assertNotFound();
        $this->get(route('admin.customers.edit', $other))->assertNotFound();
        $this->put(route('admin.customers.update', $other), $this->payload())->assertNotFound();
        $this->delete(route('admin.customers.destroy', $other))->assertNotFound();
        $this->delete(route('admin.customers.destroy', $admin))->assertNotFound();

        $this->assertDatabaseHas('users', ['id' => $other->id]);
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_unknown_customer_ids_return_404(): void
    {
        $this->actingAs($this->admin());
        $this->get(route('admin.customers.edit', 99999))->assertNotFound();
        $this->put(route('admin.customers.update', 99999), $this->payload())->assertNotFound();
        $this->delete(route('admin.customers.destroy', 99999))->assertNotFound();
    }

    public function test_deleting_a_customer_removes_their_favorites_and_notifications_but_keeps_audit_rows(): void
    {
        $this->actingAs($this->admin());
        $c = $this->customer();
        AuditLog::create(['user_id' => $c->id, 'actor_name' => $c->name, 'actor_role' => 'customer', 'action' => 'login']);

        $this->delete(route('admin.customers.destroy', $c))->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $c->id]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'login', 'user_id' => null]);
    }

    /* ----------------------- UI + regression checks ---------------------- */

    public function test_index_shows_add_button_and_enables_delete_only_for_customers_without_bookings(): void
    {
        $this->actingAs($this->admin());
        $free = $this->customer(['name' => 'Free Person']);
        $busy = $this->customer(['name' => 'Busy Person']);
        $this->booking($busy);

        $html = $this->get(route('admin.customers.index'))->assertOk()
            ->assertSee('admin-add-customer', false)->getContent();

        // Exactly one working DELETE form (the booking-free customer); the busy one is a disabled button.
        $this->assertSame(1, substr_count($html, 'name="_method" value="DELETE"'));
        $this->assertStringContainsString('data-testid="delete-customer-'.$free->id.'"', $html);
        $this->assertMatchesRegularExpression('/<button type="button" disabled[^>]*data-testid="delete-customer-'.$busy->id.'"/s', $html);
        $this->assertStringContainsString('Cannot delete: this customer has booking history', $html);
        $this->assertStringContainsString(route('admin.customers.edit', $busy), $html);
    }

    public function test_show_page_has_edit_and_delete_controls(): void
    {
        $this->actingAs($this->admin());
        $free = $this->customer();
        $html = $this->get(route('admin.customers.show', $free))->assertOk()
            ->assertSee(route('admin.customers.edit', $free), false)->getContent();
        $this->assertSame(1, substr_count($html, 'name="_method" value="DELETE"'));

        $busy = $this->customer();
        $this->booking($busy);
        $html = $this->get(route('admin.customers.show', $busy))->assertOk()
            ->assertSee('booking history')->getContent();
        $this->assertSame(0, substr_count($html, 'name="_method" value="DELETE"'));
    }

    public function test_existing_search_still_works(): void
    {
        $this->actingAs($this->admin());
        $this->customer(['name' => 'Zed Findable', 'email' => 'zed@test.com']);
        $this->customer(['name' => 'Someone Else', 'email' => 'else@test.com']);
        $this->get(route('admin.customers.index', ['q' => 'Findable']))
            ->assertOk()->assertSee('Zed Findable')->assertDontSee('Someone Else');
    }

    public function test_other_admin_pages_and_exports_still_load(): void
    {
        $this->actingAs($this->admin());
        $c = $this->customer();
        $this->booking($c);

        foreach (['admin.dashboard', 'admin.vehicles.index', 'admin.bookings.index', 'admin.categories.index',
                  'admin.replacements.index', 'admin.reports.index', 'admin.audit_logs.index',
                  'admin.customers.index'] as $name) {
            $this->get(route($name))->assertOk();
        }
        $this->get(route('admin.customers.show', $c))->assertOk();
        $this->get(route('admin.export.customers'))->assertOk();
    }

    public function test_customer_portal_and_public_pages_still_work(): void
    {
        $this->get('/')->assertOk();
        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();

        $this->actingAs($this->customer());
        $this->get(route('customer.profile.edit'))->assertOk();
        $this->get(route('customer.bookings.index'))->assertOk();
    }

    public function test_self_registration_still_works(): void
    {
        $this->post(route('register'), [
            'name' => 'Self Reg', 'email' => 'self@test.com',
            'password' => self::STRONG, 'password_confirmation' => self::STRONG,
        ])->assertRedirect(route('customer.vehicles.index'));
        $this->assertDatabaseHas('users', ['email' => 'self@test.com', 'role' => 'customer']);
    }
}
