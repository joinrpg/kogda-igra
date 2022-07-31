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

function pgloader {
    $cur_dir = (Get-Item .).FullName
    docker run --rm --name pgloader `
        --mount type=bind,source=$cur_dir,target=/tmp/cmd `
        dimitri/pgloader:latest `
        pgloader $args
}

@"
load database
    from mysql://kogda_remote_access:$mysql_pass@barbados.handyhost.ru/db_kogda_1
    into pgsql://kogda-igra-dev:$postgre_pass@rc1b-ms1mxukwh0b87hpg.mdb.yandexcloud.net:6432/kogda-igra-dev?sslmode=allow

 WITH include drop, create tables, no truncate, create indexes, reset sequences, foreign keys

 CAST type tinyint to smallint drop typemod;

"@ >migrate.load

pgloader --no-ssl-cert-verification --on-error-stop --verbose /tmp/cmd/migrate.load
Remove-Item .\migrate.load