//обработка адреса страницы
function changeHash(v,text,p,idtype, familyfil,age, iddev, idpub) 
{
    try 
    {
      // history.replaceState(null,null,'index.php?view='+ v +'&page='+ p);
      if (text != '')
      {
        history.pushState(null,null,'index.php?view='+ v+ '&text='+ text +'&page='+ p+idtype+familyfil+age);
      }
      else
      {
        if (iddev != '')
        {
            history.pushState(null,null,'index.php?view='+ v +'&id='+iddev+'&page='+ p+idtype+familyfil+age);
        }
        else
        {
            history.pushState(null,null,'index.php?view='+ v +'&page='+ p+idtype+familyfil+age);            
        }
        
        if (idpub != '')
        {
            history.pushState(null,null,'index.php?view='+ v +'&id='+idpub+'&page='+ p+idtype+familyfil+age);
        }
        else
        {
            history.pushState(null,null,'index.php?view='+ v +'&page='+ p+idtype+familyfil+age);            
        }
        
            
      }
       
    }
    catch(e) 
    {
       location.hash = 'p'+p;
    }

}


// иерархическое дерево
function send(divid)
{

    var result = 'divv='+ divid; 
    var whatdiv = "#div_"+divid;
    
  // Отсылаем паметры
       $.ajax
       ({
            type: "POST",
            url: "employee.php",
            data: result,
            // Выводим то что вернул PHP
            success: function(html) 
            {
                //предварительно очищаем нужный элемент страницы
                $(whatdiv).empty();
                //и выводим ответ php скрипта
                $(whatdiv).append(html);

            }                
        });

}


// сортировка по фио, должности дате и зарплате
function filter()
{
    var post_fio = $("#td_fio").val();
    var post_post = $("#td_name_post").val();
    var post_date = $("#td_datestart").val();
    var post_salary = $("#td_salary").val();

    var querysearch = $("#search_input").val().trim();
    var radio = $('#search_query').val();

    var www1 = "http://localhost/test/sort.php?";
    var www = "";
    var sym = "&";
    if(post_fio != 0){
       // var how_fio = $("#td_fio").attr("name");
        www += sym+"n[]=fio&v[]="+post_fio;
        /*sym = "&";*/
    }

    if(post_post != 0){
        www += sym+"n[]=name_post&v[]="+post_post;
        /*sym = "&";*/
    }

    if(post_date != 0){
        www += sym+"n[]=datestart&v[]="+post_date;
       /* sym = "&";*/
    }

    if(post_salary != 0){
        www += sym+"n[]=salary&v[]="+post_salary;
        /*sym = "&";*/
    }
    if((post_salary == 0) && (post_date == 0) && (post_post==0) && (post_fio==0))
    {
        sym = "";
    }
    else{ sym = "&";}

    if(querysearch != "")
    {
        www += sym+"s="+querysearch+"&num="+radio;
    }

    var result = www.replace("&","");

    if(www == ""){
        result = "nos=1";
    }

    var url = www1+www;
    // alert(result);
    history.pushState(null,null,url);
    
    // Отсылаем паметры
   $.ajax
   ({
        type: "POST",
        url: "sort.php",
        data: result,
        // Выводим то что вернул PHP
        success: function(html) 
        {
            //предварительно очищаем нужный элемент страницы
            $("#sortpart").empty();
            //и выводим ответ php скрипта
            $("#sortpart").append(html);

        }                
    });
}

// поиск по всем полям
function searchquery()
{
    var querysearch = $("#search_input").val().trim();
    var radio = $('#search_query').val();


    if (querysearch == '')
        querysearch = 0;

        var result = 'qs='+ querysearch +'&ws='+radio;

        var url = "http://localhost/test/sort.php?"+result;
        history.pushState(null,null,url);
       // alert(result);
        
        // Отсылаем паметры
       $.ajax
       ({
            type: "POST",
            url: "sort.php",
            data: result,
            // Выводим то что вернул PHP
            success: function(html) 
            {
                //предварительно очищаем нужный элемент страницы
                $("#sortpart").empty();
                //и выводим ответ php скрипта
                $("#sortpart").append(html);

            }                
        });
    

}


function adddatatobase()
{
    
    var result = 'add=' + 1; 
    //alert(result);
    
    // Отсылаем паметры
   $.ajax
   ({
        type: "POST",
        url: "index.php",
        data: result,
        // Выводим то что вернул PHP
        success: function(html) 
        {
            //предварительно очищаем нужный элемент страницы
            $("#resultadd").empty();
            //и выводим ответ php скрипта
            $("#resultadd").append(html);

        }                
    });

}

function checkdivemployee(bigboss_id)
{
    var displayblock = $("#div_"+bigboss_id).css("display");
    if( displayblock == "block")
    {
        $("#div_"+bigboss_id).css({"display":"none"});
        $(".item_"+bigboss_id).text("+");
    }
    else
    {
        $("#div_"+bigboss_id).css({"display":"block"});
        $(".item_"+bigboss_id).text("-");
    }

    var content_div = $("#div_"+bigboss_id).text();

    if(content_div.length == 0)
    {
        send(bigboss_id);
    }
}