<?php
// Edit these variables to meet your environment:
$mysql_server = getenv('DB_HOST');
$mysql_username = getenv('DB_USER');
$mysql_password = getenv('DB_PASSWORD');
$mysql_db = getenv('DB_NAME');
$default_text = "John 3:16";

$mysqli = new mysqli($mysql_server, $mysql_username, $mysql_password, $mysql_db);

  /* change character set to utf8 */
  if (!$mysqli->set_charset("utf8")) {
    printf("Error loading character set utf8: %s\n", $mysqli->error);
} 

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}

require("bible_to_sql.php");
//echo "b: ".$_GET['b']." r: ".$_GET['r']."<br />";


//split at commas
$references = explode(",", $_GET['b']);


?>
<!DOCTYPE html>
<html>
<head>
<title>Bible Search</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<header>
<form action="index.php" action="GET">
<label for="b">Reference(s): </label><input type="text" name="b" value="<?php if ($_GET['b']) {
    echo $_GET['b'];
} else {
    echo $default_text;
} ?>" /><input type="submit" value="Search" /><br />

</form>
</header>
<main>
	<?php 
    //return results
    
    foreach ($references as $r) {
        $ret = new bible_to_sql($r, null, $mysqli);
        //echo "sql query: " . $ret->sql() . "<br />";
        //SELECT * FROM bible.t_kjv WHERE id BETWEEN 01001001 AND 02001005
        $sqlquery = "SELECT * FROM $mysql_db.KJV_PCE WHERE " . $ret->sql();
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($sqlquery);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            //0: ID 1: Book# 5:Chapter 6:Verse 7:Text
            
            print "<article><header><h1>{$ret->getBook()} {$ret->getChapter()}</h1></header>";
            
            while ($row = $result->fetch_row()) {
                print "<div class=\"versenum\">${row[6]}</div> <div class=\"versetext\">${row[7]}</div><br />";
            }
            print "</article>";
        } else {
            print "Did not understand your input.";
        }
        $stmt->close();
    }



    ?>
</main>
<footer>
<form action="index.php" action="GET">
<label for="b">Reference(s): </label><input type="text" name="b" value="<?php if ($_GET['b']) {
        echo $_GET['b'];
    } else {
        echo "John 3:16";
    } ?>" /><input type="submit" value="Search" /><br />

</form>
</footer>
</body>
</html>
<?php $mysqli->close(); ?>