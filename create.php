<?php
$config=require('config.php');
$method = $_SERVER['REQUEST_METHOD'];
if($method=='GET'){
?>
<form method="post">
    <div>
        <label><input type="text" name="nickname">Никнейм</label>
    </div>
    <div>
        <label><select name="rate">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>Рейтинг</label>
    </div>
    <div>
        <label><textarea name="description" cols="50" rows="10"></textarea>Описание</label>
    </div>
    <div>
        <input type="file" name="photo">
    </div>
    <button type="submit">Отправить</button>
</form>
<script>
    var form = document.querySelector('form');
    form.onsubmit = async (e) => {
        e.preventDefault();
        console.log("Отправляю форму");
        var nickname = document.querySelector('input[name=nickname]').value;
        if(nickname.length > 50 || nickname<1){
            alert("Никнейм должен быть от 1 до 50 символов");
            return;
        }
        var select = document.querySelector('select[name=rate]');
        var rate = select.options[select.selectedIndex].value;
        var description = document.querySelector('textarea[name=description]').value;
        if(description.length > 500 || description<1){
            alert("Описание должен быть от 1 до 500 символов");
            return;
        }
        var reader = new FileReader();
        reader.onloadend = async function () {
            var formData={
                'nickname':nickname,
                'rate':rate,
                'description':description,
                'photo':reader.result
            };
            var json = JSON.stringify(formData);
            var request = new XMLHttpRequest();
            request.open('POST','/create.php',false);
            //request.setRequestHeader('Content-Type','application/json');
            request.send(json);
            if(request.status==200){
                console.log("success");
                alert("Отзыв создан!");
                window.location.reload();
            }else{
                console.log("error");
                alert("Ошибка создания отзыва");
            }
        };
        var photo = document.querySelector('input[name=photo]').files[0];
        if(typeof photo =='undefined') {
            alert("Не установлено фото!");
        }else{
            reader.readAsDataURL(photo);
        }
    };
</script>
<?php
}else if($method=='POST') {
    $json = json_decode(file_get_contents('php://input'));
    if(!isset($json)){
        die(header("HTTP/1.0 500 Internal Server Error"));
    }
    $conn = mysqli_connect($config['address'],$config['username'],$config['password'],$config['dbname']);
    if($conn==false){
        die(header("HTTP/1.0 500 Internal Server Error"));
    }
    $created = date('d.m.y h:i:s');
    $sql = <<<sql
INSERT INTO revision(nickname,rate,description,photo,created) 
VALUES('{$json->nickname}','{$json->rate}','{$json->description}','{$json->photo}','{$created}');
sql;

    $result = mysqli_query($conn,$sql);
    if($result==false){
        die(header("HTTP/1.0 500 Internal Server Error"));
    }
    $conn->close();
}
?>
