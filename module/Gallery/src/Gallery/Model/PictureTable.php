<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 26.07.13
 * Time: 13:20
 * To change this template use File | Settings | File Templates.
 * Класс для работы с таблицей Pictures в MySql
 */
namespace Gallery\Model;

use Zend\Db\TableGateway\TableGateway;
use Gallery\Model\UploadPicture;


class PictureTable
{
    protected $tableGateway;
    protected $albumId;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    // Вытащить все строчки
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    // Вытащить фотки принадлежащие альбому
    public function fetchAlbum($albumId)
    {
        $resultSet = $this->tableGateway->select('album = ' . $albumId . ' ORDER BY `added` ASC');
        return $resultSet;
    }

    // Получить следующую и предыдущую фотографию
    public function getPrevNext($pictureObj)
    {
        $pictures = $this->tableGateway->select('`album` = ' . $pictureObj->album . ' ORDER BY `added` ASC');
        $count  = $pictures->count();
        foreach($pictures as $i => $picture)
        {
            // Создаем массив с ключом и ссылкой на файл
            $arrayIds[$i] = $picture->id;
            // В переборе массива нашего альбома находим фотографию
            if($picture->id == $pictureObj->id)
            {
                $nowI = $i;
            }
        }
        if (isset($nowI)){
            switch($nowI)
            {
                // Первая фотка
                case 0:
                {
                    $ids["prev"] = false;
                    $ids["next"] = $arrayIds[$nowI+1];
                    break;
                }
                // Последняя фотка (учитываем, что массив начинается с 0)
                case $count-1:
                {
                    $ids["prev"] = $arrayIds[$nowI-1];
                    $ids["next"] = false;
                    break;
                }
                // Фотка где-то посередине
                default:
                {
                    $ids["prev"] = $arrayIds[$nowI-1];
                    $ids["next"] = $arrayIds[$nowI+1];
                    break;
                }
            }// switch
        }// if
        if (!isset($ids))
        {
            $ids = false;
        }
        return $ids;
    }// func getPrevNext

    // Количество фоток с id данного альбома
    public function amountPictures($albumId)
    {
        $id  = (int) $albumId;
        $rowSet = $this->tableGateway->select('`album` = ' . $id);
        $row = $rowSet->count();
        return $row;
    }

    // Дата последней загруженной фотографии
    public function lastPicture($id)
    {

        //$rowSet = $this->tableGateway->select(function (Select $select) {
        //    $select->where("album = 6")->order('added DESC')->limit(1);
        //});
        $rowSet = $this->tableGateway->select("`album` = $id ORDER BY `added` DESC LIMIT 1");
        $lastPicture = $rowSet->current();
        $last['added'] = $lastPicture->added;
        $last['src'] = $lastPicture->src;
        return $last;
    }

    // Вытащить одну строку фотки
    public function getPicture($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Не могу найти фото с id $id");
        }
        return $row;
    }

    // Вставить новую строку фотки
    public function savePicture(Picture $picture)
    {
        $data = array(
            'title' => $picture->title,
            'album' => $picture->album,
            'src'  => $picture->src,
            'address' => $picture->address,
            'added'  => date("Y-m-d H:i"),
        );
        $this->tableGateway->insert($data);
    }

    // Удаление фотки
    public function deletePicture($id)
    {
        $picture = $this->getPicture($id);
        $uploadPicture = new UploadPicture();
        $uploadPicture->delete($picture->src);
        $this->tableGateway->delete(array('id' => $id));
    }

    // Удаление фоток из конкретного альбома
    public function deletePicturesFrom($albumId)
    {
        $uploadPicture = new UploadPicture();
        $pictures = $this->tableGateway->select(array('album'=>$albumId));
        $this->tableGateway->delete(array('album'=>$albumId));
        foreach($pictures as $picture)
        {
            $uploadPicture->delete($picture->src);
        }
    }
}