<?php
$config=require('config.php');
$conn = mysqli_connect($config['address'], $config['username'], $config['password'], $config['dbname']);
$sql = <<<sql
SELECT * FROM roadnet.revision WHERE id={$_GET['id']}
sql;
$arr = [];
$result = mysqli_query($conn,$sql);
while($row=mysqli_fetch_array($result)){
    $arr[] = [
        'id'=>$row['id'],
        'nickname'=>$row['nickname'],
        'description'=>$row['description'],
        'rate'=>$row['rate'],
        'created'=>$row['created'],
        'photo'=>$row['photo']
    ];
}
?>
<div>
    <div>Id: <?=$arr[0]['id']?></div>
    <div>Никнейм: <?=$arr[0]['nickname']?></div>
    <div>Описание: <?=$arr[0]['description']?></div>
    <div>Рейтинг: <?=$arr[0]['rate']?></div>
    <div>Создан: <?=$arr[0]['created']?></div>
    <div>Фото: <img width="200px" height="200px" src="<?=$arr[0]['photo']?>"/></div>
</div>
