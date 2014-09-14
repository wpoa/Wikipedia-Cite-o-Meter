<?php
/**
 * Default page 
 */

echo <<<EOD
<html>
  <head>
	  <title>Wikipedia Cite-o-Meter: Find citations by publisher in Wikipedia</title>
	  <link rel="stylesheet" type="text/css" href="style.css" />
  </head>
  <body>
	<a href="http://github.com/wpoa/Wikipedia-Cite-o-Meter"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://camo.githubusercontent.com/52760788cde945287fbb584134c4cbc2bc36f904/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f77686974655f6666666666662e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_white_ffffff.png"></a>
	<h1><a href="$wc_url">Wikipedia Cite-o-Meter</a></h1>
    <h2>Find citations by publisher in the top 100 Wikipedias <a title="About Wikipedia Cite-o-Meter" href="?about">(read more)</a></h2>
	<form onsubmit="document.DOISearch.SubmitButton.disabled=true; document.getElementById('loading').style.display = ''; document.getElementById('crossref').style.display = 'none';" id="DOISearch" name="DOISearch" method="get">
	<fieldset><legend>Select a publisher</legend>
    	<select name="doip">
EOD;

foreach($doi as $k=>$v)
{
	echo '	<option value="'.$k.'">'.$v.' ('.$k.')</option>'."\n";
}

echo <<<EOD
    	</select>
    	<input type="submit" value="Search" id="SubmitButton" />
		<p id="crossref" class="small sp">Source: <a href="http://www.crossref.org/06members/50go-live.html">CrossRef</a> (last updated: 2011-09-24)</p>
		<p id="loading" class="small sp" style="display: none; color: #933"><img src="ajax-loader.gif" /> Retrieving data (this may take a few minutes)</p>
	</fieldset>

	<fieldset><legend>...or try one of the following</legend>
	<ul class="left">
		<li><a class="small" href="?doip=10.1126">American Association for the Advancement of Science (AAAS)</a></li>
		<li><a class="small" href="?doip=10.1021">American Chemical Society (ACS)</a></li>
		<li><a class="small" href="?doip=10.5194">Copernicus GmbH</a></li>
		<li><a class="small" href="?doip=10.1016">Elsevier</a></li>
		<li><a class="small" href="?doip=10.3389">Frontiers Research Foundation</a></li>
		<li><a class="small" href="?doip=10.1155">Hindawi Publishing Corporation</a></li>
		<li><a class="small" href="?doip=10.1080">Informa UK (Taylor & Francis)</a></li>
		<li><a class="small" href="?doip=10.1088">IOP Publishing</a></li>
		<li><a class="small" href="?doip=10.1038">Nature Publishing Group</a></li>
	</ul>
	<ul class="left">
		<li><a class="small" href="?doip=10.3897">Pensoft Publishers</a></li>
		<li><a class="small" href="?doip=10.1073">Proceedings of the National Academy of Sciences (PNAS)</a></li>
		<li><a class="small" href="?doip=10.1371">Public Library of Science</a></li>
		<li><a class="small" href="?doip=10.1177">Sage Publications</a></li>
		<li><a class="small" href="?doip=10.1007">Springer-Verlag</a></li>
		<li><a class="small" href="?doip=10.1186">Springer (Biomed Central Ltd.)</a></li>
		<li><a class="small" href="?doip=10.1098">The Royal Society</a></li>
		<li><a class="small" href="?doip=10.1515">Walter de Gruyter</a></li>
		<li><a class="small" href="?doip=10.1111">Wiley Blackwell (Blackwell Publishing)</a></li>
	</fieldset>
    </form>
<form method="get">
        <fieldset><legend>Top publishers in Wikipedia</legend> 
        <label for="lang">Wikipedia:</label><select name="lang">
EOD;
foreach ($langs as $l)
{
        echo '  <option value="'.$l.'"';
        if ($l == $lang) echo ' selected="selected"';
        echo '>'.$l.'</option>'."\n";
}
echo <<<EOD
                </select>
        <input type="submit" value="Search" id="LangSubmitButton" />
        </fieldset>
    </form>
<form method="get">
        <fieldset><legend>Top publishers in other Wikimedia projects</legend> 
	<label for="commons">Wikipedia:Commons</label>
	<input type="hidden" name="commons" />
        <input type="submit" value="Search" id="CommonsSubmitButton" />
        </fieldset>
    $footer
</body>
</html>
EOD;
?>
