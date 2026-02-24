<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ExpenseCategory::getDefaultCategories();

        // Define category groups
        $groups = [
            'player_wages' => 'Personnel Costs',
            'coach_salaries' => 'Personnel Costs',
            'equipment' => 'Operations',
            'travel' => 'Operations',
            'match_day' => 'Match Operations',
            'training_facility' => 'Facilities',
            'tournament_fees' => 'Competition',
            'medical' => 'Player Welfare',
            'merchandise' => 'Commercial',
            'marketing' => 'Commercial',
            'administrative' => 'Overhead',
            'utilities' => 'Overhead',
            'insurance' => 'Risk Management',
            'legal' => 'Professional Services',
            'youth_development' => 'Development',
            'facility_rentals' => 'Facilities',
            'other' => 'Miscellaneous',
        ];

        foreach ($categories as $category) {
            $category['is_system'] = true;
            $category['group_name'] = $groups[$category['slug']] ?? 'Miscellaneous';

            ExpenseCategory::updateOrCreate(
                ['slug' => $category['slug'], 'organization_id' => null],
                $category
            );
        }
    }
}
