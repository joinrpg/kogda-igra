<#
.Description
Migrates DB from SOURCE to DEST
#> 

function migrate([String]$source_conn_string, [String]$dest_conn_string)
{

    if ($source_conn_string.StartsWith("mysql"))
    {
        $cast_command = @"
        alter schema 'db_kogda_1' rename to 'public'
        
        CAST type tinyint to smallint drop typemod,
            column ki_game_date.time to smallint drop typemod
"@;
    }
    else {
        $cast_command = "";
    }

@"
load database
    from $source_conn_string
    into $dest_conn_string

    WITH include drop, create tables, no truncate, create indexes, reset sequences, foreign keys

    $cast_command;

"@ >migrate.load
    
    pgloader --no-ssl-cert-verification --on-error-stop --verbose /tmp/cmd/migrate.load
    Remove-Item .\migrate.load
}

function pgloader {
    $cur_dir = (Get-Item .).FullName
    docker run --rm --name pgloader `
        --mount type=bind,source=$cur_dir,target=/tmp/cmd `
        dimitri/pgloader:latest `
        pgloader $args
}
