<?php

$submitted = $_GET['s'];

if ($submitted) {
    include "bible_to_sql.php";
    echo $_GET['kindle-name'] ." <br />";

    // get length of uploaded csv

    // if length > 365 continue

    // make folder

    // loop 365 times

        // use datesArray
        // create file
        // curl api for files // index.php?k=1&date=September%2030&b=1%20Kings%202,Galatians%206,Ezekiel%2033,Psalm%2081,Psalm%2082
        // write file contents

    // copy files into folder
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Bible Search - Kindle Gen</title>
<link type="text/css" href="styles.css" rel="Stylesheet"/>
</head>
<body>
   
<header>
	
<form action="kindleGen.php?s=1" action="GET">
<label for="kindle-name">Kindle Book Name: </label><input type="text" name="kindle-name" value="" />

<input type="submit" value="generate" /><br />

</form>

</header>
<main>
	
</main>

<footer>
</footer>

</body>
</html>