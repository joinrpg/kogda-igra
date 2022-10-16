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
$dest = "pgsql://kogda-prod:$postgre_pass@rc1b-1omkout6a9ifyold.mdb.yandexcloud.net:6432/kogda-prod?sslmode=allow"

migrate $source $dest