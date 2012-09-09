<?

function getProjects() {

	$baseurl = FRECKLE_BASEURL;
	$token = FRECKLE_TOKEN;
    $url = $baseurl."projects.xml?token=".$token;
    $file = 'cache/projects.xml';

    if(file_exists($file)) {
        $data = file_get_contents($file);
    } else {

        $curl_handle = curl_init();
        curl_setopt($curl_handle,CURLOPT_URL,$url);
        curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
        $data = curl_exec($curl_handle);
        curl_close($curl_handle);

        /// CACHE DATA
        $res = file_put_contents($file, $data);
    }
    
    $array = array();
    $xml = simplexml_load_string($data);
    foreach($xml->children() as $child) {
        $e = array();
        foreach($child->children() as $v) {
            $e[$v->getName()] = utf8_decode($v);
        }
        array_push($array,$e);
    }
    
    $names = array();
    foreach($array as $k => $v) {
	    array_push($names,$v['name']);
    }
    sort($names);
    $sorted = $names;

    foreach($array as $k => $v) {
        foreach($names as $n => $name) {
            if($name == $v['name']) {
                $sorted[$n] = $array[$k];
            }
        }
    }
    $array = $sorted;

    
    //header('Content-type: text/plain');
    //sort($array);
    //print_r($array);
    //exit;
    return $array;

}

function getEntries($user_id,$from,$to = '',$projects = '',$tags = '') {

    $project_list =  getProjects();
    $p = array();
    foreach($project_list as $k => $v) {
	    $p["p".$v['id']] = $v['name'];
    }

    $baseurl = FRECKLE_BASEURL;
    $token = FRECKLE_TOKEN;
	  
    $url = $baseurl."entries.xml".
		   "?token=".$token.
		   "&search[people]=".$user_id;
	if($from) $url .= "&search[from]=".$from;
	if($to) $url .= "&search[to]=".$to;
	if($projects) $url .= "&search[projects]=".$projects;
	if($tags) $url .= "&search[tags]=".$tags;

    $curl_handle = curl_init();
    curl_setopt($curl_handle,CURLOPT_URL,$url);
    curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
    $data = curl_exec($curl_handle);
    curl_close($curl_handle);
    
    $array = array();
    $xml = simplexml_load_string($data);
    
    foreach($xml->children() as $child) {
        $e = array();
        foreach($child->children() as $v) {
            $e[$v->getName()] = utf8_decode($v);
            if($v->getName() == 'project-id') {
                $e['project_name'] = $p["p$v"];
            }
        }
        array_push($array,$e);
    }
	//header('Content-type: text/plain');
	//print($url);
	//print($data);
	//print_r($array);
	//exit;
    return $array;

}


function getUsers() {

    $baseurl = FRECKLE_BASEURL;
    $token = FRECKLE_TOKEN;
  
    $url = $baseurl."users.xml?token=".$token;

    $curl_handle = curl_init();
    curl_setopt($curl_handle,CURLOPT_URL,$url);
    curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
    $data = curl_exec($curl_handle);
    curl_close($curl_handle);
    
    $array = array();
    $xml = simplexml_load_string($data);
    
    foreach($xml->children() as $child) {
        $e = array();
        foreach($child->children() as $k => $v) {
            $e[$v->getName()] = (string)$v;
			//$e[$k] = $v[0];
        }
        array_push($array,$e);
    }
    return $array;

}

function logEntry($user,$project_id,$minutes,$description,$date) {

    $baseurl = FRECKLE_BASEURL;
    $token = FRECKLE_TOKEN;

    $url = $baseurl."entries.xml?token=".$token;

    //echo "$url\n\n";

    $xml = 
    '<?xml version="1.0" encoding="UTF-8"?>
        <entry>
            <minutes>'.$minutes.'</minutes>
            <user>'.$user.'</user>
            <project-id type="integer">'.$project_id.'</project-id>
            <description>'.utf8_encode($description).'</description>
            <date>'.$date.'</date>
        </entry>';

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt ($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}


?>