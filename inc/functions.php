<?


function m2h($mins) {
	if ($mins < 0) {
		$min = Abs($mins);
	} else {
		$min = $mins;
	}
	$H = Floor($min / 60);
	$M = ($min - ($H * 60)) / 100;
	$hours = $H + $M;
	if ($mins < 0) {
		$hours = $hours * (-1);
	}
	$expl = explode(".", $hours);
	$H = $expl[0];
	if (empty($expl[1])) {
		$expl[1] = 00;
	}
	$M = $expl[1];
	if (strlen($M) < 2) {
		$M = $M . 0;
	}
	$hours = $H . ":" . $M;
	return $hours;
}



?>
