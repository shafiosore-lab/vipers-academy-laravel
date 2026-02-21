<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FootballTerminology extends Model
{
    protected $fillable = [
        'term',
        'category',
        'definition',
        'example',
    ];

    /**
     * List of offensive words to filter out for professionalism
     */
    protected static $offensiveWords = [
        'stupid', 'idiot', 'dumb', 'worst', 'terrible', 'horrible',
        'useless', 'pathetic', 'disgrace', 'shame', 'hate', 'looser',
        'loser', 'suck', 'sucks', 'trash', 'garbage', 'shit', 'damn',
        'fool', 'moron', 'imbecile', 'retard', 'retarded', 'crybaby',
    ];

    /**
     * Professional replacements for casual/mild terms
     */
    protected static $professionalReplacements = [
        'played bad' => 'had a challenging performance',
        'played poorly' => 'delivered below expectations',
        'played good' => 'delivered a solid performance',
        'played well' => 'delivered an excellent performance',
        'didnt try' => 'showed limited engagement',
        'did not try' => 'showed limited engagement',
        'lazy' => 'could demonstrate more initiative',
        'no effort' => 'could show greater commitment',
        'always late' => 'time management requires attention',
        'late to training' => 'punctuality needs improvement',
        'not trying' => 'engagement level could increase',
        'didnt care' => 'could show more enthusiasm',
        'no interest' => 'could display greater interest',
        'slow' => 'pace could be improved',
        'weak' => 'strength development needed',
        'soft' => 'toughness could be developed',
    ];

    /**
     * Context enhancers - add professional context to stats
     */
    protected static $contextEnhancers = [
        'goal' => 'scored a goal, demonstrating offensive capability',
        'goals' => 'scored goals, showcasing attacking prowess',
        'assist' => 'provided an assist, creating scoring opportunities',
        'assists' => 'provided assists, demonstrating playmaking abilities',
        'pass' => 'completed a pass, showing good ball distribution',
        'passes' => 'completed passes, demonstrating ball retention skills',
        'tackle' => 'won a tackle, displaying defensive determination',
        'tackles' => 'won tackles, showing defensive awareness',
        'save' => 'made a save, demonstrating goalkeeping reflexes',
        'saves' => 'made saves, showcasing shot-stopping ability',
        'header' => 'headed the ball, showing aerial ability',
        'cross' => 'delivered a cross, providing width to the attack',
        'dribble' => 'dribbled past opponents, showing ball control',
        'interception' => 'made an interception, displaying positional awareness',
        'shot' => 'took a shot, showing attacking intent',
    ];

    /**
     * Football terminology dictionary with proper capitalization
     */
    protected static $terminology = [
        'hat-trick' => 'Hat-trick',
        'brace' => 'Brace',
        'opener' => 'Opener',
        'equalizer' => 'Equalizer',
        'winner' => 'Match-winner',
        'own goal' => 'Own goal',
        'penalty' => 'Penalty kick',
        'free kick' => 'Direct free kick',
        'through ball' => 'Through ball',
        'key pass' => 'Key pass',
        'assist' => 'Assist',
        'cross' => 'Cross',
        'one-two' => 'One-two combination',
        'tackle' => 'Tackle',
        'interception' => 'Interception',
        'clearance' => 'Clearance',
        'block' => 'Block',
        'clean sheet' => 'Clean sheet',
        'offside' => 'Offside position',
        'foul' => 'Foul',
        'save' => 'Save',
        'dribble' => 'Dribble',
        'header' => 'Header',
        'volley' => 'Volley',
        'counterattack' => 'Counterattack',
        'possession' => 'Ball possession',
        'formation' => 'Tactical formation',
        'set piece' => 'Set piece',
        'corner' => 'Corner kick',
    ];

    /**
     * Word extenders - expand short descriptions
     */
    protected static $wordExtenders = [
        'good' => 'demonstrated good quality and consistency',
        'great' => 'exhibited outstanding performance and dedication',
        'nice' => 'displayed pleasing technical ability',
        'decent' => 'showed reasonable competence',
        'solid' => 'delivered a reliable and steady performance',
        'excellent' => 'produced an outstanding display of skill',
        'amazing' => 'delivered a remarkable and impressive showing',
        'fast' => 'displayed excellent pace and acceleration',
        'quick' => 'showed rapid movement and reaction time',
        'strong' => 'demonstrated physical strength and power',
    ];

    /**
     * Get all terms grouped by category.
     */
    public static function getGroupedByCategory()
    {
        try {
            return self::orderBy('category')->orderBy('term')->get()->groupBy('category');
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    /**
     * Search terms by keyword.
     */
    public static function search($keyword)
    {
        try {
            return self::where('term', 'like', "%{$keyword}%")
                       ->orWhere('definition', 'like', "%{$keyword}%")
                       ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    /**
     * Filter offensive words from text.
     */
    public static function filterOffensive($text)
    {
        $filteredText = $text;
        foreach (self::$offensiveWords as $word) {
            $pattern = '/\b' . preg_quote($word, '/') . '\b/i';
            $filteredText = preg_replace($pattern, '[professional term]', $filteredText);
        }
        return $filteredText;
    }

    /**
     * Replace casual terms with professional ones.
     */
    public static function professionalize($text)
    {
        $professionalText = ucfirst(strtolower($text));

        foreach (self::$professionalReplacements as $casual => $professional) {
            $pattern = '/\b' . preg_quote($casual, '/') . '\b/i';
            $professionalText = preg_replace($pattern, $professional, $professionalText);
        }

        return $professionalText;
    }

    /**
     * Add context to statistics mentioned in text.
     */
    public static function addContext($text)
    {
        $enhancedText = $text;

        foreach (self::$contextEnhancers as $term => $context) {
            $pattern = '/\b(' . preg_quote($term, '/') . ')\b/i';
            $enhancedText = preg_replace($pattern, '$1 (' . $context . ')', $enhancedText);
        }

        return $enhancedText;
    }

    /**
     * Extend short words to more descriptive phrases.
     */
    public static function extendWords($text)
    {
        $extendedText = $text;

        foreach (self::$wordExtenders as $word => $extension) {
            $pattern = '/\b' . preg_quote($word, '/') . '\b/i';
            $extendedText = preg_replace($pattern, $extension, $extendedText);
        }

        return $extendedText;
    }

    /**
     * Expand text to reach target word count (90-100 words).
     */
    public static function expandToTargetLength($text, $targetMin = 90, $targetMax = 100)
    {
        $wordCount = str_word_count($text);

        if ($wordCount >= $targetMin) {
            return $text;
        }

        // Add professional fillers to expand content
        $fillers = [
            ' This performance contributes positively to the team dynamics.',
            ' Such efforts are valuable for the overall team development.',
            ' The player showed commitment to continuous improvement.',
            ' This demonstrates potential for future growth.',
            ' The technical abilities were on display throughout.',
            ' Mental strength was evident in the approach.',
            ' Physical performance met professional standards.',
            ' Game understanding was clearly demonstrated.',
            ' Tactical awareness was shown in various situations.',
            ' The overall contribution was meaningful to the team.',
        ];

        // Calculate how many words we need
        $wordsNeeded = $targetMin - $wordCount;

        // Add fillers until we reach target
        $addedText = '';
        foreach ($fillers as $filler) {
            if (str_word_count($addedText) >= $wordsNeeded) break;
            $addedText .= $filler;
        }

        return $text . $addedText;
    }

    /**
     * Fix common grammar issues in match reports.
     */
    public static function fixGrammar($text)
    {
        $grammarFixes = [
            '/\s+/' => ' ',
            '/\bgoalz\b/i' => 'goals',
            '/\bplayz\b/i' => 'plays',
            '/\bskor\b/i' => 'score',
            '/\bskot\b/i' => 'shot',
            '/\bdefendr\b/i' => 'defender',
            '/\bgoalkeper\b/i' => 'goalkeeper',
            '/\bplayyer\b/i' => 'player',
            '/\bref\b/i' => 'referee',
            '/\s+is\s+(\d+)/' => ' $1',
            '/\s+was\s+(\d+)/' => ' $1',
            '/([a-z])\s+([A-Z])/' => '$1. $2',
        ];

        $fixedText = $text;
        foreach ($grammarFixes as $pattern => $replacement) {
            $fixedText = preg_replace($pattern, $replacement, $fixedText);
        }

        // Ensure proper sentence capitalization
        $fixedText = preg_replace_callback('/(^|[.!?]\s+)([a-z])/', function($matches) {
            return $matches[1] . strtoupper($matches[2]);
        }, $fixedText);

        return trim($fixedText);
    }

    /**
     * Add proper football terminology where needed.
     */
    public static function addTerminology($text)
    {
        $enhancedText = $text;

        foreach (self::$terminology as $term => $properTerm) {
            $pattern = '/\b' . preg_quote($term, '/') . '\b/i';
            $enhancedText = preg_replace($pattern, $properTerm, $enhancedText);
        }

        return $enhancedText;
    }

    /**
     * Full enhancement: filter offensive, professionalize, add terminology, add context, extend words, fix grammar.
     */
    public static function enhanceText($text)
    {
        // Step 1: Filter offensive words
        $enhancedText = self::filterOffensive($text);

        // Step 2: Professionalize casual language
        $enhancedText = self::professionalize($enhancedText);

        // Step 3: Add proper football terminology (capitalize properly)
        $enhancedText = self::addTerminology($enhancedText);

        // Step 4: Add context to statistics
        $enhancedText = self::addContext($enhancedText);

        // Step 5: Extend short words to more descriptive phrases
        $enhancedText = self::extendWords($enhancedText);

        // Step 6: Expand to target word count (90-100)
        $enhancedText = self::expandToTargetLength($enhancedText);

        // Step 7: Fix common grammar issues
        $enhancedText = self::fixGrammar($enhancedText);

        // Ensure first letter is capitalized
        $enhancedText = ucfirst($enhancedText);

        // Add period at end if missing
        if (!in_array(substr(trim($enhancedText), -1), ['.', '!', '?'])) {
            $enhancedText .= '.';
        }

        return $enhancedText;
    }
}
