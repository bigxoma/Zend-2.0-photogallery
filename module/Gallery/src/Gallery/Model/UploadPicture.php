<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Alexey Telyupin
 * E-mail Gorillaz@inbox.ru
 * Date: 26.07.13
 * Time: 21:32
 * To change this template use File | Settings | File Templates.
 */

namespace Gallery\Model;


class UploadPicture {

    public $heightMiniIMG; // Высота превьюшки
    public $widthMiniIMG;  // Ширина превьюшки
    public $w; // Размеры квадратной превьюшки
    public $heightIMG; //высота большой фотки
    public $widthIMG; //ширина большой фотки
    public $root; // корень сайта
    public $dir_mini; // директория для превьюшек
    public $dir_img; // директория для изображений
    public $dir_orig; // директория для оригинала
    public $quadrate; // превьюшка квадратная
    public $quality; // коэфицент сжатия
    public $name_post; // name для поля выбора файла !!!!!

    // Настраиваем по умолчанию
    function __construct() {
        $this->heightMiniIMG = 250; // Высота превьюшки
        $this->widthMiniIMG = 250; // Ширина превьюшки
        $this->w = 250; // Размеры квадратной превьюшки
        $this->heightIMG = 800; //высота большой фотки
        $this->widthIMG = 800; //ширина большой фотки
        $this->root = $_SERVER["DOCUMENT_ROOT"]; // Получаем путь к корню сайта
        $this->dir_mini = "/upload/preview/"; // директория для превьюшек
        $this->dir_img = "/upload/picture/"; // директория для изображений
        $this->dir_orig = "/upload/original/"; // директория для оригинала
        $this->quadrate = false; // Отрезаем превью до квадрата
        $this->quality = 90;  // коэфицент сжатия

        // Если не существуют папки, то создаем их
        if (!is_dir($this->root.$this->dir_mini)) mkdir($this->root.$this->dir_mini,0777);
        if (!is_dir($this->root.$this->dir_img)) mkdir($this->root.$this->dir_img,0777);
        if (!is_dir($this->root.$this->dir_orig)) mkdir($this->root.$this->dir_orig,0777);
    }

