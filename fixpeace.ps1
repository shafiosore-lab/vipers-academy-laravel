Param([string]$Path = "config/vipers.php")
$content = Get-Content $Path -Raw
$pattern = "            'activities' => \(\r?\n +'Youth leadership cohort'[^]]*?\)\r?\n +'partnership_opportunities'"
$replacement = "            'activities' => [
                'Youth leadership cohort' => 'Training and mentoring for young team leaders, captains and peer educators',
                'Community dialogue forums' => 'Facilitated forums on conflict prevention, inclusion and community cohesion',
                'Conflict mediation training' => 'Workshops on non-violent conflict resolution and responsible competition',
                'Inclusion workshops' => 'Sessions on gender inclusion, disability inclusion and belonging',
            ],
            'partnership_opportunities'"
$content = [regex]::Replace($content, $pattern, $replacement)
Set-Content $Path -Value $content -NoNewline
Write-Output "done"
