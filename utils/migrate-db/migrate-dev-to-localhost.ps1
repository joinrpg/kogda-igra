<#
.Description
Migrates DB from DEV PostgreSQL to localhost
#> 

[CmdletBinding()]
param(
    [Parameter(Position = 0, Mandatory = $true)]
    [String]$postgre_pass
)

. $PSScriptRoot\pgloader.ps1

$source = "pgsql://kogda-igra-dev:$postgre_pass@rc1b-ms1mxukwh0b87hpg.mdb.yandexcloud.net:6432/kogda-igra-dev?sslmode=allow"
$dest = "pgsql://kogdauser:kogdapass@host.docker.internal/kogdaigra?sslmode=disable"

migrate $source $dest