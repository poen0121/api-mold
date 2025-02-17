Documentation PHP:

 >> Information

    Title		: System Parameter
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    12-16-2019		Poen		03-22-2021	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Instances/System/System.php) :
    The functional base class.

    file > (config/sp.php) :
    Validator rule configuration.

    You can configure data cache storage operations.

    Parameter name entered must not exceed 128 bytes!

    Parameter value entered must not exceed 128 bytes!

 >> Artisan Commands

    Add a new system parameter and configuration.
    $php artisan data:sp-add

    Create a system parameter document language file.
    $php artisan make:sp-document <name>

    Insert a system parameter value validator rule.
    $php artisan config:add-sp-rule <name>

 >> Aliases

    use SystemParameter;

 >> Base Usage

    Step 1 :

    Get the parameter value.

    Example :
    --------------------------------------------------------------------------
    use SystemParameter;

    SystemParameter::getValue('test_parameter');

    Step 2 :

    Set the parameter value.

    Example :
    --------------------------------------------------------------------------
    use SystemParameter;

    SystemParameter::setValue('test_parameter', '100');

 >> Learn

    Step 1 :

    $php artisan data:sp-add

    The migration insert parameter file is created automatically.

    The language file is created automatically.

    The validator rule is inserted automatically.

    Step 2 :
    Edit Language file.

    File : resources/lang/ language dir /system/parameters/ parameter name /document.php

    Example :
    --------------------------------------------------------------------------
    return [

     // Set the parameter name description
        'description' => 'New System Parameter',

     // Explanation of the parameter value
        'value' => [
            // Set the parameter value description
            'description' => 'Define values 1 ~ 100',
            // Set the parameter value type
            'type' => 'integer'
        ]
    ];

    Step 3 :
    Edit validator rule file.

    File : config / sp.php

    Example :
    --------------------------------------------------------------------------
    return [

         |--------------------------------------------------------------------------
         | Cache Store
         |--------------------------------------------------------------------------
         |
         | Enabled system parameters to use cache storage.
         | Set storage cache from config cache.php.
         | Must match with one of the application's configured cache "stores".
         |
         | Note: Use cache to improve read response speed.
         | Suggested: "memcached", "redis"
         |

         'cache_store' => env('SYSTEM_PARAMETER_CACHE_STORE', null),

         |--------------------------------------------------------------------------
         | Parameter Value Validation Rule
         |--------------------------------------------------------------------------
         |
         | Command: $php artisan data:sp-add
         | Set the system parameter value rule.
         |
         | Example :
         |  'rules' => [
         |      Parameter name => [
         |	     Validation rule.
         |      ]
         |  ]

         'rules' => [
            'test_parameter' => [
                 'required',
                 'integer',
                 'between:1,100'
            ],
            //:end-generating:
        ]
    ];

