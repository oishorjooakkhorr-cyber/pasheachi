<?php

namespace Tests\Feature;

use App\Enums\ErrandStatus;
use App\Enums\TrustLevel;
use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Models\Errand;
use App\Models\FamilyMember;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_errand_links_client_family_member_category_and_runner(): void
    {
        $errand = Errand::factory()->completed()->create();

        $this->assertSame(ErrandStatus::Completed, $errand->status);
        $this->assertTrue($errand->client->isClient());
        $this->assertTrue($errand->familyMember->client->is($errand->client));
        $this->assertTrue($errand->runner->isRunner());
        $this->assertSame($errand->familyMember->district, $errand->runner->district);
        $this->assertEquals($errand->category->base_service_fee, $errand->service_fee);
        $this->assertTrue($errand->client->postedErrands->contains($errand));
        $this->assertTrue($errand->runner->assignedErrands->contains($errand));
    }

    public function test_deleting_a_client_deletes_their_family_members(): void
    {
        $member = FamilyMember::factory()->create();

        $member->client->delete();

        $this->assertModelMissing($member);
    }

    public function test_demo_seeder_creates_an_account_for_each_role(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(UserRole::Admin, User::firstWhere('email', 'admin@pasheachi.test')->role);
        $this->assertSame(2, User::firstWhere('email', 'client@pasheachi.test')->familyMembers()->count());
        $this->assertSame(TrustLevel::Gold, User::firstWhere('email', 'runner.dhaka@pasheachi.test')->trust_level);
        $this->assertSame(VerificationStatus::Pending, User::firstWhere('email', 'runner.pending@pasheachi.test')->verification_status);
        $this->assertSame(4, User::runners()->count());
    }
}
