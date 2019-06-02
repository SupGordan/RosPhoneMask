<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Загрузить файлы</title>
    <style>
        #error {
            color: red;
        }
        #download-url {
            display: none;
        }
    </style>
</head>
<body>
<form id="file-form" enctype="multipart/form-data" action="post" datatype="">
    <input type="file" id="file-input" name="csv_code">
    <input type="submit" value="Загрузить файл">
</form>
<a id="download-url" href="" download>Скачать данные</a>
<div id="error"> </div>
</body>
</html>
<script>
    var form = document.getElementById('file-form');
    var fileInput = document.getElementById('file-input');
    var downloadButton = document.getElementById('download-url');
    var errorNode = document.getElementById('error');
    form.onsubmit = function(event) {
        event.preventDefault();
        form.style.display = "none";
        errorNode.style.display = "none";
        var file = fileInput.files[0];
        var formData = new FormData();
        formData.append('csv_code', file, file.name);
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                form.style.display = "block";
                var response = JSON.parse(xhr.response);
                if (response.code == 200) {
                    downloadButton.setAttribute('href', response.msg);
                    downloadButton.style.display = "block";
                } else {
                    errorNode.style.display = "block";
                    errorNode.innerHTML = "Код ошибки: " + response.code + " " + response.msg
                }
            }
        };
        xhr.open('POST', 'app/handlers/ajaxCodeImport.php', true);
        xhr.send(formData);
    }
</script>