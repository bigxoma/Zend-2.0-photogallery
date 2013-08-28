<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 25.07.13
 * Time: 15:58
 * To change this template use File | Settings | File Templates.
 */
namespace Gallery\Model;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\EmailAddress;
use Zend\Validator\Regex;
class Album
{
    public $id;
    public $title;
    public $description;
    public $preview;
    public $author;
    public $mail;
    public $phone;
    public $created;
    public $update;
    public $amount;
    public $last;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->title = (isset($data['title'])) ? $data['title'] : null;
        $this->description  = (isset($data['description'])) ? $data['description'] : null;
        $this->preview  = (isset($data['preview'])) ? $data['preview'] : null;
        $this->author     = (isset($data['author'])) ? $data['author'] : null;
        $this->mail = (isset($data['mail'])) ? $data['mail'] : null;
        $this->phone  = (isset($data['phone'])) ? $data['phone'] : null;
        $this->created     = (isset($data['created'])) ? $data['created'] : null;
        $this->update = (isset($data['update'])) ? $data['update'] : null;
        $this->amount  = (isset($data['amount'])) ? $data['amount'] : null;
        $this->added  = (isset($data['added'])) ? $data['added'] : null;

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

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
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
                'name'     => 'description',
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

            $inputFilter->add($factory->createInput(array(
                'name'     => 'author',
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

            // Валидность емайла, почему-то зачеркивает метод addValidator, но все работает.
            $email = new Input('mail');
            $email->getValidatorChain()->addValidator(new EmailAddress());
            $email->setRequired(false);
            $inputFilter->add($email);

            $phone = new Input('phone');
            $phone->getValidatorChain()->addValidator(new Regex(array(
                'pattern'=>'/^\+7(\ \([0-9]{3}\)\ )([0-9]{3})-([0-9]{2})-([0-9]{2})$/')));
            $phone->setRequired(false);
            $inputFilter->add($phone);


            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}