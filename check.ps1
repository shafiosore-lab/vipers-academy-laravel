Param([string]$Path = "config/vipers.php")
$lines = Get-Content $Path
$l = $lines[387]
$i = 0
$chars = @()
foreach ($c in $l.ToCharArray()) {
    if ([System.Char]::IsWhiteSpace($c) -or [System.Char]::IsPunctuation($c) -or $c -eq "'") {
        $chars += "Idx=$i '{0}' code={1} hex={2}" -f $c, [int][uint16]$c, [Convert]::ToString([uint16]$c, 16)
    }
    $i++
}
$chars | Select-Object -Unique | Sort-Object
