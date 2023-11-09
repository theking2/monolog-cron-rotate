<?php declare(strict_types=1);

require './vendor/autoload.php';
session_start();

require './CronRotatingFileHandler.php';

use Monolog\Level;
/* setup log */
$log = new Monolog\Logger( 'log' );
$rotateSettings['cronExpression'] = '*/1 * * * *';
$rotateSettings['maxFiles'] = 10;
$rotateSettings['minSize'] = 120;
$rotateSettings['compress'] = true;
$log->pushHandler( new Monolog\Handler\CronRotatingFileHandler( 'file.log', $rotateSettings, Level::Info ) );

$rotateSettings['cronExpression'] = '*/5 * * * *';
$rotateSettings['compress'] = false;
$log->pushHandler( new Monolog\Handler\CronRotatingFileHandler( 'error.log', $rotateSettings, Level::Debug,true ) );
$log->pushProcessor( function ($record) {
  $record['extra']['ip']        = $_SERVER['REMOTE_ADDR'] ?? '';
  $record['extra']['sessionId'] = substr( session_id(), 0, 8 );
  return $record;
} );

$log->info( 'Hello World!' );
$log->debug( 'Debug' );