/**
 * Created with JetBrains PhpStorm.
 * User: Alex
 * Date: 29.07.13
 * Time: 23:28
 * To change this template use File | Settings | File Templates.
 */
$(document).ready(function(){
    function validateSize(fileInput) {
        var fileObj, fileSize, fileType;
        if ( typeof ActiveXObject == "function" ) { // IE

            // не работает
            //fileObj = (new ActiveXObject("Scripting.FileSystemObject")).getFile(fileInput.value);
            alert('Невозможно определить размер и тип файла!');
        }else {
            fileObj = fileInput.files[0];
        }

        fileSize = fileObj.size; // Размер файла в байтах.
        fileType = fileObj.type; // Имя файла
        if(fileSize > (20 * 1024 * 1024)){//20Mb
            alert("Максимальный размер файла: 20 Mb");
            $(fileInput).val("");
        }
        if ( (fileType != 'image/jpeg') && (fileType != 'image/png') && (fileType != 'image/gif'))
        {
            alert('Выберите графический файл!');
            $(fileInput).val("");
        }
    }
    $('#imageFile').change(function(){validateSize(this)});
});