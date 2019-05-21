<?php 

namespace Emamie\Atom\ApiGenerator;

use InfyOm\Generator\Request\APIRequest;
use InfyOm\Generator\Utils\ResponseUtil;
use Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class AtomApiRequest extends APIRequest
{
	protected function failedValidation(Validator $validator)
    {
        throw new ValidationException('Validate Error', ResponseUtil::makeError($validator->messages()));
    }
}