<?php
return [

    /*
     |--------------------------------------------------------------------------
     | System Parameter Description
     |--------------------------------------------------------------------------
     |
     | Set the system parameter name description.
     */

    'description' => 'SMS log query limit months.',

    /*
     |--------------------------------------------------------------------------
     | Parameter Value Description
     |--------------------------------------------------------------------------
     |
     | Set the parameter value description.
     | 
     | Example :
     | 'value' => [
     |	'description' => (string) description,
     |	'type' => (string) type description,
     | ]
     */

    'value' => [
        'description' => '1 ~ 6 months',
        'type' => 'integer'
    ]
];
