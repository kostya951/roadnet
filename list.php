<?php
$config=require('config.php');
$method = $_SERVER['REQUEST_METHOD'];
if($method=='GET'){
    if(empty($_GET)) {
        $conn = mysqli_connect($config['address'], $config['username'], $config['password'], $config['dbname']);
        $sql = <<<sql
SELECT * FROM roadnet.revision;
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
    }
}