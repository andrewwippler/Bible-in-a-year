<?php
// Edit these variables to meet your environment:
$mysql_server = getenv('DB_HOST');
$mysql_username = getenv('DB_USER');
$mysql_password = getenv('DB_PASSWORD');
$mysql_db = getenv('DB_NAME');
$default_text = "John 3:16";

$mysqli = new mysqli($mysql_server, $mysql_username, $mysql_password, $mysql_db);
 
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

require "bible_to_sql.php";
//echo "b: ".$_GET['b']." r: ".$_GET['r']."<br />";


//split at commas
$ref = str_replace(';', ',', $_GET['b']);
$references = explode(",", $ref);
$formattedReferences = getFormattedReferences($references);
?>
<!DOCTYPE html>
<?php if ($_GET['k'] == 1) {
    ?> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php print $_GET['date']; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<link type="text/css" href="styles.css" rel="Stylesheet"/>
<link type="text/css" href="kf8.css" media="amzn-kf8" rel="Stylesheet"/>
<link type="text/css" href="mobi.css" media="amzn-mobi" rel="Stylesheet"/>
</head>
<body>
    <h1><?php print $_GET['date']; ?></h1>
    <?php
} else {
        ?>
<html>
<head>
<title>Bible Search</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<link type="text/css" href="styles.css" rel="Stylesheet"/>
</head>
<body>
    <?php
    }
if ($_GET['h'] == 1 || $_GET['k'] == 1) {
} else {
    ?>
<header>

<form action="index.php" action="GET">
<label for="b">Reference(s): </label><input type="text" name="b" value="<?php if ($_GET['b']) {
        echo $_GET['b'];
    } else {
        echo $default_text;
    } ?>" /><input type="submit" value="Search" /><br />

</form>

</header>
<?php
} ?>
<?php 
    //return results
    
foreach ($formattedReferences as $r) {
    $ret = new bible_to_sql($r, null, $mysqli);
    //echo "sql query: " . $ret->sql() . "<br />";
    //SELECT * FROM bible.t_kjv WHERE id BETWEEN 01001001 AND 02001005
    $sqlquery = "SELECT * FROM KJV_PCE WHERE " . $ret->sql();
    if ($result = $mysqli->query($sqlquery)) {
        print "<article><header><h3>{$ret->getBook()} {$ret->getChapter()}</h3></header>";
        while ($row = $result->fetch_row()) {
            //0: ID 1: Book# 5:Chapter 6:Verse 7:Text
            $verse = $row[7];
            if ($row[1] == 19 && preg_match('/{/', $verse)) { //Psalms
                $comments = explode('}', $verse);
                $verse = $comments[1];
                $header = preg_replace('/{/', '', $comments[0]);
                print "<div class='verse'>$header</div>";
                print "<div class=\"verse\"><span class=\"versenum\">${row[6]}</span> <span class=\"versetext\">$verse</span></div>";
            } elseif (preg_match('/{/', $verse)) {
                $comments = explode('{', $verse);
                $verse = $comments[0];
                $footer = preg_replace('/}/', '', $comments[1]);
                print "<div class=\"verse\"><span class=\"versenum\">${row[6]}</span> <span class=\"versetext\">$verse</span></div>";
                print "<div class='verse'>$footer</div>";
            } else {
                print "<div class=\"verse\"><span class=\"versenum\">${row[6]}</span> <span class=\"versetext\">$verse</span></div>";
            }
        }
        print "</article>";
        $result->close();
    }
}
?>
    <?php if ($_GET['h'] == 1 || $_GET['k'] == 1) {
    print "<div class=\"enddiv\"></div>";
} else {
    ?>
<footer>

<form action="index.php" action="GET">
<label for="b">Reference(s): </label><input type="text" name="b" value="<?php if ($_GET['b']) {
        echo $_GET['b'];
    } else {
        echo $default_text;
    } ?>" /><input type="submit" value="Search" /><br />

</form>

</footer>
<?php
} ?>
</body>
</html>
<?php $mysqli->close(); ?>