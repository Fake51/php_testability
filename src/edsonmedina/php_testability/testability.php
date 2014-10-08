<?php
/**
 * php_testability
 * @author Edson Medina <edsonmedina@gmail.com>
 */
$autoloader_locations = array (
	__DIR__.'/../../autoload.php', 
	__DIR__.'/vendor/autoload.php', 
	__DIR__.'/../../../vendor/autoload.php'
);
foreach ($autoloader_locations as $file) {
    if (file_exists($file)) {
    	require_once $file;
    }
}

error_reporting (E_ALL); // that's how we roll

use edsonmedina\php_testability\FileIterator;
use edsonmedina\php_testability\Analyser;
use edsonmedina\php_testability\ReportData;
use edsonmedina\php_testability\HTMLReport;
use Commando\Command;


$start_ts  = microtime (TRUE);

// run
$data     = new ReportData ();
$analyser = new Analyser ($data);
$iterator = new FileIterator (PATH, $analyser);
$iterator->run ();

$report = new HTMLReport (); 
$report->generate ($data);

$total_time = number_format (microtime (TRUE) - $start_ts, 2);

echo "\nDone ({$total_time}s).\n\n";
echo $iterator->getProcessedFiles()." processed files.\n";
echo $iterator->getExcludedFiles()." excluded files.\n";
echo number_format (memory_get_peak_usage()/1024/1024, 2)." Mbytes used\n\n";