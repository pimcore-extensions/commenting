<?php

/**
 * ModernWeb
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.modernweb.pl/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to kontakt@modernweb.pl so we can send you a copy immediately.
 *
 * @category    Pimcore
 * @package     Plugin_Commenting
 * @author      Rafał Gałka <rafal@modernweb.pl>
 * @copyright   Copyright (c) 2007-2012 ModernWeb (http://www.modernweb.pl)
 * @license     http://www.modernweb.pl/license/new-bsd     New BSD License
 */

/**
 * @category    Pimcore
 * @package     Plugin_Commenting
 * @author      Rafał Gałka <rafal@modernweb.pl>
 * @copyright   Copyright (c) 2007-2012 ModernWeb (http://www.modernweb.pl)
 */
class Commenting_Form extends Zend_Form
{
    public function init()
    {
        //name
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Name')
            ->setRequired(true)
            ->addErrorMessage('Please enter your name')
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addValidator('NotEmpty', true);
        $this->addElement($name);

        //email
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
            ->setRequired(true)
            ->addErrorMessage('Please enter your email')
            ->addFilters(array('StringTrim'))
            ->addValidator('EmailAddress');
        $this->addElement($email);

        //message
        $message = new Zend_Form_Element_Textarea('message');
        $message->setLabel('Comment')
            ->setRequired(true)
            ->addErrorMessage('Please enter a comment.')
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addValidator('NotEmpty', true)
            ->setAttribs(array('rows' => 10, 'cols' => 60));
        $this->addElement($message);

        //Captcha
        $captcha = new Zend_Form_Element_Captcha(
            'captcha',
            array(
                'label' => 'Type the characters you see in the picture below.',
                'captcha' => 'Image',
                'captchaOptions' => array(
                    'name' => 'cpt',
                    'wordLen' => 4,
                    'timeout' => 300,
                    'width' => 140,
                    'dotNoiseLevel' => 1,
                    'font' => PIMCORE_PLUGINS_PATH . '/Commenting/static/fonts/bebas.ttf',
                    'fontSize' => 24,
                    'imgDir' => PIMCORE_TEMPORARY_DIRECTORY . "/",
                    'imgUrl' => str_replace(PIMCORE_DOCUMENT_ROOT, '', PIMCORE_TEMPORARY_DIRECTORY) . "/",
                )
            )
        );
        $this->addElement($captcha);

        //Submit
        $submit = new Zend_Form_Element_Button('submit', array(
            'type' => 'submit',
            'name' => 'submit',
            'value' => 'Submit',
            'label' => 'Submit',
        ));
        $this->addElement($submit);
    }

}