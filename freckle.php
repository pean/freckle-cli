<?
require_once('inc/config.php');
require_once('inc/functions.php');
require_once('inc/freckle_api.php');

//var_dump($argv);
//exit;

$project =  strtolower($argv[1]);
$mins = strtolower($argv[2]);
$hours = m2h($mins);
$comment = $argv[3];
$date = date('Y-m-d',time());
$user = FRECKLE_USER;

$p = getProjects();

foreach($p as $ps) {
    if(strtolower($ps['name']) == $project) {
        //print_r($ps);
        $pid = $ps['id'];
        break;
    }
    /*
    foreach($projects as $k => $v) {
        if($k == 'name' && strtolower($v) == $project) {
            echo "KEY: $k => $v\n";
            echo print_r($projects)."\n";
            echo $projects['id']."\n";
            break;
        }
    }
     */
}

// echo $pid."\n";
// echo $hours."\n";
// echo $comment."\n";
// echo $date."\n";

if($pid && $hours && $comment && $date) {
    $res =  logEntry($user,$pid,$hours,$comment,$date);
    echo "OK\n";
    exit;
} else {
    echo "Missing parameters\n";
    exit;
}