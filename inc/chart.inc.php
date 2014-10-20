<?php
/**
 * ColumnChart handler 
 */

$r = array();
$i = 0;
foreach ($t as $k => $v){
	if ($v > 0) {
		$r[$i]["label"] = $k;
		$r[$i]["n"] = $v;	
		$i++;
	}
}
$output = json_encode($r);

echo <<<EOF
	<html>
		<head>
			<title>Wikipedia Cite-o-Meter: Statistics for $doi[$doip]</title>
			<link rel="stylesheet" type="text/css" href="style.css" />
			<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.4.13/d3.min.js" charset="utf-8"></script>
		</head>
		<body>
	  	<h1><a href="$wc_url">Wikipedia Cite-o-Meter</a></h1>
		<h2>Statistics for <strong>$doi[$doip]</strong> (<strong>$doip</strong>)</h2>
	    <h3>[stats] [<a href="$table_url">data</a>] [<a href="$json_url">json</a>] [<a href="?about">about</a>]</h3>
		<ul id="stats">
			<li>Total number of citations across Wikipedia: <a href="$table_url" title="Display matches for $doi[$doip] in the top 100 Wikipedias"><strong>$totalcit</strong></a></li>
			<li>Project with the largest number of citations: <a title="Display matches for $doi[$doip] in $topl[0].wiki" href="http://$topl[0].wikipedia.org/w/index.php?title=Special:Search&search=$doip"><strong>$topl[0]</strong></a> ($topc citations)</li>
			<li>Total number of citations in Wikimedia Commons: <strong><a title="Display matches for $doi[$doip] in Wikimedia Commons" href="$commons_search$doip">$totalcommons</a></strong></li>
			<li>Data last updated: <strong>$fm</strong></li>
		</ul>

	    <h2>Citations for <strong>$doi[$doip]</strong> (<strong>$doip</strong>) in the top 100 Wikipedias</h2>
		<div id="chart"></div>
    	$footer
	    </body>
		<script type="text/javascript">
				var dataset = { "items" :  $output
				};

			var w = 1000,
				h = 20 * $i,
				barHorizontalPadding = 60,
				barVerticalPadding = 3,
				chartPadding = 150;
	
			var svg = d3.select("#chart")
				.append("svg")
				.attr("width", w)
				.attr("height", h);
				
				var data = dataset.items;
		
				var max_n = 0;
				for (var d in data) {
					max_n = Math.max(data[d].n, max_n);
				}
			
				var dx = (w - chartPadding) / max_n;
				var dy = h / data.length;
		
				var g = svg.selectAll(".bar")
					.data(data.sort(function(a,b) {return b.n - a.n;}), function(d) {return d.label;})
					.enter();
					
				g.append("svg:a")
  					.attr("xlink:href", function(d) {return "http://" + d.label + ".wikipedia.org/w/index.php?title=Special:Search&search=$doip";})
					.append("svg:rect")
					.attr("class", "bar")
					.attr("x", function(d, i) {return barHorizontalPadding;})
					.attr("y", function(d, i) {return dy*i;})
					.attr("width", 1)
					.style("fill", "#CCC")
					.attr("height", dy - barVerticalPadding)
					.transition()
						.delay(function(d, i) { return i * 80; })
						.duration(300)
						.attr("width", function(d, i) {return dx*d.n})
						.style("fill", "#933")
			
				g.append("svg:a")
  					.attr("xlink:href", function(d) {return "http://" + d.label + ".wikipedia.org/w/index.php?title=Special:Search&search=$doip";})
					.append("svg:text")
					.attr("x", 10)
					.attr("y", function(d, i) {return dy*i + 10;})
					.text( function(d) {return d.label;})
					.attr("font-size", "14px")
					.style("font-weight", "normal")
					.attr("opacity", 0)
					.transition()
						.delay(function(d, i) { return i * 80; })
						.duration(300)
						.attr("x", 0)
						.attr("opacity", 1)

				g.append("text")
					.attr("x", function(d, i) {return barHorizontalPadding +10})
					.attr("y", function(d, i) {return dy*i + 11;})
					.text( function(d) {return d.n;})
					.attr("font-size", "11px")
					.style("font-weight", "thin")
					.style("fill", "#FFF")
					.transition()
						.delay(function(d, i) { return i * 80; })
						.duration(300)
						.attr("x", function(d, i) {return barHorizontalPadding + dx*d.n +10})
						.style("fill", "#333");
		</script>
</html>
EOF;
?>
