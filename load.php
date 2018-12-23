<?php
$base = "http://read.local/";

$dsn = 'mysql:host=localhost;dbname=reader';
$db = new PDO($dsn, 'username', 'password');

if(!isset($_GET['s'])) {
	$r['text'] = "You need to select a story.<br />";
	$_GET['s'] = "Select a story";
	$_GET['p'] = 0;
	$next = false;
	$prev = false;

	$q = $db->prepare("SELECT story FROM list");
	$q->execute();
	$re = $q->fetchAll(PDO::FETCH_ASSOC);

	foreach ($re as $key => $value) {
		$r['text'] .= "<a onclick=\"return loadStoryList('" . $value['story'] . "');\" href=\"" . $base . "read/" . $value['story'] . "\">" . $value['story'] . "</a><br />";
	}
} elseif(!isset($_GET['p'])) {
	$r['text'] = "Now select a page.<br />Or select a <a href=\"" . $base . "read\" onclick=\"return loadList();\">different story</a>.<div class=\"container\">";
	//$_GET['s'] = "Select a page";
	$_GET['p'] = 0;
	$next = false;
	$prev = false;

	$q = $db->prepare("SELECT chapter FROM stories WHERE story = ?");
	$q->execute(array($_GET['s']));
	$re = $q->fetchAll(PDO::FETCH_ASSOC);
	$i = 1;
	//var_dump($re);

	foreach ($re as $key => $value) {
		//print_r($value);
		if($i == 1) {
			$r['text'] .= "<div class=\"row\">";
		}
		$r['text'] .= "<div class=\"col-3 col-sm-3\"><a onclick=\"return loadMe('" . $_GET['s'] . "', '" . $value['chapter'] . "');\" href=\"" . $base . "read/" . $_GET['s'] . "/" . $value['chapter'] . "\">Page " . $value['chapter'] . "</a></div>";
		if($i == 4) {
			$r['text'] .= "</div>";
			$i = 1;
		} else {
			$i++;
		}
	}
	$r['text'] .= "</div>";
} else {
	$q = $db->prepare("SELECT * FROM stories WHERE chapter = ? AND story = ?");
	$q->execute(array($_GET['p'], $_GET['s']));
	$r = $q->fetch(PDO::FETCH_ASSOC);
	$next = true;
	if($_GET['p'] == "1") {
		$prev = false;
	} else {
		$prev = true;
	}

	if($_GET['p'] <= 0) {
		echo '<script>window.location.replace("' . $base . 'read/' . $_GET['s'] . '");</script>';
		//header("Location: read.php?s=" . $_GET['s']);
	} else {
		$last = $base . "read/" . $_GET['s'] . "/" . ($_GET['p']-1);
	}

	if($r['text'] == "") {
		//header("Location: read.php?s=" . $_GET['s']);
		echo '<script>window.location.replace("' . $base . 'read/' . $_GET['s'] . '");</script>';
	} else {
		$next = $base . "read/" . $_GET['s'] . "/" . ($_GET['p']+1);
	}
}

if(isset($_GET['a'])) {
	echo $r['text'];
}
?>