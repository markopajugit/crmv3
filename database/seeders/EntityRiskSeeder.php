<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\EntityRisk;
use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Seeder;

class EntityRiskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $companies = Company::all();
        $persons = Person::all();

        if ($users->isEmpty() || ($companies->isEmpty() && $persons->isEmpty())) {
            $this->command->warn('Skipping EntityRiskSeeder: No users or entities found.');
            return;
        }

        // Create risk assessments for companies
        if ($companies->isNotEmpty()) {
            foreach ($companies->random(min(25, $companies->count())) as $company) {
                try {
                    $riskLevel = fake()->randomElement(['low', 'medium', 'high']);
                    $assessmentDate = fake()->dateTimeBetween('-1 year', 'now');
                    $reviewDate = fake()->dateTimeBetween($assessmentDate, '+6 months');
                    
                    EntityRisk::create([
                        'riskable_type' => Company::class,
                        'riskable_id' => $company->id,
                        'risk_level' => $riskLevel,
                        'assessment' => fake()->paragraph(rand(2, 4)),
                        'mitigation' => fake()->optional(0.7)->paragraph(rand(1, 3)),
                        'assessed_by' => $users->random()->id,
                        'assessment_date' => $assessmentDate,
                        'review_date' => $reviewDate,
                        'created_at' => $assessmentDate,
                        'updated_at' => $assessmentDate,
                    ]);
                } catch (\Exception $e) {
                    // Skip on any constraint errors
                    continue;
                }
            }
        }

        // Create risk assessments for persons
        if ($persons->isNotEmpty()) {
            foreach ($persons->random(min(25, $persons->count())) as $person) {
                try {
                    $riskLevel = fake()->randomElement(['low', 'medium', 'high']);
                    $assessmentDate = fake()->dateTimeBetween('-1 year', 'now');
                    $reviewDate = fake()->dateTimeBetween($assessmentDate, '+6 months');
                    
                    EntityRisk::create([
                        'riskable_type' => Person::class,
                        'riskable_id' => $person->id,
                        'risk_level' => $riskLevel,
                        'assessment' => fake()->paragraph(rand(2, 4)),
                        'mitigation' => fake()->optional(0.7)->paragraph(rand(1, 3)),
                        'assessed_by' => $users->random()->id,
                        'assessment_date' => $assessmentDate,
                        'review_date' => $reviewDate,
                        'created_at' => $assessmentDate,
                        'updated_at' => $assessmentDate,
                    ]);
                } catch (\Exception $e) {
                    // Skip on any constraint errors
                    continue;
                }
            }
        }
    }
}

