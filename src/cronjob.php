<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

$yaml = Yaml::parseFile(__DIR__ . '/../config/cron.yaml');
$cronJobs = $yaml['cron_jobs'];

$cronfile = "/etc/cron.d/generated_cron";

$content = '';

foreach ($cronJobs as $job) {
    $content .= "{$job['schedule']} {$job['command']}\n";
}

file_put_contents($cronfile, $content);

chmod($cronfile, 0644);
