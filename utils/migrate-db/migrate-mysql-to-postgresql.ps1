<#
.Description
Migrates DB from MySQl to PostgreSQL
#> 

[CmdletBinding()]
param(
    [Parameter(Position = 0, Mandatory = $true)]
    [String]$mysql_pass,
    [Parameter(Position = 1, Mandatory = $true)]
    [String]$postgre_pass
)

. $PSScriptRoot\pgloader.ps1

$source = "mysql://kogda_remote_access:$mysql_pass@barbados.handyhost.ru/db_kogda_1"
$dest = "pgsql://kogda-igra-dev:$postgre_pass@rc1b-ms1mxukwh0b87hpg.mdb.yandexcloud.net:6432/kogda-igra-dev?sslmode=allow"

migrate $source $dest