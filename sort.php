<?php 
//если был только поиск
if (isset($_POST['qs']))
{
    include ($_SERVER['DOCUMENT_ROOT'].'/test/db_function.php');
  
    db_connect();
    
    $querysearch = $_POST['qs'];
  
    $querysearch = strip_tags($querysearch);
	$querysearch = trim($querysearch);
    $querysearch = mysql_real_escape_string($querysearch);
    $querysearch = str_replace(",", ".", $querysearch);

    if (isset($querysearch) and $querysearch != "" and isset($_POST['ws']))
    {
        if($querysearch == 0)
        {
            $searchquery = mysql_query("SELECT `fio`,`name_post`, `salary`, DATE_FORMAT(datestart, '%d.%m.%Y') AS datestart
                    FROM `employee` INNER Join post ON `employee`.`id_post`=`post`.`id_post`");
        }
        else
        {
            switch($_POST['ws']){
                case 1:
                    $searchquery = mysql_query("SELECT `fio`,`name_post`, `salary`, DATE_FORMAT(datestart, '%d.%m.%Y') AS datestart
                    FROM `employee` INNER Join post ON `employee`.`id_post`=`post`.`id_post`
                    WHERE `fio` LIKE '%".$querysearch."%'");
                break;
                case 2:
                    $searchquery = mysql_query("SELECT `fio`,`name_post`, `salary`, DATE_FORMAT(datestart, '%d.%m.%Y') AS datestart
                    FROM `employee` INNER Join post ON `employee`.`id_post`=`post`.`id_post`
                    WHERE `name_post` LIKE '%".$querysearch."%'");
                break;
                case 3:
                    if(!preg_match("/^(0?[1-9]|[1-2][0-9]|3[0-1]).(0?[1-9]|1[0-2]).(20[\d]{2})$/", $querysearch))
                    {
                        echo "Для поиска по дате пожалуйста введите корректную и полную дату!";
                        exit();
                    }
                    $searchquery = mysql_query("SELECT `fio`,`name_post`, `salary`, DATE_FORMAT(datestart, '%d.%m.%Y') AS datestart
                    FROM `employee` INNER Join post ON `employee`.`id_post`=`post`.`id_post`
                    WHERE DATE_FORMAT(datestart, '%d.%m.%Y') LIKE '%".$querysearch."%'");
                break;
                case 4:
                    if(!preg_match("/^[0-9]./", $querysearch))
                    {
                        echo "Для поиска по окладу пожалуйста введите числовое значение!";
                        exit();
                    }
                    $searchquery = mysql_query("SELECT `fio`,`name_post`, `salary`, DATE_FORMAT(datestart, '%d.%m.%Y') AS datestart
                    FROM `employee` INNER Join post ON `employee`.`id_post`=`post`.`id_post`
                    WHERE `salary` LIKE '%".$querysearch."%'");
                break;
                default:
                    echo "По чем сортируем? Неизвестно)";
                    exit();
                break;
            }
        }
        if (mysql_num_rows($searchquery) != 0)
        {
            sortpost(0, 0, 0, 0);
            
            while($resultsearch = mysql_fetch_array($searchquery))
            {
                echo "
                    <tr>
                        <td id='paddingtd'>".$resultsearch[fio]."</td>
                        <td id='paddingtd'>".$resultsearch[name_post]."</td>
                        <td id='paddingtd' align='center'>".$resultsearch[datestart]."</td>
                        <td id='paddingtd'>".$resultsearch[salary]."</td>
                    </tr>
                ";
            }
            echo "</table></div>";
        }
        else
        {
            echo "<p class='no_result_search'>Поиск не дал результатов!</p> </div>";
        }
        
            
    }
    else
    {
        echo "Что-то пошло не так!";
    }
    
    exit();
}


/*if (isset($_POST['no']))
{
    include ($_SERVER['DOCUMENT_ROOT'].'/test/db_function.php');
  
    db_connect();
    
    sortpost(0,0,0,0);
    
     $all = mysql_query("SELECT id_employee, fio, name_post, DATE_FORMAT(datestart, '%d.%m.%Y') AS datestart, salary FROM employee INNER JOIN post ON employee.`id_post`=post.`id_post`");
    if (mysql_num_rows($all) != 0)
    {
        
        while($allemployee = mysql_fetch_array($all))
        {
            echo "
                <tr>
                    <td id='paddingtd'>".$allemployee[fio]."</td>
                    <td id='paddingtd'>".$allemployee[name_post]."</td>
                    <td id='paddingtd' align='center'>".$allemployee[datestart]."</td>
                    <td id='paddingtd'>".$allemployee[salary]."</td>
                </tr>
            ";
        }
    }
    echo "</table></div>";
    
    exit();
}*/

//сортировка данных
if (isset($_POST['n']) or isset($_POST['s']))
{
    include ($_SERVER['DOCUMENT_ROOT'].'/test/db_function.php');
    
    db_connect();
    
    $nameorder = $_POST['n'];
    $valorder = $_POST['v'];
    $param = "fio,name_post,datestart,salary";
    $paramarray = array("fio","name_post","datestart","salary");
    
   // $query = str_repeat("(", count($nameorder)-1);
    $query .= "SELECT id_employee, fio, name_post, DATE_FORMAT(datestart, '%d.%m.%Y') AS datestart, salary FROM employee INNER JOIN post ON employee.`id_post`=post.`id_post` ";
    // сортируем по результатам поиска
    if(isset($_POST['s']) and isset($_POST['num']))
    {
        switch($_POST['num']){
            case 1:
                $query_where = " WHERE `fio` LIKE '%".$_POST['s']."%' ";
            break;
            case 2:
                $query_where = " WHERE `name_post` LIKE '%".$_POST['s']."%' ";
            break;
            case 3:
                $query_where =" WHERE DATE_FORMAT(datestart, '%d.%m.%Y') LIKE '%".$_POST['s']."%' ";
            break;
            case 4:
                $query_where = " WHERE `salary` LIKE '%".$_POST['s']."%' ";
            break;
            default:
                $query_where = " ";
            break;
        }

    }

    $query .= $query_where;

    if((isset($_POST['n']) and isset($_POST['v'])))
    {
        for ($k = 0; $k < count($nameorder); $k++) {
            switch ($valorder[$k]) {
                case (0):
                    break;
                case(1):
                    $query2 .= " $nameorder[$k]";
                    break;
                case(2):
                    $query2 .= "  $nameorder[$k] DESC";
                    break;
            }
            if ($k != count($nameorder) - 1 and $valorder[$k] != 0) {
                $query2 .= ",";
            }
            $param = str_replace($nameorder[$k], $valorder[$k], $param);

        }


        if ($query2 != "") {
            $query .= "ORDER BY " . $query2;
        }

        if (substr($query, -1) == ",") {
            $query = substr($query, 0, -1);
        }


        $param = str_replace($paramarray, 0, $param);
        $par = explode(",", $param);
    }
    else{
        $par = array(0,0,0,0);
    }

        sortpost($par[0], $par[1], $par[2], $par[3]);

    $all = mysql_query($query);

    if (mysql_num_rows($all) != 0)
    {
       while($allemployee = mysql_fetch_array($all))
       {
            echo "
                <tr>
                    <td id='paddingtd'>".$allemployee[fio]."</td>
                    <td id='paddingtd'>".$allemployee[name_post]."</td>
                    <td id='paddingtd' align='center'>".$allemployee[datestart]."</td>
                    <td id='paddingtd'>".$allemployee[salary]."</td>
                </tr>
            ";
        }        
    }
    
    echo "</table></div>";

    exit();
}
// если сняли всю сортировку
if(isset($_POST['nos'])){

    include ($_SERVER['DOCUMENT_ROOT'].'/test/db_function.php');

    db_connect();

    $query .= "SELECT id_employee, fio, name_post, DATE_FORMAT(datestart, '%d.%m.%Y') AS datestart, salary FROM employee INNER JOIN post ON employee.`id_post`=post.`id_post` ";

    sortpost(0,0,0,0);
    $all = mysql_query($query);

    if (mysql_num_rows($all) != 0)
    {
        while($allemployee = mysql_fetch_array($all))
        {
            echo "
                <tr>
                    <td id='paddingtd'>".$allemployee[fio]."</td>
                    <td id='paddingtd'>".$allemployee[name_post]."</td>
                    <td id='paddingtd' align='center'>".$allemployee[datestart]."</td>
                    <td id='paddingtd'>".$allemployee[salary]."</td>
                </tr>
            ";
        }
    }

    echo "</table></div>";

    exit();
}

include ('db_function.php');

echo "<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title> </title>
<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\" />

<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"css/bootstrap.css\" />

<!--Ajax-->
<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js\"></script>
<script language='JavaScript' src='js/ajax.js'></script>
<script>
$( document ).ready(function() {
   if($('#search_input').val().trim() == '')
   {
        history.pushState(null,null,'http://localhost/test/sort.php');
   }
});
</script>
</head><body>";

echo "<div class='row'><div class='container'>
    <div class='main_div col-lg-12 col-md-12 col-sm-12 col-xs-12'>";

echo '<div class="top_line">
        <div style="padding: 0px; float: left; width: 60px;">
        <a href="index.php" class="home_page">
            <span class="glyphicon glyphicon-home"></span>
        </a>
        </div>';

// получаем значение поля поиска и select по чем ищем
if(isset($_GET['s']) and isset($_GET['num']))
{
    $s_q = $_GET['s'];
    $n_q = $_GET['num'];
}
else{
    $s_q = "";
    $n_q = 1;
}


echo "<div class='sort_by col-lg-8 col-md-7 col-sm-5 col-xs-12'>";
echo '<span> Поиск по</span> <select name="search_query" id="search_query">
        <option value="1" '.($n_q==1?"selected":"").'>ФИО</option>
        <option value="2" '.($n_q==2?"selected":"").'>Должности</option>
        <option value="3" '.($n_q==3?"selected":"").'>Дате</option>
        <option value="4" '.($n_q==4?"selected":"").'>Окладу</option>
      </select></div>';
echo '<div class="search_div col-lg-3 col-md-4 col-sm-5 col-xs-12" >';
echo '<div class="search_input_div">
        <input name="query" id="search_input" type="text" autocomplete="off" value="'.$s_q.'"
            placeholder="Найти..." onkeypress="if(event.charCode == 13){searchquery();}"></div>
  <div class="search_but"><a onclick="searchquery();" id="search"> Искать</a></div>';

echo "</div></div>";

// формируем значения если есть соритровка
$fio = 0;
$post = 0;
$date = 0;
$salary = 0;

if (isset($_GET['n']))
{
    $nameorder = $_GET['n'];
    $valorder = $_GET['v'];
    $fio = 0; $post = 0; $date=0; $salary = 0;
    for ($k =0; $k < count($nameorder); $k++)
    {   
        switch($nameorder[$k])
        {
            case("fio"):
                $fio = $valorder[$k];
            break;
            case("name_post"):
                $post = $valorder[$k];
            break;
            case("datestart"):
                $date = $valorder[$k];
            break;
            case("salary"):
                $salary = $valorder[$k];
            break;
            default:
            break;
        }
    }

}
 sortpost($fio,$post,$date,$salary);
// сортировка по результатам поиска
if((isset($_GET['n']) and isset($_GET['v'])) or isset($_GET['s']))
{
    $nameorder = $_GET['n'];
    $valorder = $_GET['v'];
    $param = "fio,name_post,datestart,salary";
    $paramarray = array("fio","name_post","datestart","salary");

    $query .= "SELECT id_employee, fio, name_post, DATE_FORMAT(datestart, '%d.%m.%Y') AS datestart, salary FROM employee INNER JOIN post ON employee.`id_post`=post.`id_post` ";

    if(isset($_GET['s']) and isset($_GET['num']))
    {
        switch($_GET['num']){
            case 1:
                $query_where = " WHERE `fio` LIKE '%".$_GET['s']."%' ";
                break;
            case 2:
                $query_where = " WHERE `name_post` LIKE '%".$_GET['s']."%' ";
                break;
            case 3:
                $query_where =" WHERE DATE_FORMAT(datestart, '%d.%m.%Y') LIKE '%".$_GET['s']."%' ";
                break;
            case 4:
                $query_where = " WHERE `salary` LIKE '%".$_GET['s']."%' ";
                break;
            default:
                $query_where = " ";
                break;
        }

    }
    else
        $query_where = "  ";

    $query .= $query_where;
    if((isset($_GET['n']) and isset($_GET['v'])))
    {
        for ($k =0; $k < count($nameorder); $k++)
        {
            switch ($valorder[$k])
            {
                case (0):
                    break;
                case(1):
                    $query2 .= " $nameorder[$k]";
                    break;
                case(2):
                    $query2 .= "  $nameorder[$k] DESC";
                    break;
            }
            if ($k != count($nameorder)-1 and $valorder[$k] != 0)
            {
                $query2 .= ",";
            }
            $param = str_replace($nameorder[$k],$valorder[$k], $param);
        }
        if ($query2 != "")
        {
            $query .= "ORDER BY ".$query2;
        }

        if (substr($query, -1) == ",")
        {
            $query = substr($query, 0, -1);
        }



        $param = str_replace($paramarray, 0, $param);
        $par = explode(",",$param);
    }
    $all = mysql_query($query);



    if (mysql_num_rows($all) != 0)
    {
        while($allemployee = mysql_fetch_array($all))
        {
            echo "
                <tr>
                    <td id='paddingtd'>".$allemployee[fio]."</td>
                    <td id='paddingtd'>".$allemployee[name_post]."</td>
                    <td id='paddingtd' align='center'>".$allemployee[datestart]."</td>
                    <td id='paddingtd'>".$allemployee[salary]."</td>
                </tr>
            ";
        }
    }

    echo "</table></div>";
}
else
{
    $all = mysql_query("SELECT id_employee, fio, name_post, DATE_FORMAT(datestart, '%d.%m.%Y') AS datestart, salary FROM employee INNER JOIN post ON employee.`id_post`=post.`id_post`");
    if (mysql_num_rows($all) != 0)
    {

        while($allemployee = mysql_fetch_array($all))
        {
            echo "
            <tr>
                <td id='paddingtd'>".$allemployee[fio]."</td>
                <td id='paddingtd'>".$allemployee[name_post]."</td>
                <td id='paddingtd' align='center'>".$allemployee[datestart]."</td>
                <td id='paddingtd'>".$allemployee[salary]."</td>
            </tr>
        ";
        }
    }
}
echo "</table></div>";

echo "</div></div></div>";

echo "</body></html>";

?>