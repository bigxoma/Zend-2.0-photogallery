<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 25.07.13
 * Time: 16:33
 * To change this template use File | Settings | File Templates.
 */
namespace Gallery\Form;

use Zend\Form\Form;

class AlbumForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('album');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'title',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Название альбома (максимум 50 символов)*',
            ),
        ));
        $this->add(array(
            'name' => 'description',
            'attributes' => array(
                'type'  => 'textarea',
            ),
            'options' => array(
                'label' => 'Описание альбома (максимум 200 символов)*',
            ),
        ));
        $this->add(array(
            'name' => 'author',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Имя фотографа (максимум 50 символов)*',
            ),
        ));
        $this->add(array(
            'name' => 'mail',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Адрес эл. почты',
            ),
        ));
        $this->add(array(
            'name' => 'phone',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Контактный телефон в формате +7 (xxx) xxx-xx-xx',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }
}