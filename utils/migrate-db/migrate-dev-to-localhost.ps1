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

$source = "pgsql://kogda-dev:$postgre_pass@rc1b-1omkout6a9ifyold.mdb.yandexcloud.net:6432/kogda-dev?sslmode=allow"
$dest = "pgsql://kogdauser:kogdapass@host.docker.internal:7432/kogdaigra?sslmode=disable"

migrate $source $dest