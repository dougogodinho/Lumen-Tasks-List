<?php

namespace App\Http\Controllers;

use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Http\Request;
use Illuminate\Translation\Translator;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /** @var  Translator */
    protected $trans;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->trans = app('translator');
    }

    /**
     * @param $key
     * @param array $replace
     * @return array|null|string
     */
    public function trans($key, $replace = [])
    {
        return $this->trans->get($key, $replace);
    }

    /**
     * @param Request $request
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
    protected function throwValidationException(Request $request, $validator)
    {
        throw new ValidationHttpException($validator->errors());
    }
}
