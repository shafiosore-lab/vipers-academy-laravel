Param([string]$Path = "config/vipers.php")
$content = Get-Content $Path -Raw

$old = "            'activities' => [`r
                'Youth leadership cohort' => 'placeholder',`r
                'Community dialogue forums' => 'placeholder',`r
                'Conflict mediation training' => 'placeholder',`r
                'Inclusion workshops' => 'placeholder',`r
            ],"@
$replacement = "            'activities' => [
                'Youth leadership cohort' => 'Training and mentoring for young team leaders, captains and peer educators',
                'Community dialogue forums' => 'Facilitated forums on conflict prevention, inclusion and community cohesion',
                'Conflict mediation training' => 'Workshops on non-violent conflict resolution and responsible competition',
                'Inclusion workshops' => 'Sessions on gender inclusion, disability inclusion and belonging',
            ],"

if ($content -like "*Youth leadership cohort*") {
    $content = $content -replace [regex]::Escape($old), $replacement
    Set-Content $Path -Value $content -NoNewline
    Write-Output "done"
} else {
    Write-Output "no match"
}
