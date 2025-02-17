<?php

namespace App\Libraries\Abstracts\Base;

use App\Libraries\Traits\Info\Format\Response;
use Illuminate\Validation\ValidationException;
use App\Exceptions\System\CellExceptionCode;
use Validator;

abstract class Cell
{
    use Response;

    /**
     * The input interface implementation.
     *
     * @var array
     */
    protected $input = [];

    /**
     * Cell constructor.
     *
     * @param array $input
     * 
     * @return void
     */
    public function __construct(array $input = [])
    {
        /* Review release input */
        $this->input = array_intersect_key($input, $this->rules());
    }

    /**
     * Get the validation rules that apply to the arguments input.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [];
    }

    /**
     * Execute the cell handle.
     *
     * @return array
     */
    protected function handle(): array
    {
        throw new CellExceptionCode(CellExceptionCode::FUNCTION_UNIMPLEMENTED, [], ['%cell%' => get_class($this)]);
    }

    /**
     * Get input argument.
     *
     * @param string $name
     * @return mixed|null
     */
    protected function getInput(string $name)
    {
        return (isset($this->input[$name]) ? $this->input[$name] : null);
    }

    /**
     * Input data.
     *
     * @param array $input
     * 
     * @return object
     */
    public static function input(array $data = []): object
    {
        return new static($data);
    }

    /**
     * Execute the function handle to run.
     * 
     * @return array
     * @throws \Exception
     */
    public function run(): array
    {
        /* Validate */
        try {
            Validator::make($this->input, $this->rules())->validate();
        } catch (\Throwable $th) {
            if ($th instanceof ValidationException) {
                throw new CellExceptionCode(CellExceptionCode::VALIDATION, [], ['%cell%' => get_class($this)], $th->validator->getMessageBag());
            }
            throw $th;
        }
        /* Run handle */
        $result = $this->handle();
        /* Reset input */
        $this->input = [];
        /* Standard format */
        if (isset($result['success'], $result['data']) && count($result) == 2 && is_bool($result['success']) && is_array($result['data'])) {
            return $result;
        } else {
            /* Unformatted */
            throw new CellExceptionCode(CellExceptionCode::DATA_FORMAT_ERROR, [], ['%cell%' => get_class($this)]);
        }
    }
}