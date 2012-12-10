<?php
require_once('inc/config.php');
require_once('inc/functions.php');
require_once('inc/freckle_api.php');

function flog($str) {
    file_put_contents(BASE_PATH."freckle.log",$str,FILE_APPEND);
    file_put_contents(BASE_PATH."msg.log",$str);
}

flog("Script runs!\n");
//flog(var_dump($argv));

$oldstyle = 0;
if($oldstyle) {
    $project =  strtolower($argv[1]);
    $mins = strtolower($argv[2]);
    $hours = m2h($mins);
    $comment =  join(array_splice($argv,3,count($argv)),' ');
    $date = date('Y-m-d',time());
    $user = FRECKLE_USER;
    print_r($argv);
}

$args = join(array_splice($argv,1,count($argv)),' ');

$regexp = "/^(.*)\s([\d]{1,2}\:[\d]{2})\s(.*)$/";

preg_match($regexp,$args,$matches);
// echo "ARGS: ".$args."\n";
// echo "MATCHES: \n";
// print_r($matches);

$project =  strtolower($matches[1]);
$hours = strtolower($matches[2]);
$comment = $matches[3];
$date = date('Y-m-d',time());
$user = FRECKLE_USER;

/*
echo "Date: ".$date."\n";
echo "User: ".$user."\n";
echo "Project: ".$project."\n";
echo "Hours: ".$mins."\n";
echo "Comment: ".$comment."\n";
exit;
 */

$p = getProjects();

foreach($p as $ps) {
    if(strtolower($ps['name']) == $project) {
        //print_r($ps);
        $pid = $ps['id'];
        break;
    }
}

if(!$pid) {
    $output =  "Could not find project $project…\n";
    flog($output);
    echo $output;
    exit;
}

if($pid && $hours && $comment && $date) {
    $res =  logEntry($user,$pid,$hours,$comment,$date);
    $output =  "Logged $hours hours for $ps[name]\n$comment";
    flog($output);
    echo $output;
    exit;
} else {
    $output =  "Missing parameters\n";
    flog($output);
    echo $output;
    exit;
}