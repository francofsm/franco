<!DOCTYPE html>
<html lang="en">
<head>
    <link href="<?php echo Yii::app()->theme->baseUrl;?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->theme->baseUrl;?>/css/responsive.min.css" rel="stylesheet">
    <style type="text/css">
            
        .titulo{ text-align: center; font-weight: bold; } 
        /*td {  padding-left: 5px; padding-top: 2px; padding-bottom: 2px; text-align: justify; font-size: 15px; } */
        table { margin-top: 20px; font-size: 13px; }
        .pie_pagina{ font-size: 12px; }
    </style>   
</head>
<body> 

  <div class="titulo">   

  </div> 

  <table>
    <tr>
      <td style="padding-right: 75px;">PARA:</td>
      <td style="padding-left: 75px;">DE:</td>
    </tr>

  </table>

  <div>

  </br>


 



 
  <htmlpagefooter name="myfooter">
  <p class="pie_pagina">  Se informa además que en función de lo previsto en el artículo N°131 del Código Procesal Penal.
  Se envía este mismo correo a la Defensoría.-
  Sin otro particular, le saluda atentamente</p>

  </htmlpagefooter>   
  <sethtmlpagefooter name="myfooter" value="on" />
</body>
</html>


