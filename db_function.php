<?php
// подключение к БД
function db_connect()
{
	$host = 'localhost';
	$user = 'root';
	$pasw = '';
	$db = 'company';
	
	$connection = mysql_connect($host, $user, $pasw);   
     
    mysql_query("SET NAMES utf8");
	if (!$connection || !mysql_select_db($db,$connection))
	{
		echo mysql_error();
		return false;
	}
	return $connection;
}

//древовидное отображение списка сотрудников компании
function showemployee($levelup, $whatlevel)
{
    $resultconnect = db_connect();
    if ($resultconnect == false)
    {
        echo "Не удалось подключиться к базе данных";
    }
    else
    {
        if($whatlevel == 1)
        {
            $employee = mysql_query("SELECT id_employee, fio, name_post FROM employee INNER JOIN post ON employee.`id_post`=post.`id_post` WHERE employee.id_post = '1'");
        }
        else
        {
            $employee = mysql_query("SELECT id_employee, fio, name_post FROM employee INNER JOIN post ON employee.`id_post`=post.`id_post` WHERE id_boss = $levelup");
        }

        if (mysql_num_rows($employee) !=0)
        {
            echo "<ul type='none' id='main_ul'>";
            while ($bigboss = mysql_fetch_array($employee))
            {
               list($count_woker) = mysql_fetch_array(mysql_query("SELECT count(id_employee) FROM employee WHERE id_boss=".$bigboss['id_employee']));

               if ($count_woker != 0)
               {
                    echo "<li> <a class='link_li' id='".$bigboss[id_employee]."'  onclick='checkdivemployee(".$bigboss['id_employee'].")'>
                    <span class='arrow item_".$bigboss[id_employee]."'>+</span><span class='fio_boss'>".$bigboss[fio]."</span><span class='post_employee'> (".$bigboss[name_post].")</span></a></li>";
                    echo '<div id="div_'.$bigboss[id_employee].'" style="display:none;"></div>';
               }
               else
               {
                    echo "<li style='padding-left:15px; cursor: default; '><span class='fio_boss'>".$bigboss[fio]."</span><span class='post_employee'> (".$bigboss[name_post].")</span></li>";
               }
            }    
          
                
            echo "</ul>";
        }
        else
        {
            if($whatlevel == 1)
                echo "<p class='no_result_search'>В компании нет генерального директора!</p>";
            else
            {
                return 0;
            }
        }
        
    }
}

//отображение полей сортировки
function sortpost($fio, $idpost, $datebegin, $salary)
{
    db_connect();

    $clear_url = 'http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'],'?'));
    echo "<div id='sortpart' class='table-responsive'>
    <table border='1' align='center' width='100%' class='table table-striped table-bordered'>
        <tr align='center' >
            <td id='paddingtd'><b>ФИО</b></td>
            <td id='paddingtd'><b> Должность</b></td>
            <td id='paddingtd'><b> Дата приема на работу</b></td>
            <td id='paddingtd'><b> Оклад</b></td>
        </tr>   <tr align='center'>     ";

    $arr_field = array("fio", "name_post", "datestart", "salary");
    $arr_param = array($fio, $idpost, $datebegin, $salary);

    $text = "";
    $i = 0;
    while($i<4)
    {
        $text .= "
        <td id='paddingtd'>
        <select name='f".$arr_field[$i]."' id='td_".$arr_field[$i]."' onchange='filter()'>";
        if($i>1)
        {
            if($arr_param[$i] == 0)
            {
                $text .= " <option value='0' selected></option>";
            }
            else
            {
                $text .= "<option value='0'></option>";
            }
            if($arr_param[$i] == 1)
            {
                $text .="<option value='1' selected>по возрастанию</option>";
            }
            else
            {
                $text .= "<option value='1'>по возрастанию</option>";
            }
            if($arr_param[$i] == 2)
            {
                $text .= "  <option value='2' selected>по убыванию</option>";
            }
            else
            {
                $text .= " <option value='2'>по убыванию</option>";
            }
        }
        else
        {
            if($arr_param[$i] == 0)
            {
                $text .= " <option value='0' selected></option>";
            }
            else
            {
                $text .= "<option value='0'></option>";
            }
            if($arr_param[$i] == 1)
            {
                $text .= "<option value='1' selected>по алфавиту</option>";
            }
            else
            {
                $text .= "<option value='1'>по алфавиту</option>";
            }
            if($arr_param[$i] == 2)
            {
                $text .= "  <option value='2' selected>по убыванию</option>";
            }
            else
            {
                $text .= " <option value='2'>по убыванию</option>";
            }
        }

        $text .= "
                </select>
            </td>
       ";
        $i++;
    }
    $text .= " </tr>";
    echo $text;
    db_connect();
}

