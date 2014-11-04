<?php
/**
 * About page 
 */
echo <<<EOF
<html>
  <head>
	  <title>Wikipedia Cite-o-Meter: About</title>
	  <link rel="stylesheet" type="text/css" href="style.css" />
  </head>
  <body>
	<a href="http://github.com/wpoa/Wikipedia-Cite-o-Meter"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://camo.githubusercontent.com/52760788cde945287fbb584134c4cbc2bc36f904/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f77686974655f6666666666662e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_white_ffffff.png"></a>
	<h1><a href="$wc_url">Wikipedia Cite-o-Meter</a></h1>
	<h2>About</h2>
	
	<p>The <strong>Wikipedia Cite-o-Meter</strong> provides a conservative estimate of the number of times journal articles from a particular publisher are cited in the 100 largest Wikipedias. Using the <a href="http://en.wikipedia.org/w/api.php">Wikipedia API</a>, Cite-o-Meter searches for occurrences of a DOI prefix in the main namespace of these Wikipedias (<a href="http://en.wikipedia.org/w/index.php?title=Special%3ASearch&amp;search=10.1371&amp;fulltext=Search&amp;ns0=1 ">example</a>).</p> 
	
	<p>A <a href="http://en.wikipedia.org/wiki/Digital_object_identifier">Digital Object Identifier</a> (DOI) is a string used to uniquely identify objects such as electronic documents. DOIs are the de facto standard for uniquely identifying scholarly publications such as journal articles. A DOI (such as <tt>10.1371/journal.pone.0011273</tt>) is <a href="http://www.doi.org/doi_handbook/2_Numbering.html#2.2.2">composed of two parts</a> &mdash; a prefix (<tt>10.1371</tt>) and a suffix  (<tt>journal.pone.0011273</tt>). An article is uniquely identified by a DOI, which can be looked up using the <a href="http://dx.doi.org/">DOI resolver</a>. The example article is located at <a href="http://dx.doi.org/10.1371/journal.pone.0011273">http://dx.doi.org/10.1371/journal.pone.0011273</a>.</p>
	
	<p>The DOI prefix is specific to a DOI registrant - the organization that handles the assignments of DOI suffixes for items published under that prefix. Most scholarly publishers operate as a single registrant, yet due to mergers and acquisitions, some publishers run several DOI prefixes. The DOI prefix <tt>10.1371</tt> is handled by the Public Library of Science (PLoS), and it is the only DOI prefix handled by PLoS. As a result, <a href="http://tools.wmflabs.org/cite-o-meter/?doip=10.1371">Cite-o-Meter statistics</a> for this prefix provide a lower bound for citations to PLoS articles in Wikipedia.</p>

	<h3>Data and license</h3>
	<p>Cite-o-Meter data is available in the form of <a href="?doip=10.1371">graphs</a>, <a href="?doip=10.1371&amp;table">tabular data</a> and machine-readable <a href="?doip=10.1371&amp;json">JSON</a> and is released under a <a href="http://creativecommons.org/publicdomain/zero/1.0/">CC0</a> license.</p>

	<h3 id="notes">Notes and known limitations</h3>
	<ul>
		<li>Not all journal article citations in Wikipedia include a DOI: DOI-less citations are not tracked by Cite-o-Meter.</li>
		<li>Not all DOIs are registered via CrossRef, on the basis of whose data Cite-o-Meter identifies registrants.</li>
		<li>Cite-o-Meter does not discriminate between single and multiple occurrences of a DOI prefix in a Wikipedia article.</li>
		<li>Journal DOIs persist when transferred to another publisher, hence the DOI prefix can only be taken as an indication of the original publisher.</li>
	</ul>

	$footer
	</body>
</html>
EOF;
?>
