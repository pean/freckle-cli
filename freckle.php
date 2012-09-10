<?
require_once('inc/config.php');
require_once('inc/functions.php');
require_once('inc/freckle_api.php');

//var_dump($argv);
//exit;

$project =  strtolower($argv[1]);
$mins = strtolower($argv[2]);
$hours = m2h($mins);
// TODO: Parse all the following arguments to skip escaping param with ""
//$comment =  print_r(array_splice($argv,3,count($argv)),', ');
$comment =  join(array_splice($argv,3,count($argv)),' ');
$date = date('Y-m-d',time());
$user = FRECKLE_USER;

/*
echo $project."\n";
echo $mins."\n";
echo $house."\n";
echo $comment."\n";
echo $date."\n";
echo $user."\n";
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
    echo "Could not find project\n";
    exit;
}

if($pid && $hours && $comment && $date) {
    $res =  logEntry($user,$pid,$hours,$comment,$date);
    echo "OK\n";
    exit;
} else {
    echo "Missing parameters\n";
    exit;
}