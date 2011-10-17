<?php
/**
 * Top publishers by project 
 */

//build array from cached data
$ps = array();
$list = glob("./data/*.txt"); 
foreach ($list as $l)
{ 
	if (preg_match("/\.\/data\/(10\.[0-9]{4})\.txt/",$l,$matches)) 
 	{
	 	$pf = $matches[1];
		$t = unserialize(file_get_contents($l));
		$ps[$pf] = $t[$lang];
 	}
} 

//sort array by citation count
arsort($ps);
//take top 100 non-null values
$max = 100;
$ps = array_slice(array_filter($ps, function ($v){ return ($v>0);}),0,$max);
$cl = count($ps);

echo <<<EOF
<html>
  <head>
	<title>Wikipedia Cite-o-Meter: Top $max publishers by citations in Wikipedia:$lang</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">
		google.load("visualization", "1", {packages:["corechart"]});
		google.setOnLoadCallback(drawChart);
		function drawChart() {
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Publisher');
			data.addColumn('number', 'Citations');
			data.addRows($max);
EOF;

$b = 0;
foreach($ps as $pf => $c)
{
	echo "			data.setValue($b, 0, '$doi[$pf]');\n";
	echo "			data.setValue($b, 1, $c);\n";
	$b++;
}
$maxv = $pf[0];

echo <<<EOF
		var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
        chart.draw(data, {width: 1000, height: 2600, legend: 'none', colors:['#933'],
						chartArea:{left:280, top: 20},
						hAxis: {logScale: false, format:'#,###',maxValue: $maxv},
                        vAxis: {textStyle: {fontSize: 12}}
                         });
        }
	    </script>
  </head>
  <body>
	<h1><a href="$wc_url">Wikipedia Cite-o-Meter</a></h1>
EOF;

echo <<<EOF
<form method="get">
        <fieldset><legend>Top publishers in Wikipedia</legend>
        <label for="lang">Wikipedia:</label><select name="lang">
EOF;
foreach ($langs as $l)
{
	echo '	<option value="'.$l.'"';
	if ($l == $lang) echo ' selected="selected"';
	echo '>'.$l.'</option>'."\n";
}
echo <<<EOF
		</select>
    	<input type="submit" value="Submit" id="SubmitButton" />
	</fieldset>
</form>
<h2>Top 100 publishers by citations in Wikipedia:<a href="http://$lang.wikipedia.org">$lang</a></h2>
<div id="chart_div"></div>
$footer
</body>
</html>
EOF;
?>