//случайная дата приема на работу)
function gen_data()
{
    $d = rand(1,31);
    $m = rand(1,2);
    $y = rand(2010, 2015);
    if ($d < 10)
    {
        $d = "0".$d;
    }
    $m = "0".$m;
    $strdate = $y.".".$m.".".$d;
    return $strdate;
}

//заполнение быз данных
function adddata()
{
   db_connect();
   $maxidemployee = mysql_query("SELECT MAX(id_employee) AS maximum FROM employee");
   $maxidemployee = mysql_fetch_array($maxidemployee);
   $newidemp = $maxidemployee[maximum] + 1;
   $maxidpost = mysql_query("SELECT MAX(id_post) AS maxpost FROM post");
   $rang = mysql_query("SELECT id_post, rang FROM post ORDER BY id_post");
  
   $prev = 0;
   $j = 0;
    $i=0;
   while ($ran = mysql_fetch_array($rang))
   {

        if ($i >= 1)
        {
            $prev = $curr;
        }
        $curr = $ran[rang];
        if ( $prev != $curr)
        {
            $rangmass[] = $ran[id_post];
            $j++;
        }
       $i++;
   }
   
   $queryinsert = "INSERT INTO `employee` (`id_employee`, `fio`, `id_post`,`datestart`,`salary`, `id_boss`, `counter`) 
   VALUES (".$newidemp.", 'Сотрудник1', ".$rangmass[0].", '".gen_data()."',".rand(10900, 15000).",0,100)";

  
   $idbossstart = $newidemp + 1; 
   //rang 2
   for ($i = 2; $i < 102; $i++)
   {
        $str_1 .= ",(".(++$newidemp).", 'Сотрудник".($i)."', ".rand($rangmass[1],$rangmass[2]-1).", '".gen_data()."',".rand(8000, 10000).",".($idbossstart-1).",1)";

   }

   $idbossend = $newidemp;
   //rang 3
   for ($i; $i<603; $i++)
   {
        $str_1 .= ",(".(++$newidemp).", 'Сотрудник".($i)."', ".rand($rangmass[2],$rangmass[3]-1).", '".gen_data()."',".rand(6000, 8000).",".rand($idbossstart,$idbossend).",1)"; 
   }

   //rang 4
   $idbossstart = $idbossend;
   $idbossend = $newidemp;
   for ($i; $i<903; $i++)
   {
        $str_1 .= ",(".(++$newidemp).", 'Сотрудник".($i)."', ".rand($rangmass[3],$rangmass[4]-1).", '".gen_data()."',".rand(5000, 7000).",".rand($idbossstart,$idbossend).",1)"; 
   }
   //rang 5
   $idbossstart = $idbossend;
   $idbossend = $newidemp;
   for ($i; $i<1500; $i++)
   {
        $str_1 .= ",(".(++$newidemp).", 'Сотрудник".($i)."', ".rand($rangmass[4],$maxidpost[maxpost]).", '".gen_data()."',".rand(5000, 7000).",".rand($idbossstart,$idbossend).",1)";
   }
   $queryinsert .= $str_1;
   mysql_query($queryinsert);
   
   if (mysql_errno() == 0)
   {
        echo "<p class='resultadd'>Данные успешно добавлены</p>";
   }
   else
   {
        echo "<p class='resultadd'>".mysql_errno()."</p>";
   }
}
?>