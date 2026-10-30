<?php

namespace Tests\Feature;

use App\Models\Building;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Js;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class BuildingFormTest extends TestCase
{
    private School $school;

    protected function setUp(): void
    {
        parent::setUp();

        // The legacy school/audit tables are imported from SQL in production.
        // Build only the required schema in the isolated in-memory test database.
        Schema::create('school', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('censusNo')->nullable();
            $table->string('eduDivision')->nullable();
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });
        Schema::create('auditlog', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('userId');
            $table->string('userEmail');
            $table->string('action');
            $table->string('resourceType');
            $table->string('resourceId');
            $table->text('details');
            $table->timestamp('createdAt')->nullable();
        });
        (require database_path('migrations/2026_10_30_000000_create_buildings_and_facilities_tables.php'))->up();
        (require database_path('migrations/2026_10_30_000001_add_details_and_import_all_buildings.php'))->up();
        (require database_path('migrations/2026_10_30_000002_clear_imported_census_notes.php'))->up();
        (require database_path('migrations/2026_10_30_000003_standardize_bilingual_building_status.php'))->up();

        $this->school = School::create([
            'id' => 'test-school', 'name' => 'Test School', 'censusNo' => '09999', 'eduDivision' => 'Wilgamuwa',
        ]);
    }

    public static function areas(): array
    {
        return [['zonal', User::ROLE_ZONAL_ADMIN], ['school', User::ROLE_SCHOOL_ADMIN]];
    }

    private function signIn(string $role): void
    {
        $this->actingAs(new User([
            'id' => 'test-admin', 'name' => 'Test Admin', 'email' => 'admin@example.test',
            'role' => $role, 'schoolId' => $this->school->id, 'isActive' => true,
        ]));
    }

    #[DataProvider('areas')]
    public function test_building_can_be_created_with_optional_fields_empty(string $area, string $role): void
    {
        $this->signIn($role);
        $this->from(route("$area.buildings"))->post(route("$area.buildings.store"), [
            'schoolId' => $this->school->id, 'buildingName' => "Principal's Hall",
            'floors' => '', 'constructionYear' => '', 'lastRepairYear' => '',
        ])->assertRedirect(route("$area.buildings"))->assertSessionHasNoErrors();

        $this->assertDatabaseHas('buildings', [
            'schoolId' => $this->school->id, 'buildingName' => "Principal's Hall",
            'buildingType' => 'Learning / ඉගෙනුම්',
            'floors' => 1, 'status' => 'Good / Usable (හොඳයි / භාවිතයට සුදුසුයි)', 'constructionYear' => null,
        ]);
        $this->assertDatabaseCount('auditlog', 1);
    }

    #[DataProvider('areas')]
    public function test_building_created_with_dimensions_calculates_area_and_size(string $area, string $role): void
    {
        $this->signIn($role);
        $this->from(route("$area.buildings"))->post(route("$area.buildings.store"), [
            'schoolId' => $this->school->id,
            'buildingName' => 'Science Block A',
            'buildingType' => 'Learning / ඉගෙනුම්',
            'lengthFeet' => 80,
            'widthFeet' => 20,
            'floors' => 2,
        ])->assertRedirect(route("$area.buildings"))->assertSessionHasNoErrors();

        $this->assertDatabaseHas('buildings', [
            'schoolId' => $this->school->id,
            'buildingName' => 'Science Block A',
            'buildingType' => 'Learning / ඉගෙනුම්',
            'lengthFeet' => 80,
            'widthFeet' => 20,
            'areaSqft' => 3200, // 80 * 20 * 2 floors = 3200
            'floors' => 2,
        ]);
    }

    #[DataProvider('areas')]
    public function test_invalid_create_reopens_form_with_input_and_errors(string $area, string $role): void
    {
        $this->signIn($role);
        $this->from(route("$area.buildings"))->post(route("$area.buildings.store"), [
            'schoolId' => $this->school->id, 'buildingName' => "Hall's notes", 'floors' => 21,
        ])->assertSessionHasErrors(['floors'], null, 'building')
            ->assertSessionHasInput('buildingName', "Hall's notes");

        $this->get(route("$area.buildings"))->assertOk()
            ->assertSee('Please correct the following errors:')
            ->assertSee('const hasErrors = true;', false)
            ->assertSee('Hall', false);
        $this->assertDatabaseCount('buildings', 0);
    }

    #[DataProvider('areas')]
    public function test_failed_edit_preserves_id_and_does_not_create_a_duplicate(string $area, string $role): void
    {
        $this->signIn($role);
        $building = Building::create(['schoolId' => $this->school->id, 'buildingName' => 'Original Hall']);
        $this->from(route("$area.buildings"))->put(route("$area.buildings.update", $building->id), [
            '_building_id' => (string) $building->id, 'schoolId' => $this->school->id,
            'buildingName' => 'Edited Hall', 'constructionYear' => 1700,
        ])->assertSessionHasErrors(['constructionYear'], null, 'building')
            ->assertSessionHasInput('_building_id', (string) $building->id);

        $this->get(route("$area.buildings"))->assertOk()
            ->assertSee('const previousId = '.Js::from((string) $building->id).';', false)
            ->assertSee('const hasErrors = true;', false);

        $this->put(route("$area.buildings.update", $building->id), [
            'schoolId' => $this->school->id, 'buildingName' => 'Edited Hall', 'constructionYear' => 2005,
        ])->assertSessionHasNoErrors();
        $this->assertDatabaseCount('buildings', 1);
        $this->assertDatabaseHas('buildings', ['id' => $building->id, 'buildingName' => 'Edited Hall']);
    }

    #[DataProvider('areas')]
    public function test_names_with_quotes_and_newlines_are_safe_in_action_handlers(string $area, string $role): void
    {
        $this->signIn($role);
        $name = "Principal's \"Hall\"\nSecond Floor </script>";
        Building::create(['schoolId' => $this->school->id, 'buildingName' => $name]);
        $this->get(route("$area.buildings"))->assertOk()
            ->assertSee(Js::from($name)->toHtml(), false)
            ->assertSee('<dialog ', false)
            ->assertSee('aria-labelledby="building-dialog-title"', false);
    }
}