    // function сжимаем
    function resize($name_post) {
        $this->name_post = $name_post; // name для поля выбора файла !!!!!
        // Если пусто, то выходим
        if ($_FILES[$this->name_post]["name"] == "")
        {
            return NULL;
        }

        // Генерируем имя
        do {
            $newname = time();
            $filetype = $_FILES[$this->name_post]["name"];
            $filetype = basename($filetype);
            $filetype = explode('.', $filetype);
            $filetype = $filetype[count($filetype)-1];
            if ($filetype == 'JPG') $filetype = 'jpg';
            if ($filetype == 'JPEG') $filetype = 'jpg';
            if ($filetype == 'jpeg') $filetype = 'jpg';
            if ($filetype == 'PNG') $filetype = 'png';
            if ($filetype == 'GIF') $filetype = 'gif';
            $newname.='.';
            $newname.= $filetype;
        } while (file_exists($this->root.$this->dir_mini.$newname)); // Вдруг такой файл уже существует, тогда повторяем генерацию имени еще раз

        // В зависимости от формата, создаем слепок изображения
        switch($filetype) {
            case "jpg":
                $src = imagecreatefromjpeg($_FILES[$this->name_post]["tmp_name"]);
                break;
            case "png":
                $src = imagecreatefrompng($_FILES[$this->name_post]["tmp_name"]);
                break;
            case "gif":
                $src = imagecreatefromgif($_FILES[$this->name_post]["tmp_name"]);
                break;
        } // END switch
        $w_src = imagesx($src); // Определяем ширину загруженной картинки
        $h_src = imagesy($src); // Определяем высоту загруженной картинки

        // Если изображение вылазит за границы, то сжимаем
        if (($w_src > $this->widthIMG) || ($h_src > $this->heightIMG)) {
            if ($w_src > $h_src) { // Изображение горизонтальное
                $new_w_src = $this->widthIMG; // Новая ширина равна заданной велечине
                $new_h_src = (($h_src/$w_src)*$this->widthIMG); // Новая высота высчитывается пропорционально
            } else {
                $new_h_src = $this->heightIMG; // Новая высота равна заданной величине
                $new_w_src = (($w_src/$h_src)*$this->heightIMG);	// Новая ширина высчитывается пропорционально
            } // Ориентация изображения
            // Создаем полотно с новыми размерами для нанесения изображения
            $fon_jpg = imagecreatetruecolor($new_w_src, $new_h_src)or die();
            // Наносим изображения
            imagecopyresampled($fon_jpg, $src, 0, 0, 0, 0, $new_w_src, $new_h_src, $w_src, $h_src);

            // В зависимости от формата, создаем изображение и помещаем в директорию
            switch($filetype) {
                case "jpg":
                    imagejpeg($fon_jpg,$this->root.$this->dir_img.$newname,$this->quality);
                    break;
                case "png":
                    imagepng($fon_jpg,$this->root.$this->dir_img.$newname,$this->quality/10);
                    break;
                case "gif":
                    imagegif($fon_jpg,$this->root.$this->dir_img.$newname,$this->quality);
                    break;
            } // END switch

            // Освобождаем память
            imagedestroy($fon_jpg);

        } else {
            // Если маленькая, то просто размещаем
            copy($_FILES[$this->name_post]["tmp_name"], $this->root.$this->dir_img.$newname); // Просто размещаем
        } // END if big image
        // Скидываем оригинал
        move_uploaded_file($_FILES[$this->name_post]["tmp_name"], $this->root.$this->dir_orig.$newname); // Просто размещаем

        // Если нужно получить квадратную превьюшку
        if ($this->quadrate) {
            // Создаем квадратную превьюшку
            $desc = imagecreatetruecolor($this->w,$this->w)or die("Cannot create image"); // Создаем подложку с нужными размерами

            // Если картинка горизонтальная, то вырезаем квадратную середину
            if ($w_src>$h_src) {
                imagecopyresampled($desc, $src, 0, 0,round((max($w_src,$h_src)-min($w_src,$h_src))/2),
                    0, $this->w, $this->w, min($w_src,$h_src), min($w_src,$h_src));
            }
            // вырезаем квадратную верхушку по y, если фото вертикальное
            if ($w_src<$h_src) {
                imagecopyresampled($desc, $src, 0, 0, 0, 0, $this->w, $this->w, min($w_src,$h_src), min($w_src,$h_src));
            }
            // квадратная картинка масштабируется без вырезок
            if ($w_src==$h_src) {
                imagecopyresampled($desc, $src, 0, 0, 0, 0, $this->w, $this->w, $w_src, $w_src);
            }
            // Если нужно получить прямоугольную превьюшку
        } else {
            if ($w_src > $h_src) { // Картинка горизонтальная
                $new_w_src = $this->widthMiniIMG;
                $new_h_src = (($h_src/$w_src)*$this->widthMiniIMG);
            } else { // Картинка вертикальная, все равно ориентируемся по ширине, но можно изменить
                //$new_w_src = $this->widthMiniIMG;
                //$new_h_src = (($h_src/$w_src)*$this->widthMiniIMG);
                $new_h_src = $this->heightMiniIMG;
                $new_w_src = (($w_src/$h_src)*$this->heightMiniIMG);
            }
            // Создаем слепок превьюшки по новым размерам
            $desc = imagecreatetruecolor($new_w_src, $new_h_src)or die();
            // Накладываем изображение на слепок
            imagecopyresampled($desc, $src, 0, 0, 0, 0, $new_w_src, $new_h_src, $w_src, $h_src);
        } // END форма превьюшки

        // Записываем превьюшку в файл
        switch($filetype) {
            case "jpg":
                imagejpeg($desc,$this->root.$this->dir_mini.$newname,$this->quality);
                break;
            case "png":
                $quality = round($this->quality/10);
                imagepng($desc,$this->root.$this->dir_mini.$newname,$quality);
                break;
            case "gif":
                imagegif($desc,$this->root.$this->dir_mini.$newname,$this->quality);
                break;
        } // END switch
        imagedestroy($desc);
        imagedestroy($src);
        // возвращаем название файла
        return $newname;
    } // END function сжимаем

    // Удаление фоток
    public function delete($file)
    {
        if ($file == NULL) return;
        $miniWay = $this->root . $this->dir_mini . $file;
        $imgWay = $this->root . $this->dir_img . $file;
        $origWay = $this->root . $this->dir_orig . $file;
        if (file_exists($miniWay)) {unlink($miniWay);}
        if (file_exists($imgWay)) {unlink($imgWay);}
        if (file_exists($origWay)) {unlink($origWay);}
    }

} // END CLASS