<?php

namespace Tests\Feature;

use App\Models\School;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SchoolExtendedFieldsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('school', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('nameSinhala')->nullable();
            $table->string('category')->nullable();
            $table->string('censusNo')->nullable()->unique();
            $table->string('address')->nullable();
            $table->string('landArea')->nullable();
            $table->string('difficultyCategory')->nullable();
            $table->string('contactNumber')->nullable();
            $table->string('email')->nullable();
            $table->string('schoolType')->nullable();
            $table->string('eduZone')->nullable();
            $table->string('eduDivision')->nullable();
            $table->string('dsDivision')->nullable();
            $table->string('gnDivision')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('gradeStart')->nullable();
            $table->integer('gradeEnd')->nullable();
            $table->integer('sanctionedPositions')->nullable();
            $table->string('principalName')->nullable();
            $table->string('principalNic')->nullable();
            $table->string('principalMobile')->nullable();
            $table->boolean('isActive')->default(true);
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });

        Schema::create('teacher', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('schoolId')->nullable();
            $table->string('fullName')->nullable();
            $table->string('nic')->nullable();
            $table->string('gender')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('position')->nullable();
            $table->string('class_grade')->nullable();
            $table->string('appt_code')->nullable();
            $table->date('schappoint')->nullable();
            $table->boolean('isActive')->default(true);
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });

        Schema::create('buildings', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('schoolId');
            $table->string('buildingName');
            $table->string('buildingType')->nullable();
            $table->decimal('lengthFeet', 8, 2)->nullable();
            $table->decimal('widthFeet', 8, 2)->nullable();
            $table->decimal('areaSqFt', 10, 2)->nullable();
            $table->string('size')->nullable();
            $table->integer('floors')->default(1);
            $table->integer('constructionYear')->nullable();
            $table->integer('lastRepairYear')->nullable();
            $table->boolean('needsRenovation')->default(false);
            $table->string('status')->nullable();
            $table->text('remarks')->nullable();
            $table->string('fundingSource')->nullable();
            $table->decimal('estimatedCostLkr', 15, 2)->nullable();
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });

        Schema::create('school_sanitation', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('schoolId')->unique();
            $table->integer('boysToilets')->default(0);
            $table->integer('girlsToilets')->default(0);
            $table->integer('teacherToilets')->default(0);
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });

        Schema::create('studentcount', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('schoolId');
            $table->string('grade');
            $table->string('academicYear');
            $table->integer('maleCount')->default(0);
            $table->integer('femaleCount')->default(0);
            $table->integer('classCount')->default(0);
            $table->string('classes')->nullable();
            $table->json('classDetails')->nullable();
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
    }

    private function signInZonalAdmin(): void
    {
        $this->actingAs(new User([
            'id' => 'admin-user',
            'name' => 'Zonal Administrator',
            'email' => 'admin@zeowilgamuwa.lk',
            'role' => User::ROLE_ZONAL_ADMIN,
            'isActive' => true,
        ]));
    }

    public function test_school_bilingual_difficulty_and_badge_classes(): void
    {
        $school1 = new School(['difficultyCategory' => 'Very Convenient']);
        $this->assertEquals('Very Convenient (ඉතා පහසු)', $school1->bilingual_difficulty);
        $this->assertStringContainsString('emerald', $school1->difficulty_badge_class);

        $school2 = new School(['difficultyCategory' => 'Convenient']);
        $this->assertEquals('Convenient (පහසු)', $school2->bilingual_difficulty);
        $this->assertStringContainsString('blue', $school2->difficulty_badge_class);

        $school3 = new School(['difficultyCategory' => 'Difficult']);
        $this->assertEquals('Difficult (දුෂ්කර)', $school3->bilingual_difficulty);
        $this->assertStringContainsString('amber', $school3->difficulty_badge_class);

        $school4 = new School(['difficultyCategory' => 'Very Difficult']);
        $this->assertEquals('Very Difficult (ඉතා දුෂ්කර)', $school4->bilingual_difficulty);
        $this->assertStringContainsString('rose', $school4->difficulty_badge_class);
    }

    public function test_zonal_admin_can_create_school_with_extended_fields(): void
    {
        $this->signInZonalAdmin();

        $response = $this->post(route('zonal.schools.store'), [
            'name' => 'CP/W/Hettipola National School',
            'censusNo' => '09432',
            'schoolType' => '1AB',
            'gradeStart' => 1,
            'gradeEnd' => 13,
            'address' => 'Hettipola, Wilgamuwa',
            'landArea' => '4 Acres 2 Roods 10 Perches',
            'difficultyCategory' => 'Difficult',
            'eduZone' => 'Wilgamuwa',
            'eduDivision' => 'Wilgamuwa',
            'dsDivision' => 'Wilgamuwa',
            'gnDivision' => 'Hettipola',
            'latitude' => 7.7123456,
            'longitude' => 80.9123456,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('school', [
            'name' => 'CP/W/Hettipola National School',
            'censusNo' => '09432',
            'landArea' => '4 Acres 2 Roods 10 Perches',
            'difficultyCategory' => 'Difficult',
            'dsDivision' => 'Wilgamuwa',
            'gnDivision' => 'Hettipola',
        ]);
    }

    public function test_zonal_admin_can_update_school_with_extended_fields(): void
    {
        $this->signInZonalAdmin();

        $school = School::create([
            'name' => 'Old School Name',
            'censusNo' => '08888',
            'eduDivision' => 'Laggala',
            'dsDivision' => 'Laggala-Pallegama',
        ]);

        $response = $this->put(route('zonal.schools.update', $school->id), [
            'name' => 'Updated Laggala Pallegama Maha Vidyalaya',
            'censusNo' => '08888',
            'schoolType' => '1C',
            'gradeStart' => 6,
            'gradeEnd' => 13,
            'address' => 'Pallegama, Laggala',
            'landArea' => '3 Acres 1 Rood',
            'difficultyCategory' => 'Very Difficult',
            'eduDivision' => 'Laggala',
            'dsDivision' => 'Laggala-Pallegama',
            'gnDivision' => 'Laggala Pallegama',
            'latitude' => 7.5501234,
            'longitude' => 80.7801234,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('school', [
            'id' => $school->id,
            'name' => 'Updated Laggala Pallegama Maha Vidyalaya',
            'landArea' => '3 Acres 1 Rood',
            'difficultyCategory' => 'Very Difficult',
            'dsDivision' => 'Laggala-Pallegama',
            'gnDivision' => 'Laggala Pallegama',
        ]);
    }

    public function test_school_overview_page_renders_extended_fields_and_maps_button(): void
    {
        $this->signInZonalAdmin();

        $school = School::create([
            'name' => 'Wilgamuwa Central College',
            'censusNo' => '09111',
            'schoolType' => '1AB',
            'gradeStart' => 1,
            'gradeEnd' => 13,
            'address' => 'Central Road, Wilgamuwa',
            'landArea' => '5 Acres',
            'difficultyCategory' => 'Very Convenient',
            'eduDivision' => 'Wilgamuwa',
            'dsDivision' => 'Wilgamuwa',
            'gnDivision' => 'Wilgamuwa',
            'latitude' => 7.7200000,
            'longitude' => 80.9500000,
        ]);

        $response = $this->get(route('zonal.schools.show', $school->id));
        $response->assertOk();
        $response->assertSee('Wilgamuwa Central College');
        $response->assertSee('5 Acres');
        $response->assertSee('Very Convenient (ඉතා පහසු)');
        $response->assertSee('Geographic & Land Area', false);
        $response->assertSee('https://www.google.com/maps?q=', false);
        $response->assertSee('View on Google Maps');
    }

    public function test_zonal_schools_index_page_loads_cleanly(): void
    {
        $this->signInZonalAdmin();

        \App\Models\Teacher::create([
            'id' => 't-demo-1',
            'fullName' => 'Kamal Perera',
            'nic' => '198512345678',
            'phone' => '0771234567',
        ]);

        $response = $this->get(route('zonal.schools'));
        $response->assertOk();
        $response->assertSee('Kamal Perera');
    }
}
