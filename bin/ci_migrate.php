<?php

$system_folder = '../system';
$application_folder = '../app';

// relative to the app folder
$migrations_path = 'migrations';

// no need to touch anything below here
require_once( 'lib/optparser.php' );
include_once( 'ci_init.php');
include_once( $application_folder . '/helpers/migrator_helper.php');
// re-path this
$migrations_path = $application_folder . '/' . $migrations_path;

$help =<<<EOF
CI Database Migration Tool
---
usage: migrate [OPTIONS]
EOF;

$cli = new OptParser( $help );
$cli->add_option( 'd', 'dump', 'Dump the current database schema to XML' );
$cli->add_option( 'e', 'env', 'Use environment ARG', true );
$cli->add_option( 'f', 'force', 'Force re-run all migrations (DROP)' );
$cli->add_option( 'h', 'help', 'Get some help dude' );
$cli->add_option( 'v', 'version', 'Run or undo migration to version ARG', true );
$cli->parse( $argv );
$args = $cli->args();


$active_group = 'default';
$db = DB( $active_group );
$migrator = new DBMigrator( $migrations_path, $db );

if( $cli->used_opt('force')) {
  $migrator->reset();
}
if( $cli->used_opt('dump')) {
	$migrator->dump();
} else {
	$migrator->migrate($cli->opt_value('version'));
}
$db->close();

?>