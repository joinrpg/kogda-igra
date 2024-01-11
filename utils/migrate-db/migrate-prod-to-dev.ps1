<#
.Description
Migrates DB from DEV PostgreSQL to localhost
#> 

[CmdletBinding()]
param(
    [Parameter(Position = 0, Mandatory = $true)]
    [String]$postgre_pass_source,
    [Parameter(Position = 1, Mandatory = $true)]
    [String]$postgre_pass_dest
)

. $PSScriptRoot\pgloader.ps1

$source = "pgsql://kogda-prod:$postgre_pass_source@rc1b-1omkout6a9ifyold.mdb.yandexcloud.net:6432/kogda-prod?sslmode=allow"
$dest   = "pgsql://kogda-igra-dev:$postgre_pass_dest@rc1b-ms1mxukwh0b87hpg.mdb.yandexcloud.net:6432/kogda-igra-dev?sslmode=allow"


migrate $source $dest