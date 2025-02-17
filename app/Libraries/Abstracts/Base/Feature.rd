Documentation PHP:

 >> Information

    Title		: Feature
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    12-21-2018		Poen		02-12-2020	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Abstracts/Base/Feature.php) :
    The functional base class.

    file > (app/Libraries/Instances/Feature/Provider.php) :
    The functional configuration source class.

    file > (app/Libraries/Traits/Entity/Column/Feature.php) :
    The functional enitie trait class.

    file > (config/feature.php) :
    Feature provider configuration.

 >> Artisan Commands

    Create a object file.
    $php artisan make:feature <name>

    Unregister as a provider to the profile.
    $php artisan make:feature --unregister <name>

    Cancel creating document language file.
    $php artisan make:feature --nodocument <name>

    Cancel creating feature ExceptionCode class file.
    $php artisan make:feature --no-ex-code <name>

 >> Learn

    Step 1 :
    Create feature object class.

    $php artisan make:feature Gold\Increase

    Example : App\Libraries\Features\Gold\IncreaseFeature object class

    File : app/Libraries/Features/Gold/IncreaseFeature.php

    Step 2 :
    Edit feature provider.

    File : config / feature.php

    Example :
    --------------------------------------------------------------------------
    return [

        |--------------------------------------------------------------------------
        | Feature Provider Class
        |--------------------------------------------------------------------------
        |
        | Set the list of feature classes available to the provider.
        |
        | Example :
        | 'providers' => [
        |    Feature definition code => Functional object class,
        | ]

        'providers' => [
            'add_gold' => App\Libraries\Features\Gold\IncreaseFeature::class
            //:end-generating:
        ],

        |--------------------------------------------------------------------------
        | Model Definition Class
        |--------------------------------------------------------------------------
        |
        | Set the list of model with feature trait.
        | With App\Libraries\Traits\Entity\Column\Feature
        |
        | Example :
        | 'models' => [
        |    Model definition name => Model with feature trait,
        | ]

        'models' => [
            'client' => App\Entities\Service\Client::class
        ],

        |--------------------------------------------------------------------------
        | Release Model Feature
        |--------------------------------------------------------------------------
        |
        | Set the list of publishing features for the model.
        |
        | Example :
        | 'release' => [
        |    Model definition name => [
        |       Features option object class,
        |    ],
        | ]

        'release' => [
            'client' => [
                'add_gold',
            ],
        ]
    ];

    Step 3 :
    Edit language file.

    File : resources/lang/ language dir /feature/App/Libraries/Features/Gold/IncreaseFeature/document.php

    Example :
    --------------------------------------------------------------------------
    return [
        // Set the feature name description.
        'description' => 'New Feature',
        // Set the feature arguments description
    	'arguments' => [
        	'deploy' => [
                'quantity' => 'Quantity increase'
            ],
    	    'handle' => []
    	],
    	// Set the feature responses description
    	'responses' => [
    	    'deploy' => [
                'quantity' => 'Quantity increase'
            ],
    	    'handle' => [
    	        'name' => 'Item name',
                'quantity' => 'Quantity increase'
    	    ]
        ]
    ];

    Step 4 :
    Edit feature.

    Example :
    --------------------------------------------------------------------------
    // Get the validation rules that apply to the deploy arguments input.
    protected function deployRules(): array
    {
        return [
            'quantity' => 'required|integer|between:1,2147483647'
        ];
    }

    // Get the validation rules that apply to the handle arguments input.
    protected function handleRules(): array
    {
        return [
            //
        ];
    }

    // Get a list of the feature arguments for the release input required for deploying.
    protected function deployArguments(): array
    {
        return [
            [
                'quantity', // Arguments name
                'integer' // Arguments type ( string | integer | array )
            ]
        ];
    }

    // Get a list of the feature arguments for the release input required for handling.
    protected function handleArguments(): array
    {
        return [
            //
        ];
    }

    // Get a list of feature response parameters for deployment.
    protected function deployResponses(): array
    {
        return [
            [
                'quantity', // Parameters name
                'integer' // Parameters type ( string | integer | array | object | boolean )
            ]
        ];
    }

    // Get a list of feature response parameters for handle.
    protected function handleResponses(): array
    {
        return [
            [
                'name', // Parameters name
                'string' // Parameters type ( string | integer | array | object | boolean )
            ],
            [
                'quantity', // Parameters name
                'integer' // Parameters type ( string | integer | array | object | boolean )
            ]
        ];
    }

    // Get the begin handle deployment of the feature.
    protected function deploy()
    {
        // get input values
        $quantity = $this->getInput('quantity');

        // place your business logic here

        // output begin handle
        return $this->output([
            'quantity' => $quantity
        ]);
    }

    // Execute the feature handle.
    protected function handle()
    {
        // get input values
        $quantity = $this->getInput('quantity');
        // get other custom input data
        $custom = $this->getInput('custom');
        if (isset($custom)) {

        // place your business logic here

        } else {
            throw new ExceptionCode(ExceptionCode::NORMAL);
        }
        // output handle
        return $this->output([
            'name' => 'Itrm name',
            'quantity' => $quantity
        ]);
    }

    Step 5 :
    Get the begin handle deployment of the feature.

    Example :
    --------------------------------------------------------------------------

    $object = app('App\Libraries\Features\Gold\IncreaseFeature');

    $object->getCode(); // Get feature code by provider

    $object->setInput('quantity', 10, true); // Client input deployment arguments

    $deployment = $object->getDeployment(); // Get feature begin handle deployment

    Step 6 :
    Execute the feature handle to run.

    Example :
    --------------------------------------------------------------------------

    $object = app('App\Libraries\Features\Gold\IncreaseFeature');

    $object->getCode(); // Get feature code by provider

    $object->setInput('quantity', 10, true); // Client input deployment arguments

    $deployment = $object->getDeployment(); // feature begin handle deployment

    $object->setInput('type', 'test', true); // Client input handle arguments

    $object->setInput('custom', ['id' => 1]); // Get feature input custom arguments

    $object->run($deployment); // Run handle
