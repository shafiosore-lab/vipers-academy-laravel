<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Player;
use Illuminate\Support\Facades\File;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure the players directory exists
        $playersPath = public_path('assets/img/players');

        if (!File::exists($playersPath)) {
            File::makeDirectory($playersPath, 0755, true);
            $this->command->info('✓ Created directory: public/assets/img/players/');
        }

        $this->command->info('🌟 Adding 6 Demo Players...');

        // Demo Player 1: Senior Striker
        Player::create([
            'first_name' => 'John',
            'last_name' => 'Ochieng',
            'full_name' => 'John Ochieng',
            'position' => 'striker',
            'age' => 19,
            'image' => 'john-ochieng-senior-striker-19.jpg',
            'bio' => 'Outstanding striker with excellent finishing skills.',
            'category' => 'senior',
            'status' => 'active',
        ]);

        // Demo Player 2: Under-17 Midfielder
        Player::create([
            'first_name' => 'Sarah',
            'last_name' => 'Wanjiku',
            'full_name' => 'Sarah Wanjiku',
            'position' => 'midfielder',
            'age' => 17,
            'image' => 'sarah-wanjiku-under-17-midfielder-17.jpg',
            'bio' => 'Talented attacking midfielder with excellent vision.',
            'category' => 'u17',
            'status' => 'active',
        ]);

        // Demo Player 3: Under-15 Goalkeeper
        Player::create([
            'first_name' => 'Michael',
            'last_name' => 'Kiprop',
            'full_name' => 'Michael Kiprop',
            'position' => 'goalkeeper',
            'age' => 15,
            'image' => 'michael-kiprop-under-15-goalkeeper-15.jpg',
            'bio' => 'Exceptional shot-stopping ability.',
            'category' => 'u15',
            'status' => 'active',
        ]);

        // Demo Player 4: Under-13 Defender
        Player::create([
            'first_name' => 'Grace',
            'last_name' => 'Achieng',
            'full_name' => 'Grace Achieng',
            'position' => 'defender',
            'age' => 13,
            'image' => 'grace-achieng-under-13-defender-13.jpg',
            'bio' => 'Young defender with great potential.',
            'category' => 'u13',
            'status' => 'active',
        ]);

        // Demo Player 5: Senior Midfielder
        Player::create([
            'first_name' => 'David',
            'last_name' => 'Mwangi',
            'full_name' => 'David Mwangi',
            'position' => 'midfielder',
            'age' => 20,
            'image' => 'david-mwangi-senior-midfielder-20.jpg',
            'bio' => 'Captain of senior team with professional contract.',
            'category' => 'senior',
            'status' => 'active',
        ]);

        // Demo Player 6: Under-17 Striker
        Player::create([
            'first_name' => 'Joyce',
            'last_name' => 'Njeri',
            'full_name' => 'Joyce Njeri',
            'position' => 'striker',
            'age' => 17,
            'image' => 'joyce-njeri-under-17-striker-17.jpg',
            'bio' => 'Top scorer of Under-17 team.',
            'category' => 'u17',
            'status' => 'active',
        ]);

        $this->command->info('✅ Successfully added 6 demo players!');
        $this->command->info('');
        $this->command->info('📊 Demo Players Added:');
        $this->command->info('  1. John Ochieng (Senior Striker, 19)');
        $this->command->info('  2. Sarah Wanjiku (Under-17 Midfielder, 17)');
        $this->command->info('  3. Michael Kiprop (Under-15 Goalkeeper, 15)');
        $this->command->info('  4. Grace Achieng (Under-13 Defender, 13)');
        $this->command->info('  5. David Mwangi (Senior Midfielder, 20)');
        $this->command->info('  6. Joyce Njeri (Under-17 Striker, 17)');
        $this->command->info('');
        $this->command->info('🎯 Player Distribution:');
        $this->command->info('  • 2 Senior Players');
        $this->command->info('  • 2 Under-17 Players');
        $this->command->info('  • 1 Under-15 Player');
        $this->command->info('  • 1 Under-13 Player');
        $this->command->info('  • Mix of positions: Striker, Midfielder, Goalkeeper, Defender');
        $this->command->info('');
        $this->command->info('📍 Visit: http://127.0.0.1:8000/players to view the demo players');
        $this->command->info('');
        $this->command->info('════════════════════════════════════════════════');
        $this->command->info('          Demo Players Successfully Added!       ');
        $this->command->info('════════════════════════════════════════════════');
    }
}
