<?php
include("load.php");
?>
<html>
<head>
	<link rel="stylesheet" href="/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
	<title><?=$_GET['s']?> <?php if($cor) { echo "- Page " . $_GET['p']; }?></title>
	<style type="text/css">
		body {
			background-color: #E9ECEF;
		}
		span.wwnot {
			font-size: 0.7rem;
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
					<span class="wwnot">All of these stories have been ripped from <a href="https://www.wuxiaworld.com/" target="_blank">WuxiaWorld</a> purely for my own ease of reading.</span><br />
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
					<span class="wwnot">All of these stories have been ripped from <a href="https://www.wuxiaworld.com/" target="_blank">WuxiaWorld</a> purely for my own ease of reading.</span>
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
	function loadList() {
		$.get("<?=$base?>load.php?a=true", function(data, status) {
			//alert(data + " - " + status);
			if(status == "success") {
				s = true;
				$("#main-body").fadeOut(400, function() {
					$("#main-body").html(data);
				}).fadeIn(400);
				
				document.title = "Select a story";
				history.pushState(null, null, "/read/");
				$(".title-header").html("Select a story");
				hideDirections();
			}
		});
		return false;
	}

	function loadStoryList(story) {
		$.get("<?=$base?>load.php?a=true&s=" + story, function(data, status) {
			//alert(data + " - " + status);
			if(status == "success") {
				s = true;
				$("#main-body").fadeOut(400, function() {
					$("#main-body").html(data);
				}).fadeIn(400);
				
				document.title = story;
				history.pushState(null, null, "/read/" + story);
				$(".title-header").html(story);
				hideDirections();
			}
		});
		return false;	
	}

	function loadMe(story, chapter) {
		$.get("<?=$base?>load.php?a=true&s=" + story + "&p=" + chapter, function(data, status) {
			//alert(data + " - " + status);
			if(status == "success") {
				s = true;
				$("#main-body").fadeOut(400, function() {
					$("#main-body").html(data);
				}).fadeIn(400);
				
				document.title = story + " - Page " + chapter;
				history.pushState(null, null, "/read/" + story + "/" + chapter);
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
				history.pushState(null, null, "/read/" + s + "/" + p);
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
				history.pushState(null, null, "/read/" + s + "/" + p);
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
</script>
</body>