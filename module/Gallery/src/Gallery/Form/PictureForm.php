<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 26.07.13
 * Time: 11:31
 * To change this template use File | Settings | File Templates.
 */
namespace Gallery\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class PictureForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('picture');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');

        /*
        $file = new Element\File('image-file');
        $file->setLabel('Загружаемый файл (не более 20 мб)');
        $this->add($file);
        */

        $this->add(array(
            'name' => 'imageFile',
            'attributes' => array(
                'type' => 'file',
                'id' => 'imageFile',
            ),
            'options' => array(
                'label' => 'Загружаемый файл (не более 20 мб)* ',
            ),
        ));

        $this->add(array(
            'name' => 'title',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Заголовок фотографии (макс. 50 символов)* ',
            ),
        ));

        $this->add(array(
            'name' => 'address',
            'attributes' => array(
                'type'  => 'textarea',
            ),
            'options' => array(
                'label' => 'Адрес фотосъемки (макс. 200 симовлов)* ',
            ),
        ));



        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Загрузить',
                'id' => 'submitbutton',
            ),
        ));
    }
    public function setAlbums($albumsArray){
        $select = new Element\Select('album');
        $select->setLabel('Выберите альбом');
        foreach ($albumsArray as $album ){
            $id = $album->id;
            $title = $album->title;
            $list[$id]=$title;
        }
        //print_r($list);
        $select->setValueOptions($list);

        $this->add($select);
    }
    public function setAlbumId ($id)
    {
        $this->add(array(
            'name' => 'album',
            'attributes' => array(
                'type'  => 'hidden',
                'value' => $id
            ),
        ));
    }
    /*
    public function addElements()
    {
        // File Input
        $file = new Element\File('image-file');
        $file->setLabel('Загружаемый файл (не более 20 мб)')
            ->setAttribute('id', 'image-file');
        $this->add($file);
    }
    */
}