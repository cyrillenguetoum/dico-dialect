<?php
declare(strict_types=1);

namespace App\Filter;

use Laminas\Filter\StringTrim;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\EmailAddress;
use Laminas\Validator\StringLength;

class EntryInputFilter extends InputFilter
{
    public function init()
    {
        $this->add([
            'name' => 'word',
            'required' => true,
            'filters' => [
                ['name' => StringTrim::class]
            ],
        ]);
        $this->add([
            'name' => 'typology',
            'required' => true,
            'filters' => [
                ['name' => StringTrim::class]
            ],
        ]);
        $this->add([
            'name' => 'definition',
            'required' => true,
            'filters' => [
                ['name' => StringTrim::class]
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'min' => 5,
                        'max' => 255
                    ],
                ]
            ]
        ]);
    }
}
