<?php
include("load.php");
?>
<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<title><?=$_GET['s']?> <?php if($next) { echo "- Page " . $_GET['p']; }?></title>
	<style type="text/css">
		body {
			background-color: #E9ECEF;
		}

		.hide {
			display: none;
		}

	</style>
</head>

<body>

<main role="main">
	<div class="jumbotron">
		<div class="container">
			<div class="row">
				<div class="col-3 col-sm-3">
					<a class="nav-link prev-link <?php if(!$prev) { ?>hide<?php } ?>"  style="font-size: 1.2rem;" href="<?=$last?>"><< Last Page</a>
				</div>
				<div class="col-6 col-sm-6 text-center">
					<h2 class="title-header"><?php echo $_GET['s']; ?></h2>
				</div>
				<div class="col-3 col-sm-3 text-right">
					<a class="nav-link next-link <?php if(!$next) { ?>hide<?php } ?>" style="font-size: 1.2rem;" href="<?=$next?>">Next Page >></a>
				</div>
			</div>
		</div>
		<hr />
		<div style="font-size: 2rem;" id="main-body">
			<?=$r['text']?>
		</div>
		<hr />
		<div class="container">
			<div class="row">
				<div class="col-3 col-sm-3">
					<a class="nav-link prev-link <?php if(!$prev) { ?>hide<?php } ?>" style="font-size: 1.2rem;" href="<?=$last?>"><< Last Page</a>				
				</div>
				<div class="col-6 col-sm-6 text-center">
					<h2 class="title-header"><?php echo $_GET['s']; ?></h2>
				</div>
				<div class="col-3 col-sm-3 text-right">
					<a class="nav-link next-link <?php if(!$next) { ?>hide<?php } ?>" style="font-size: 1.2rem;" href="<?=$next?>">Next Page >></a>
				</div>
			</div>
		</div>
	</div>
</main>
<script type="text/javascript" src="<?=$base?>jquery.min.js"></script>
<script type="text/javascript">
	function loadList(back = false) {
		$.get("<?=$base?>load.php?a=true", function(data, status) {
			//alert(data + " - " + status);
			if(status == "success") {
				s = true;
				$("#main-body").fadeOut(400, function() {
					$("#main-body").html(data);
				}).fadeIn(400);
				
				document.title = "Select a story";
				if(back) {
					console.log("replace");
					history.replaceState(null, null, "<?=$base?>read/");
				} else {
					console.log("push");
					history.pushState(null, null, "<?=$base?>read/");
				}
				$(".title-header").html("Select a story");
				hideDirections();
			}
		});
		return false;
	}

	function loadStoryList(story, back = false) {
		$.get("<?=$base?>load.php?a=true&s=" + story, function(data, status) {
			//alert(data + " - " + status);
			if(status == "success") {
				s = true;
				$("#main-body").fadeOut(400, function() {
					$("#main-body").html(data);
				}).fadeIn(400);
				
				document.title = story;

				if(back) {
					console.log("replace");
					history.replaceState(null, null, "<?=$base?>read/" + story);
				} else {
					console.log("push");
					history.pushState(null, null, "<?=$base?>read/" + story);
				}
				$(".title-header").html(story);
				hideDirections();
			}
		});
		return false;	
	}

	function loadMe(story, chapter, back = false) {
		$.get("<?=$base?>load.php?a=true&s=" + story + "&p=" + chapter, function(data, status) {
			//alert(data + " - " + status);
			if(status == "success") {
				s = true;
				$("#main-body").fadeOut(400, function() {
					$("#main-body").html(data);
				}).fadeIn(400);
				
				document.title = story + " - Page " + chapter;
				if(back) {
					console.log("replace");
					history.replaceState(null, null, "<?=$base?>read/" + story + "/" + chapter);
				} else {
					console.log("push");
					history.pushState(null, null, "<?=$base?>read/" + story + "/" + chapter);
				}
				$(".title-header").html(story);
				showDirections(story, chapter);
			}
		});
		return false;
	}

	$(".next-link").click(function() {
		const regex = /\/read\/([\w\W]+)\/([\d]+)/gm;
		const str = window.location.pathname;
		let m;
		m = regex.exec(str);

		var s = m[1].replace(/([\%20])+/gm, " ");
		var p = parseInt(m[2]);
		p = (p+1);

		$.get("<?=$base?>load.php?a=true&s=" + s + "&p=" + p, function(data, status) {
			//alert(data + " - " + status);
			if(status == "success") {
				$("#main-body").fadeOut(400, function() {
					$("#main-body").html(data);
				}).fadeIn(400);
				
				document.title = s + " - Page " + p;
				history.pushState(null, null, "<?=$base?>read/" + s + "/" + p);
				$(".title-header").html(s);
				showDirections(s, p);
				window.scrollTo(0,0);
			}
		});

		return false;
	});

	$(".prev-link").click(function() {
		const regex = /\/read\/([\w\W]+)\/([\d]+)/gm;
		const str = window.location.pathname;
		let m;
		m = regex.exec(str);

		var s = m[1].replace(/([\%20])+/gm, " ");
		var p = parseInt(m[2]);
		p = (p-1);

		$.get("<?=$base?>load.php?a=true&s=" + s + "&p=" + p, function(data, status) {
			//alert(data + " - " + status);
			if(status == "success") {
				$("#main-body").fadeOut(400, function() {
					$("#main-body").html(data);
				}).fadeIn(400);
				
				document.title = s + " - Page " + p;
				history.pushState(null, null, "<?=$base?>read/" + s + "/" + p);
				$(".title-header").html(s);
				showDirections(s, p);
				window.scrollTo(0,0);
			}
		});

		return false;
	});

	function hideDirections() {
		$(".next-link").addClass("hide");
		$(".prev-link").addClass("hide");
	}

	function showDirections(story, chapter) {
		next = (parseInt(chapter)+1);
		prev = (parseInt(chapter)-1);

		$(".next-link").attr("href", "<?=$base?>read/" + story + "/" + next).removeClass("hide");
		if(prev > 0) {
			$(".prev-link").attr("href", "<?=$base?>read/" + story + "/" + prev).removeClass("hide");
		} else {
			$(".prev-link").addClass("hide");
		}
	}

	window.onpopstate = function(event) {
		//alert("location: " + document.location + ", state: " + JSON.stringify(event.state));

		page = /\/read\/([a-zA-Z\%20]+)\/([\d]+)/gm;
		story = /\/read\/([a-zA-Z\%20]+)/gm;
		list = /\/read\//gm;
		newloc = String(document.location);
		console.log(newloc);
		let r;

		if(page.test(newloc)) {
			page.exec("");
			r = page.exec(newloc);
			r[1] = r[1].replace(/([\%20])+/gm, " ");
			loadMe(r[1], r[2], true);
			console.log("loadMe(" + r[1] + ", " + r[2] + ")");
		} else if(story.test(newloc)) {
			story.exec("");
			r = story.exec(newloc);
			r[1] = r[1].replace(/([\%20])+/gm, " ");
			loadStoryList(r[1], true);
			console.log("loadStoryList(" + r[1] + ")");
		} else if(list.test(newloc)) {
			loadList(true);
			console.log("loadList()");
		} else {
			back();
			console.log("back()");
		}
	};
</script>
</body>