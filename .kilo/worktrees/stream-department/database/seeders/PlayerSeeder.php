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
            'name' => 'John Ochieng',
            'position' => 'striker',
            'age' => 19,
            'photo' => 'john-ochieng-senior-striker-19.jpg',
            'bio' => 'Outstanding striker with excellent finishing skills.',
            'program_id' => 1,
        ]);

        // Demo Player 2: Under-17 Midfielder
        Player::create([
            'name' => 'Sarah Wanjiku',
            'position' => 'midfielder',
            'age' => 17,
            'photo' => 'sarah-wanjiku-under-17-midfielder-17.jpg',
            'bio' => 'Talented attacking midfielder with excellent vision.',
            'program_id' => 1,
        ]);

        // Demo Player 3: Under-15 Goalkeeper
        Player::create([
            'name' => 'Michael Kiprop',
            'position' => 'goalkeeper',
            'age' => 15,
            'photo' => 'michael-kiprop-under-15-goalkeeper-15.jpg',
            'bio' => 'Exceptional shot-stopping ability.',
            'program_id' => 1,
        ]);

        // Demo Player 4: Under-13 Defender
        Player::create([
            'name' => 'Grace Achieng',
            'position' => 'defender',
            'age' => 13,
            'photo' => 'grace-achieng-under-13-defender-13.jpg',
            'bio' => 'Young defender with great potential.',
            'program_id' => 1,
        ]);

        // Demo Player 5: Senior Midfielder
        Player::create([
            'name' => 'David Mwangi',
            'position' => 'midfielder',
            'age' => 20,
            'photo' => 'david-mwangi-senior-midfielder-20.jpg',
            'bio' => 'Captain of senior team with professional contract.',
            'program_id' => 1,
        ]);

        // Demo Player 6: Under-17 Striker
        Player::create([
            'name' => 'Joyce Njeri',
            'position' => 'striker',
            'age' => 17,
            'photo' => 'joyce-njeri-under-17-striker-17.jpg',
            'bio' => 'Top scorer of Under-17 team.',
            'program_id' => 1,
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
