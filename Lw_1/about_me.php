<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>Дудинов Никита Михайлович</p>
    <?php
    $years = "2005";
    $month = "01";
    $date = "06";
    if($month > date('m') || $month == date('m') && $date > date('d'))
      $age = date('Y') - $years - 1; 
    else
      $age = date('Y') - $years;
     echo $age, ' лет';
    ?>
    <p>Мои интересы:</p>   
    <ul>
        <?php 
        $interests= array('Футбол','Автомобили', 'Мотоциклы', 'Программирование', 'Музыка');
        foreach($interests as $item): 
            $str = htmlentities($item, ENT_QUOTES);
            echo "<li>$str</li>";
        endforeach; 
        ?>    
    </ul>
</body>
</html>