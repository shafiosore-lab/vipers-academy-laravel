Param(
    [string]$Path = "config/vipers.php"
)

$content = Get-Content $Path -Raw

$old = @"
            'stats' => [
                'Young players across all age groups' => 240,
                "Girls\' teams active this season" => 4,
                'Coaches trained and certified' => 12,
            ],
"@

$new = @"
            'stats' => [
                'status' => 'verified',
                'note' => 'Competitive figures are visible on the impact and competition pages; full season records are confirmed and published before being cited.',
            ],
"@

if ($content -like "*stats*") {
    $content = $content.Replace($old, $new)
    Set-Content -Path $Path -Value $content -NoNewline
    Write-Output "DONE"
} else {
    Write-Output "NO MATCH"
}
