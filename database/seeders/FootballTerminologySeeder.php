<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FootballTerminology;

class FootballTerminologySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $terminologies = [
            // Scoring Terms
            ['term' => 'hat-trick', 'category' => 'scoring', 'definition' => 'Three goals scored by one player in a single match', 'example' => 'Ronaldo scored a hat-trick in the final.'],
            ['term' => 'brace', 'category' => 'scoring', 'definition' => 'Two goals scored by one player in a single match', 'example' => 'The striker scored a brace before halftime.'],
            ['term' => 'opener', 'category' => 'scoring', 'definition' => 'The first goal scored in a match', 'example' => 'The opener came in the 15th minute.'],
            ['term' => 'equalizer', 'category' => 'scoring', 'definition' => 'A goal that brings the scoring team level with the opponent', 'example' => 'The equalizer came from a corner kick.'],
            ['term' => 'winner', 'category' => 'scoring', 'definition' => 'The final goal that determines the winning team', 'example' => 'The winner was scored in the 89th minute.'],
            ['term' => 'own goal', 'category' => 'scoring', 'definition' => 'A goal scored by a player against their own team', 'example' => 'The match ended with an own goal.'],
            ['term' => 'penalty', 'category' => 'scoring', 'definition' => 'A direct free kick awarded for a foul in the penalty area', 'example' => 'The referee awarded a penalty in the 78th minute.'],
            ['term' => 'free kick', 'category' => 'scoring', 'definition' => 'An unhindered kick awarded for a foul or rule violation', 'example' => 'The free kick was taken from 25 yards out.'],
            ['term' => 'direct free kick', 'category' => 'scoring', 'definition' => 'A free kick that can score directly without touching another player', 'example' => 'He scored from a direct free kick.'],
            ['term' => 'indirect free kick', 'category' => 'scoring', 'definition' => 'A free kick that must touch another player before entering the goal', 'example' => 'The indirect free kick required a pass.'],

            // Passing Terms
            ['term' => 'through ball', 'category' => 'passing', 'definition' => 'A pass played between defenders to reach an attacker', 'example' => 'The through ball split the defense.'],
            ['term' => 'key pass', 'category' => 'passing', 'definition' => 'A pass that leads directly to a shot on goal', 'example' => 'He delivered three key passes in the match.'],
            ['term' => 'assist', 'category' => 'passing', 'definition' => 'The final pass leading to a goal', 'example' => 'His assist set up the winning goal.'],
            ['term' => 'cross', 'category' => 'passing', 'definition' => 'A pass delivered from the wing into the penalty area', 'example' => 'The cross found the striker at the far post.'],
            ['term' => 'one-two', 'category' => 'passing', 'definition' => 'A passing combination where two players exchange passes', 'example' => 'The one-two played opened up the defense.'],
            ['term' => 'give and go', 'category' => 'passing', 'definition' => 'A passing combination where a player passes and then receives the ball back', 'example' => 'The give and go beat the defender.'],
            ['term' => 'wall pass', 'category' => 'passing', 'definition' => 'A one-two or give and go passing move', 'example' => 'The wall pass created space for the shot.'],

            // Defensive Terms
            ['term' => 'tackle', 'category' => 'defense', 'definition' => 'A challenge to dispossess an opponent', 'example' => 'He made five successful tackles.'],
            ['term' => 'slide tackle', 'category' => 'defense', 'definition' => 'A tackle made while sliding on the ground', 'example' => 'The slide tackle won possession.'],
            ['term' => 'interception', 'category' => 'defense', 'definition' => 'Anticipating and stopping a pass', 'example' => 'Her interception prevented the counterattack.'],
            ['term' => 'clearance', 'category' => 'defense', 'definition' => 'Kicking the ball away from the goal to relieve pressure', 'example' => 'The clearance went to the halfway line.'],
            ['term' => 'block', 'category' => 'defense', 'definition' => 'Stopping a shot with any part of the body', 'example' => 'His block saved a certain goal.'],
            ['term' => 'clean sheet', 'category' => 'defense', 'definition' => 'A match where the goalkeeper concedes no goals', 'example' => 'The team kept a clean sheet at home.'],
            ['term' => 'offside', 'category' => 'defense', 'definition' => 'Being in an illegal position when the ball is played', 'example' => 'The goal was disallowed for offside.'],
            ['term' => 'foul', 'category' => 'defense', 'definition' => 'An illegal action against an opponent', 'example' => 'He committed a foul in the penalty area.'],
            ['term' => 'yellow card', 'category' => 'defense', 'definition' => 'A warning given to a player for a caution', 'example' => 'The player received a yellow card for dissent.'],
            ['term' => 'red card', 'category' => 'defense', 'definition' => 'A dismissal from the match for serious foul play', 'example' => 'The red card reduced them to ten men.'],

            // Goalkeeper Terms
            ['term' => 'save', 'category' => 'goalkeeper', 'definition' => 'Stopping a shot from entering the goal', 'example' => 'The goalkeeper made six saves.'],
            ['term' => 'clean sheet', 'category' => 'goalkeeper', 'definition' => 'No goals conceded in a match', 'example' => 'The keeper kept a clean sheet.'],
            ['term' => 'penalty save', 'category' => 'goalkeeper', 'definition' => 'Stopping a penalty kick', 'example' => 'The penalty save was crucial.'],
            ['term' => 'distribution', 'category' => 'goalkeeper', 'definition' => 'Passing or throwing the ball after gaining possession', 'example' => 'His distribution started counterattacks.'],
            ['term' => 'claim', 'category' => 'goalkeeper', 'definition' => 'Catching or collecting a cross or through ball', 'example' => 'The claim relieved the pressure.'],
            ['term' => 'punch', 'category' => 'goalkeeper', 'definition' => 'Hitting the ball away with fists', 'example' => 'The punch cleared the danger.'],

            // General/Match Terms
            ['term' => 'formation', 'category' => 'general', 'definition' => 'The tactical arrangement of players on the field', 'example' => 'They played a 4-3-3 formation.'],
            ['term' => 'counterattack', 'category' => 'general', 'definition' => 'A rapid attack after winning the ball from the opponent', 'example' => 'The counterattack resulted in a goal.'],
            ['term' => 'possession', 'category' => 'general', 'definition' => 'Control of the ball by a team', 'example' => 'They had 65% possession.'],
            ['term' => 'set piece', 'category' => 'general', 'definition' => 'A structured play from a dead ball situation', 'example' => 'The set piece led to the opener.'],
            ['term' => 'corner kick', 'category' => 'general', 'definition' => 'A kick taken from the corner flag', 'example' => 'The corner kick was floated to the back post.'],
            ['term' => 'throw-in', 'category' => 'general', 'definition' => 'Method of restarting after the ball goes out of play', 'example' => 'The throw-in was taken quickly.'],
            ['term' => 'halftime', 'category' => 'general', 'definition' => 'The break between the two halves of a match', 'example' => 'The team made adjustments at halftime.'],
            ['term' => 'full time', 'category' => 'general', 'definition' => 'The end of the match', 'example' => 'Full time: 2-1.'],
            ['term' => 'stoppage time', 'category' => 'general', 'definition' => 'Extra time added to compensate for delays', 'example' => 'The goal came in stoppage time.'],
            ['term' => 'extra time', 'category' => 'general', 'definition' => 'Additional playing time in knockout competitions', 'example' => 'The match went to extra time.'],
            ['term' => 'man of the match', 'category' => 'general', 'definition' => 'The best player in a match as awarded', 'example' => 'He was named man of the match.'],
            ['term' => 'hat-trick', 'category' => 'general', 'definition' => 'Three goals by one player (same as scoring)', 'example' => 'He scored a hat-trick.'],
            ['term' => 'brace', 'category' => 'general', 'definition' => 'Two goals by one player (same as scoring)', 'example' => 'He scored a brace.'],
            ['term' => 'dribble', 'category' => 'general', 'definition' => 'Running with the ball while maintaining control', 'example' => 'His dribble beat three defenders.'],
            ['term' => 'header', 'category' => 'general', 'definition' => 'Striking the ball with the head', 'example' => 'The header went into the top corner.'],
            ['term' => 'volley', 'category' => 'general', 'definition' => 'Striking the ball without it touching the ground', 'example' => 'The volley was unstoppable.'],
            ['term' => 'bicycle kick', 'category' => 'general', 'definition' => 'An overhead kick while facing away from goal', 'example' => 'The bicycle kick was spectacular.'],
            ['term' => 'nutmeg', 'category' => 'general', 'definition' => 'Dribbling the ball between an opponents legs', 'example' => 'He nutmegged the defender.'],
            ['term' => 'rabona', 'category' => 'general', 'definition' => 'A trick shot kicking the ball behind the standing leg', 'example' => 'The rabona surprised everyone.'],
            ['term' => 'turn', 'category' => 'general', 'definition' => 'Changing direction while controlling the ball', 'example' => 'The turn left the defender stranded.'],
            ['term' => 'step over', 'category' => 'general', 'definition' => 'A feint where the player swings the ball over', 'example' => 'The step over confused the defender.'],
            ['term' => 'fake shot', 'category' => 'general', 'definition' => 'A feint pretending to shoot to beat a defender', 'example' => 'The fake shot created space for the pass.'],
        ];

        foreach ($terminologies as $term) {
            FootballTerminology::updateOrCreate(
                ['term' => $term['term'], 'category' => $term['category']],
                $term
            );
        }
    }
}
