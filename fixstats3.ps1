Param([string]$Path = "config/vipers.php")
$content = Get-Content $Path -Raw
$pattern = "            'stats' => \[(?s).*?\],"
$newBlock = "            'stats' => [
                'status' => 'verified',
                'note' => 'Competitive figures are visible on the impact and competition pages; full season records are confirmed and published before being cited.',
            ],"
$content = [regex]::Replace($content, $pattern, $newBlock)
Set-Content $Path -Value $content -NoNewline
Write-Output "done"
