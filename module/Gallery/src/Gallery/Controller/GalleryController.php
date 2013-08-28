<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 25.07.13
 * Time: 15:51
 * To change this template use File | Settings | File Templates.
 */
namespace Gallery\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Gallery\Model\Album;
use Gallery\Model\Picture;
use Gallery\Form\AlbumForm;
use Gallery\Form\PictureForm;

class GalleryController extends AbstractActionController
{
    protected $albumTable;
    protected $pictureTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'albums' => $this->getAlbumTable()->fetchAll(),
        ));
    }

    // Добавить новый альбом
    public function addAlbumAction()
    {
        $form = new AlbumForm();
        $form->get('submit')->setValue('Добавить альбом');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $album = new Album();
            $form->setInputFilter($album->getInputFilter());

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $album->exchangeArray($form->getData());
                $this->getAlbumTable()->saveAlbum($album);

                // Redirect to list of albums
                return $this->redirect()->toRoute('gallery');
            }
        }
        return array('form' => $form);
    }

    // Правка альбома
    public function editAlbumAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('gallery', array(
                'action' => 'addAlbum'
            ));
        }
        $album = $this->getAlbumTable()->getAlbum($id);

        $form  = new AlbumForm();
        $form->bind($album);
        $form->get('submit')->setAttribute('value', 'Обновить');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($album->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getAlbumTable()->saveAlbum($form->getData());

                // Redirect to list of albums
                return $this->redirect()->toRoute('gallery');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    // Удаление альбома
    public function deleteAlbumAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('gallery');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getAlbumTable()->deleteAlbum($id);
                $this->getPictureTable()->deletePicturesFrom($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('gallery');
        }

        return array(
            'id'    => $id,
            'album' => $this->getAlbumTable()->getAlbum($id)
        );
    }

    // Показать фотографии из альбома
    public function showAlbumAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        return new ViewModel(array(

            'pictures' => $this->getPictureTable()->fetchAlbum($id),
            'album' => $this->getAlbumTable()->getAlbum($id),
        ));
    }

    // Показать конкретную фотографию
    public function showPictureAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        $picture = $this->getPictureTable()->getPicture($id);
        $ids = $this->getPictureTable()->getPrevNext($picture);
        return new ViewModel(array(
            'picture' =>  $picture,
            'album' => $this->getAlbumTable()->getAlbum($picture->album),
            'ids' => $ids,
        ));
    }

    // Загрузить фотографию с выбором альбома или без, если есть id
    public function uploadPictureAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        $form = new PictureForm();
        if (!$id) {
            // список альбомов
            $albums = $this->getAlbumTable()->fetchAll();
            $form ->setAlbums($albums);
            $backToAlbum = false;
        } else {
            // Проверка наличия альбома, если его нет, то выдаст ошибку
            $this->getAlbumTable()->getAlbum($id);
            $backToAlbum = $id;
            $form->setAlbumId($id);
        }
        $request = $this->getRequest();
        if ($request->isPost()) {
            $picture = new Picture();
            $form->setInputFilter($picture->getInputFilter());

            // Вытаскиваем имя файла и проверяем заполнено ли поле
            $data = $request->getPost()->toArray();
            $file = $this->params()->fromFiles('imageFile');
            $data['imageFile'] = $file['name'];
            $form->setData($data);
            if ($form->isValid()) {
                $picture->exchangeArray($form->getData());
                $this->getPictureTable()->savePicture($picture);
                $data = $form->getData();
                $albumId = $data['album'];
                $this->calibration($albumId);
                // Redirect
                return $this->redirect()->toRoute('gallery',array('action'=>'showAlbum','id'=> $albumId));
            }
        }

        return array('form' => $form, 'backToAlbum' =>$backToAlbum );
    }
    // Удалить фотографию
    public function deletePictureAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('gallery');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');
            $id = (int) $request->getPost('id');
            $picture = $this->getPictureTable()->getPicture($id);
            if ($del == 'Yes') {
                echo
                $this->getPictureTable()->deletePicture($picture->id);
                $this->calibration($picture->album);
            }
            // Redirect to list of albums
            return $this->redirect()->toRoute('gallery',array('action'=>'showAlbum','id'=> $picture->album));
        }

        return array(
            'id'    => $id,
            'picture' => $this->getPictureTable()->getPicture($id)
        );
    }

    // Достать таблицу альбомов
    // Теперь  getAlbumTable() доступен с любого места нашего класса для взаимодействия с моделью(model)
    public function getAlbumTable()
    {
        if (!$this->albumTable) {
            $sm = $this->getServiceLocator();
            $this->albumTable = $sm->get('Gallery\Model\AlbumTable');
        }
        return $this->albumTable;
    }
    public function getPictureTable()
    {
        if (!$this->pictureTable) {
            $sm = $this->getServiceLocator();
            $this->pictureTable = $sm->get('Gallery\Model\PictureTable');
        }
        return $this->pictureTable;
    }

    // Калибруем значения кол-во фотографий и дату последней фотки
    protected function calibration($albumId)
    {
        // Количество фоток у данного альбома
        $amount = $this->getPictureTable()->amountPictures($albumId);

        // Дата и название файла у последнего фото
        $last = $this->getPictureTable()->lastPicture($albumId);
        // Обновляем альбом
        $this->getAlbumTable()->updateAlbum(array(
                'amount'=>$amount, 'preview'=>$last['src'], 'added'=>$last['added']),
                array('id'=>$albumId));
    }
}