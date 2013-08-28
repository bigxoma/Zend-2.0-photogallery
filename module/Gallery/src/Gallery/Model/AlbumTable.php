<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 25.07.13
 * Time: 16:05
 * To change this template use File | Settings | File Templates.
 */
namespace Gallery\Model;

use Zend\Db\TableGateway\TableGateway;
class AlbumTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getAlbum($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Не могу найти альбом с id = $id");
        }
        return $row;
    }

    public function saveAlbum(Album $album)
    {
        $data = array(
            'title' => $album->title,
            'description'  => $album->description,
            'author'  => $album->author,
            'mail'  => $album->mail,
            'phone'  => $album->phone,
        );

        $id = (int)$album->id;
        if ($id == 0) {
            $data['created'] = date("Y-m-d H:i");
            $this->tableGateway->insert($data);
        } else {
            if ($this->getAlbum($id)) {
                $data['update'] = date("Y-m-d H:i");
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Форма не существует');
            }
        }
    }

    public function deleteAlbum($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }

    public function updateAlbum($data, $id)
    {
       // print_r($data);
       // print_r($id);
       // exit;
        $this->tableGateway->update($data, $id);
    }
}