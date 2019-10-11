<?php

namespace App\ConfigClasses;


class CustomerAuthConfig{

    public $inputs;

    public function __construct()
    {

        $this->inputs = [
            'name' => ['type'=>'text', 'placeholder'=>'imię', 'require'=>true, 'id'=>'imie'],
            'surname' => ['type'=>'text', 'placeholder'=>'nazwisko', 'require'=>true,'id'=>'nazwisko'],
            'email' => ['type'=>'text', 'placeholder'=>'email', 'require'=>true,'id'=>'email-rejestracji'],
            'customer_type' => [
                'type'=>'radio',
                'values'=>['osoba prywatna', 'instytucja'],
                'labels'=>['osoba prywatna', 'instytucja'],
                'selected'=>'osoba prywatna',
                'has_oblique'=>true,
                'input_oblique'=>[
                    'instytucja'=>[
                        'customer_type' => ['type'=>'text', 'name'=>'institution', 'placeholder'=>'Instytucja', 'require'=>false]
                    ],
                    'osoba prywatna'=>[

                    ]
                ]
            ],
            'register_target' => ['type'=>'select', 'placeholder'=>'cel', 'require'=>true, 'suggest'=>true],
            'phone' => ['type'=>'text', 'placeholder'=>'telefon', 'require'=>false,'id'=>'telefon'],
            'password' => ['type'=>'password', 'placeholder'=>'hasło', 'require'=>true,'id'=>'haslo'],
            'repassword' => ['type'=>'password', 'placeholder'=>'potwierdź hasło', 'require'=>true,'id'=>'re-haslo'],
            'regulations' => ['type'=>'checkbox',
                            'label'=>
                                    '
                                    Zgadzam się z regulaminem strony
                                    <a href="'.url('3-regulamin-portalu-relacjebiograficznepl').'" target="blank">przejdź do regulaminu</a>
                                    ',
                            'require'=>true],
            'privacy_policy' => ['type'=>'checkbox',
                            'label'=>
                                '
                                Zgadzam się z polityką prywatności
                                <a href="'.url('4-polityka-prywatnosci-serwisu-wwwrelacjebiograficznepl').'" target="_blank">przejdź do polityki prywatności</a>
                                ',
                            'require'=>true]
        ];

    }


}