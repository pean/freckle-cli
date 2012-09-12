<?
require_once('inc/config.php');
require_once('inc/functions.php');
require_once('inc/freckle_api.php');

//var_dump($argv);
//exit;

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
    echo "Could not find project\n";
    exit;
}

if($pid && $hours && $comment && $date) {
    $res =  logEntry($user,$pid,$hours,$comment,$date);
    echo "Logged $hours hours for $ps[name]\n$comment";
    exit;
} else {
    echo "Missing parameters\n";
    exit;
}