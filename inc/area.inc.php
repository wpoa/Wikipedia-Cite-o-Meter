<?php
/**
 * AreaChart handler (disabled)
 */

echo <<<EOF
	<html>
	  <head>
	  	<title>Wikipedia Cite-o-Meter: Statistics for $doi[$doip]</title>
	  	<link rel="stylesheet" type="text/css" href="style.css" />
	    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
	    <script type="text/javascript">
	      google.load("visualization", "1", {packages:["corechart"]});
	      google.setOnLoadCallback(drawChart);
	      function drawChart() {
	        var data = new google.visualization.DataTable();
	        data.addColumn('string', 'Project');
	        data.addColumn('number', 'Citations');
	        data.addRows([

EOF;

	foreach ($langs1 as $l)
	{
		echo "          ['$l', $t[$l]],";
	}

echo	<<<EOF
        ]);

	        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
	        chart.draw(data, {width: 1200, height: 240, legend: 'none',
				  vAxis: {logScale: true, format:'#,###'},
	                          hAxis: {slantedTextAngle: 60}
	                         });
	      	}
	    </script>
	    <script type="text/javascript">
	      google.load("visualization", "1", {packages:["corechart"]});
	      google.setOnLoadCallback(drawChart2);
	      function drawChart2() {
	        var data = new google.visualization.DataTable();
	        data.addColumn('string', 'Project');
	        data.addColumn('number', 'Citations');
	        data.addRows([

EOF;

	foreach($langs2 as $l)
	{
		echo "          ['$l', $t[$l]],";
	}
	$maxv = $t['en'];


echo <<<EOF
	        ]);

	        var chart = new google.visualization.AreaChart(document.getElementById('chart_div2'));
	        chart.draw(data, {width: 1200, height: 240, legend: 'none',
				  				vAxis: {logScale: true, format:'#,###', maxValue: $maxv},
	                          	hAxis: {slantedTextAngle: 60}
	                         });
	     	}
	    </script>
	  </head>
	  <body>
	  	<h1><a href="$wc_url">Wikipedia Cite-o-Meter</a></h1>
		<h2>Statistics for <strong>$doi[$doip]</strong> (<strong>$doip</strong>)</h2>
	    <h3>[stats] [<a href="$table_url">data</a>] [<a href="$json_url">json</a>]</h3>
		<ul>
			<li>Total number of citations across Wikipedia: <strong>$totalcit</strong></li>
			<li>Project with the largest number of citations: <strong>$topl[0]</strong> ($topc citations)</li>
			<li>Total number of citations in Wikimedia Commons: <strong><a href="$commons_search$doip">$totalcommons</a></strong></li>
			<li>Data last updated: <strong>$fm</strong></li>
		</ul>

	    <h2>Citations for <strong>$doi[$doip]</strong> (<strong>$doip</strong>) in the top 100 Wikipedias</h2>
	    <div style="margin:0" id="chart_div"></div>
	    <div style="margin:0" id="chart_div2"></div>

    	$footer
	  </body>
	</html>
EOF;
?>