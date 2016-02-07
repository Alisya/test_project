<?php 

if (isset($_POST['querysearch']))
{
    include ($_SERVER['DOCUMENT_ROOT'].'/test/db_function.php');
  
    db_connect();
    
    $querysearch = $_POST['querysearch'];
  
    $querysearch = strip_tags($querysearch);
	$querysearch = trim($querysearch);
    $querysearch = mysql_real_escape_string($querysearch);
    
    if ($querysearch != '')
    {
        if ($_POST['whatsearch'] == 1)
        {
            $searchquery = mysql_query("SELECT `fio`,`name_post`, `salary`, DATE_FORMAT(datestart, '%d.%m.%Y') AS datestart  
            FROM `employee` INNER Join post ON `employee`.`id_post`=`post`.`id_post`
            WHERE `fio` LIKE '%".$querysearch."%' OR `name_post` LIKE '%".$querysearch."%'");
        
        }
        else
        {
            $searchquery = mysql_query("SELECT `fio`,`name_post`, `salary`, DATE_FORMAT(datestart, '%d.%m.%Y') AS datestart  
            FROM `employee` INNER Join post ON `employee`.`id_post`=`post`.`id_post`
            WHERE `salary` LIKE '%".$querysearch."%' OR DATE_FORMAT(datestart, '%d.%m.%Y') LIKE '%".$querysearch."%'");
        
        }   
        
      
        if (mysql_num_rows($searchquery) != 0)
        {
            sortpost(0, "", 0);
            
            for($i=0; $i< mysql_num_rows($searchquery); $i++)
            {
                $resultsearch = mysql_fetch_array($searchquery);
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
            echo "Поиск не дал результатов!";
        }
        
            
    }
    
    exit();
}

if (isset($_POST['filterpost']))
{
    include ($_SERVER['DOCUMENT_ROOT'].'/test/db_function.php');
    
    echo "<script type=\"text/javascript\" src=\"calendar/tcal.js\"></script>
        <link rel=\"stylesheet\" type=\"text/css\" href=\"calendar/tcal.css\" />";
    
    db_connect();
    $idpost = $_POST['filterpost'];
    $work = $_POST['datework'];
    $salary = $_POST['salary'];
   // echo time($work);
    sortpost($idpost, $work, $salary);
    if ($work != '')
    {
        $datepart = explode(".", $work);
        $date1 = mktime (0,0,0, $datepart[1], $datepart[0], $datepart[2]);
       // echo $date1;
    }
    
    
    if ($idpost !=0)
    {
        if ($work != '')
        {
            $query = "SELECT id_employee, fio, name_post, DATE_FORMAT(datestart, '%d.%m.%Y') AS datestart, salary FROM employee INNER JOIN post ON employee.`id_post`=post.`id_post` WHERE post.id_post=$idpost AND UNIX_TIMESTAMP(`datestart`)=$date1";
            
            if ($salary !=0)
            {
                switch ($salary)
                {
                    case (1): 
                        $query .= " ORDER BY salary";
                    break;
                    case (2): 
                        $query .= " ORDER BY salary DESC";
                    break;
                }                
            } 
        }
        else
        {
            if ($salary !=0)
            {
                switch ($salary)
                {
                    case (1): 
                        $query = "SELECT id_employee, fio, name_post, DATE_FORMAT(datestart, '%d.%m.%Y') AS datestart, salary FROM employee INNER JOIN post ON employee.`id_post`=post.`id_post` WHERE post.id_post=$idpost ORDER BY salary";
                    break;
                    case (2): 
                        $query = "SELECT id_employee, fio, name_post, DATE_FORMAT(datestart, '%d.%m.%Y') AS datestart, salary FROM employee INNER JOIN post ON employee.`id_post`=post.`id_post` WHERE post.id_post=$idpost ORDER BY salary DESC";
                    break;
                }
                
            } 
        }
        
        if ($work == '' and $salary == 0)
        {
            $query = "SELECT id_employee, fio, name_post, DATE_FORMAT(datestart, '%d.%m.%Y') AS datestart, salary FROM employee INNER JOIN post ON employee.`id_post`=post.`id_post` WHERE post.id_post=$idpost";
        }  
        
              
        $all = mysql_query($query);        
    }   
    else
    {
        
        if ($work != '')
        {
            $query = "SELECT id_employee, fio, name_post, DATE_FORMAT(datestart, '%d.%m.%Y') AS datestart, salary FROM employee INNER JOIN post ON employee.`id_post`=post.`id_post` WHERE UNIX_TIMESTAMP(`datestart`)=$date1";
            
            if ($salary !=0)
            {
                switch ($salary)
                {
                    case (1): 
                        $query .= " ORDER BY salary";
                    break;
                    case (2): 
                        $query .= " ORDER BY salary DESC";
                    break;
                }                
            } 
        }
        else
        {
            if ($salary !=0)
            {
                switch ($salary)
                {
                    case (1): 
                        $query = "SELECT id_employee, fio, name_post, DATE_FORMAT(datestart, '%d.%m.%Y') AS datestart, salary FROM employee INNER JOIN post ON employee.`id_post`=post.`id_post` ORDER BY salary";
                    break;
                    case (2): 
                        $query = "SELECT id_employee, fio, name_post, DATE_FORMAT(datestart, '%d.%m.%Y') AS datestart, salary FROM employee INNER JOIN post ON employee.`id_post`=post.`id_post` ORDER BY salary DESC";
                    break;
                }
                
            } 
        }
 
        if ($work == '' and $salary == 0)
        {
            $query = "SELECT id_employee, fio, name_post, DATE_FORMAT(datestart, '%d.%m.%Y') AS datestart, salary FROM employee INNER JOIN post ON employee.`id_post`=post.`id_post`";
        }
        
        
        $all = mysql_query($query);
    }
    
        if (mysql_num_rows($all) != 0)
        {
           for($i=0; $i< mysql_num_rows($all); $i++)
           {
                $allemployee = mysql_fetch_array($all);
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
    
    echo "</table>";

    exit();
}


include ('db_function.php');


echo "<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title> </title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\" />

<!--Ajax-->
<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js\"></script>
<script language='JavaScript' src='js/ajax.js'></script>

</head><body>";
echo '<input type="radio" name="drink1" id="drink1" value="1" checked> 
     <label for="drink1" value="" id="">Поиск по ФИО и Должности</label>
   <input type="radio" name="drink1" id="drink2" value="2"> 
   <label for="drink2" value="" id="">Поиск по Дате и Окладу</label><br><br>';
echo "<input name='query' id='search_input' type='text'  size='51' maxlength='50' autocomplete='off'>
    <a href='#' onclick='searchquery();'> Искать</a><br><br>";
    
 sortpost(0, "", 0);
 
 $all = mysql_query("SELECT id_employee, fio, name_post, DATE_FORMAT(datestart, '%d.%m.%Y') AS datestart, salary FROM employee INNER JOIN post ON employee.`id_post`=post.`id_post`");
if (mysql_num_rows($all) != 0)
{
    
    for($i=0; $i< mysql_num_rows($all); $i++)
    {
        $allemployee = mysql_fetch_array($all);
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

echo "</body></html>";

?>