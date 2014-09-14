<?php
/**
 * Top publishers by citations in Wikimedia Commons
 */

//build array from cached data
$ps = array();
$list = glob("./data/*_commons.txt"); 
foreach ($list as $l)
{ 
	if (preg_match("/\.\/data\/(10\.[0-9]{4})\_commons\.txt/",$l,$matches)) 
 	{
	 	$pf = $matches[1];
		$t = unserialize(file_get_contents($l));
		$ps[$pf] = $t[0];
 	}
} 

//sort array by citation count
arsort($ps);

//only take top 100 records with non-zero values
$max = 100;
$ps = array_slice(array_filter($ps, function ($v){ return ($v>0);}),0,$max);
$cl = count($ps);

//store data in JSON object
$r = array();
$i = 0;
foreach ($ps as $pf => $c){
	$r[$i]["label"] = $doi[$pf];
	$r[$i]["n"] = $c;
	$i++;
}
//$output = json_encode($r,JSON_PRETTY_PRINT);
$output = json_encode($r);
$maxv = $pf[0];

echo <<<EOF
	<html>
		<head>
			<title>Wikipedia Cite-o-Meter: Top $max publishers by citations in Wikimedia Commons</title>
			<link rel="stylesheet" type="text/css" href="style.css" />
			<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
		</head>
		<body>
		<h1><a href="$wc_url">Wikipedia Cite-o-Meter</a></h1>
	<h2>Top 100 publishers by citations in <a href="http://commons.wikimedia.org">Wikimedia Commons</a></h2>
	<div id="chart"></div>
	$footer
	</body>
	<script type="text/javascript">
			var dataset = { "items" :  $output
				};

			var w = 1000,
				h = 20 * $i,
				barHorizontalPadding = 400,
				barVerticalPadding = 3,
				chartPadding = 500;
				maxLabelLength = 60;
	
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

				g.append("rect")
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
			
				g.append("text")
					.attr("x", 0)
					.attr("y", function(d, i) {return dy*i + 10;})
					.text( function(d) {return d.label.substring(0,maxLabelLength);})
					.attr("font-size", "14px")
					.style("font-weight", "normal");

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
