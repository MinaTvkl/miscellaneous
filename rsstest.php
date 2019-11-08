<?php header("Content-type:text/xml;charset=utf-8");?>
<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://purl.org/rss/1.0/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:syn="http://purl.org/rss/1.0/modules/syndication/">
<channel rdf:about="http://www.nada.kth.se/media/Theses/"> 
		<title>Examensarbeten medieteknik</title>
		<link>http://www.nada.kth.se/media/Theses/</link>
		<description>Examensarbeten inom medieteknik.</description>
		<dc:language>sv</dc:language>
		<dc:rights>Copyright KTH/Nada/Media</dc:rights>
		<dc:date>2006-10-19T09:41:35+01:00</dc:date> <!--ändra dc:date-->

		<dc:publisher>KTH/Nada/Media</dc:publisher>
		<dc:creator>bjornh@kth.se</dc:creator>
		<syn:updatePeriod>daily</syn:updatePeriod>
		<syn:updateFrequency>1</syn:updateFrequency>
		<syn:updateBase>2006-01-01T00:00+00:00</syn:updateBase>

    <?php  
    // connect using host, username, password and databasename
    $connection = mysqli_connect('xml.csc.kth.se', 'rsslab', 'rsslab','rsslab'); //bytt namn på link till connection för att undvika förvirring

	//check connection 
	if (mysqli_connect_errno()) {
    	printf("Connect failed: %s\n", mysqli_connect_error());
    	exit();
	}

    $returnstring ="";
    
    // The SQL query
    $query = "SELECT  link, title, description, creator, feeddate
            FROM exjobbsfeed
            ORDER BY feeddate ASC";

    // Execute the query
	if (($result = mysqli_query($connection, $query)) == FALSE) {
       	printf("Query failed: %s\n", $query);
	}
    /*
    $returnstring = $returnstring . "<items><rdf:Seq>";

    while ($line = $result->fetch_object()) {
        // Store results from each row in variables
        $link = $line->link;
        $link = preg_replace("/&/", "&amp", $link); //ersätter specialtecken i länken
        $link = preg_replace("/\s+/", "%20", $link);
        $date = $line->feeddate;


        
        // Store the result we want by appending strings to the variable $returnstring
        $returnstring = $returnstring . "<rdf:li rdf:resource='$link'/>"; 
        $returnstring = $returnstring . "<link></link>";
        //stänger öppnade taggar (rdf:Seq, items, channel) och lägger till bilden
        $returnstring = $returnstring . "</rdf:Seq></items><image rdf:resource='http://www.nada.kth.se/media/images/kth.png'/></channel>";
*/
    
    // Loop over the resulting lines
    while ($line = $result->fetch_object()) {
        // Store results from each row in variables
        $link = $line->link;
        $link = preg_replace("/\s+/", "%20", $link);  //ersätter specialtecken i länken
        //$date = $line->feeddate;
        $title = $line->title;
        $description = $line->description;
        $description = preg_replace("/&/", "&amp", $description);
        $creator = $line->creator;
        $date = $line->feeddate;

        
        // Store the result we want by appending strings to the variable $returnstring
        $returnstring = $returnstring . "<item rdf:about='$link'>";
        $returnstring = $returnstring . "<title>$title</title>"; 
        $returnstring = $returnstring . "<link>$link</link>";
        $returnstring = $returnstring . "<description>$description</description>";
        $returnstring = $returnstring . "<dc:creator>$creator</dc:creator>";
        //$returnstring = $returnstring . "</dc:date>$feeddate</dc:date>";
        $returnstring = $returnstring . "</item>";

    }
error_reporting(E_ALL);
ini_set('display_errors', 1);

//print $returnstring;
    print utf8_encode($returnstring);
// Free result and just in case encode result to utf8 before returning
    mysqli_free_result($result);

    ?>
</rdf:RDF>