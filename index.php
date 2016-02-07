<?php 

if (isset($_POST['add']))
{
    include ($_SERVER['DOCUMENT_ROOT'].'/test/db_function.php');
    echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\" />";
    adddata();
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
        <div class='main_div col-lg-12 col-md-12 col-sm-12 col-xs-12'>
            <div class='inner_div'>
            <a class='link_main' href='employee.php'> Все сотрудники</a>
            <a class='link_main' href='sort.php'> Сортировка и поиск</a>
            <a class='link_main' onclick='adddatatobase();'> Заполнить базу</a>
    <div id='resultadd'></div></div> </div></div></div>";

echo "</body></html>";

?>