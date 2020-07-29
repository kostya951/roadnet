<?php
    $config = require('config.php');
?>
<div>
    <a href="/create.php" style="display: block">Создать</a>
</div>
<div>
    <form>
        <select name="field">
            <option value="rate">По рейтингу</option>
            <option value="created">По дате создания</option>
        </select>
        <select name="direction">
            <option value="ASC">По возрастанию</option>
            <option value="DESC">По убыванию</option>
        </select>
        <button type="submit">Сортировать</button>
    </form>
</div>
<div class="content">
    <table>
        <thead>
        <td>Id</td>
        <td>Никнейм</td>
        <td>Рейтинг</td>
        <td>Описание</td>
        <td>Дата создания</td>
        <td>Фото</td>
        <td>Показать</td>
        </thead>
        <tbody>

        </tbody>
    </table>
    <div class="pagination">

    </div>
</div>
<script>
    function showMain(pages){
        var buttons = drawPagination(pages);
        for(var i=0; i< buttons.length;i++){
            var btn = buttons[i];
            btn.addEventListener('click', evt => {
                evt.preventDefault();
                drawList(pages[evt.srcElement.innerText-1]);
            });
        }
    }
    function fillPages(pages,json){
        while(json.length){
            var page = json.splice(0,<?=$config['pagination']?>);
            pages.push(page);
        }
    }
    function drawList(page){
        var tbody = document.querySelector('tbody');
        tbody.innerHTML='';
        for(var i=0; i<page.length;i++){
            var tr = document.createElement('tr')
            var id = document.createElement('td');
            id.innerText = page[i].id;
            var nickname = document.createElement('td');
            nickname.innerText = page[i].nickname;
            var rate = document.createElement('td');
            rate.innerText = page[i].rate;
            var description = document.createElement('td');
            description.innerText = page[i].description;
            var created = document.createElement('td');
            created.innerText = page[i].created;
            var photo = document.createElement('td');
            var img = document.createElement('img');
            img.setAttribute('src', pages[0][i].photo);
            img.setAttribute('width','100px');
            img.setAttribute('height','100px');
            var view = document.createElement('td');
            var aView = document.createElement('a');
            aView.innerText='Показать';
            aView.setAttribute('href','/view.php?id='+page[i].id);
            view.appendChild(aView);
            photo.appendChild(img);
            tr.appendChild(id);
            tr.appendChild(nickname);
            tr.appendChild(rate);
            tr.appendChild(description);
            tr.appendChild(created);
            tr.appendChild(photo);
            tr.appendChild(view);
            tbody.appendChild(tr);
        }
    }

    function drawPagination(pages){
        var buttons = new Array();
        var pagination = document.querySelector('.pagination');
        pagination.innerHTML='';
        for(var i=0;i<pages.length;i++){
            var button = document.createElement('a');
            button.setAttribute('href','#');
            button.setAttribute('class','pagination');
            button.setAttribute('style','margin-right:10px');
            button.innerText=i+1;
            pagination.appendChild(button);
            buttons.push(button);
        }
        return buttons;
    }

    var pages = new Array();
    document.querySelector('button').addEventListener('click',evt=>{
        evt.preventDefault();
       var request = new XMLHttpRequest();
       request.open('POST','/order.php',false);
       var form = document.querySelector('form');
       var formData = new FormData(form);
       var json = {
           'direction':formData.get('direction'),
           'field':formData.get('field')
       }
       var query = JSON.stringify(json);
       request.send(query);
       if(request.status==200){
           console.log('sucсess');
           var json = JSON.parse(request.responseText);
           console.log(json);
           pages = new Array();
           fillPages(pages,json);
           drawList(pages[0]);
           showMain(pages);
       }else{
           console.log('error');
           alert("Ошибка запроса сортировки");
       }
    });

    window.onload = function(){
        var request = new XMLHttpRequest();
        request.open('GET','/list.php',false);
        request.send();
        if(request.status==200){
            console.log('sucсess');
            var json = JSON.parse(request.responseText);
            console.log(json);
            fillPages(pages,json);
        }else{
            console.log('error');
            alert("Ошибка запроса списка отзывов");
        }

        drawList(pages[0]);

        showMain(pages);
    };
</script>