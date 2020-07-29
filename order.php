<?php
$config=require('config.php');
$json = json_decode(file_get_contents('php://input'));
$conn = mysqli_connect($config['address'], $config['username'], $config['password'], $config['dbname']);
$sql = <<<sql
SELECT * FROM roadnet.revision ORDER BY {$json->field} {$json->direction}
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

$json = json_encode($arr);
header('Content-Type: application/json');
echo $json;
?>
