<#
.Description
Migrates DB from MySQl to LOCAL PostgreSQL
#> 

[CmdletBinding()]
param(
    [Parameter(Position = 0, Mandatory = $true)]
    [String]$mysql_pass
)

. $PSScriptRoot\pgloader.ps1

$source = "mysql://kogda_remote_access:$mysql_pass@barbados.handyhost.ru/db_kogda_1"
$dest = "pgsql://kogdauser:kogdapass@host.docker.internal/kogdaigra?sslmode=disable"

migrate $source $dest