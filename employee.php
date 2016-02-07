<?php 

if (isset($_POST['divv']))
{
    include ($_SERVER['DOCUMENT_ROOT'].'/test/db_function.php');
    $idboss = $_POST['divv'];
    showemployee($idboss, 0);
    exit();
}

include ('db_function.php');

echo "<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title> </title>

<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"css/bootstrap.css\" />

<!--Ajax-->
<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js\"></script>
<script language='JavaScript' src='js/ajax.js'></script>
</head><body>";

echo "<div class='row'><div class='container'>
    <div class='main_div col-lg-12 col-md-12 col-sm-12 col-xs-12'>";

echo '<div class="top_line">
        <div style="padding: 0px; float: left; width: 60px;">
        <a href="index.php" class="home_page">
            <span class="glyphicon glyphicon-home"></span>
        </a>
        </div></div><div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center">
            <p class="title_employee">Сотрудники компании</p>
        </div>';

showemployee(0,1);

echo "</div></div></div></div></body></html>";
?>