<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 26.07.13
 * Time: 12:19
 * To change this template use File | Settings | File Templates.
 */

namespace Gallery\Model;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Gallery\Model\UploadPicture;
use Zend\InputFilter\FileInput;
use Zend\Validator\File\UploadFile;

class Picture {
    public $id;
    public $title;
    public $album;
    public $address;
    public $src;
    public $added;

    public function exchangeArray($data)
    {
        if (file_exists($_FILES["imageFile"]["tmp_name"]))
        {
            // Супер класс по загрузке фоток
            $uploadPicture = new UploadPicture();
            $data['src'] = $uploadPicture->resize("imageFile");
        }


        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->title = (isset($data['title'])) ? $data['title'] : null;
        $this->album  = (isset($data['album'])) ? $data['album'] : null;
        $this->address = (isset($data['address'])) ? $data['address'] : null;
        $this->src = (isset($data['src'])) ? $data['src'] : null;
        $this->added = (isset($data['added'])) ? $data['added'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            //$file = new FileInput('image-file');
            $inputFilter->add($factory->createInput(array(
                'name'     => 'imageFile',
                'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'title',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 50,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'address',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 200,
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}