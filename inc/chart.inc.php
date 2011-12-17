<?php
/**
 * ColumnChart handler 
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
	        data.addRows($cl1);

EOF;

	$b = 0;
	foreach($langs1 as $l)
	{
		echo "        	data.setValue($b, 0, '$l');\n";
		echo "        	data.setValue($b, 1, $t[$l]);\n";
		$b++;
	}
	$maxv = $t['en'];

echo <<<EOF

	        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
	        chart.draw(data, {width: 1200, height: 240, legend: 'none', colors:['#933'],
				  	vAxis: {logScale: true, format:'#,###', maxValue: $maxv},
				  	chartArea:{left:80, top: 20},
	                hAxis: {slantedTextAngle: 60, textStyle: {fontSize: 12}}
	                });
	     	}
	    </script>
	    <script type="text/javascript">
	      google.load("visualization", "1", {packages:["corechart"]});
	      google.setOnLoadCallback(drawChart2);
	      function drawChart2() {
	        var data2 = new google.visualization.DataTable();
	        data2.addColumn('string', 'Project');
	        data2.addColumn('number', 'Citations');
	        data2.addRows($cl2);

EOF;

	$b = 0;
	foreach($langs2 as $l)
	{
		echo "        	data2.setValue($b, 0, '$l');\n";
		echo "        	data2.setValue($b, 1, $t[$l]);\n";
		$b++;
	}
	$maxv = $t['en'];

echo <<<EOF

	        var chart2 = new google.visualization.ColumnChart(document.getElementById('chart_div2'));
	        chart2.draw(data2, {width: 1200, height: 240, legend: 'none', colors:['#933'],
				  	vAxis: {logScale: true, format:'#,###', maxValue: $maxv},
				  	chartArea:{left:80, top: 20},
	                hAxis: {slantedTextAngle: 60, textStyle: {fontSize: 12}}
	                });
	     	}
	    </script>
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
	    <div style="margin:0" id="chart_div"></div>
	    <div style="margin:0" id="chart_div2"></div>
    	$footer
	  </body>
	</html>
EOF;
?>
