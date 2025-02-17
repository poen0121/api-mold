Documentation PHP:

 >> Information

    Title		: Feature Column
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    01-22-2020		Poen		02-10-2020	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Traits/Entity/Column/Feature.php) :
    The functional base class.

    Feature column format data and feature request input function.

    file > (config/feature.php) :
    The feature provider about config.

 >> Artisan Commands

    Add a new feature column to database table.
    $php artisan mg-column:append-feature

 >> Learn

    Step 1 :
    Append feature column to database table.

    Artisan Command :

    $php artisan mg-column:append-feature

    Step 2 :
    In App\Entities Model Class, Use App\Libraries\Traits\Entity\Column\Feature

    File : app/Entities/Service/Client.php

    Example :
    --------------------------------------------------------------------------
    namespace App\Entities\Service;

    use Illuminate\Database\Eloquent\Model;
    use Prettus\Repository\Contracts\Transformable;
    use Prettus\Repository\Traits\TransformableTrait;
    use App\Libraries\Traits\Entity\Column\Feature;

    class Client extends Model implements Transformable {
        use TransformableTrait;
        use Feature;

        // Push the 'feature' attributes that are mass assignable.
        protected $fillable = [
            'feature'
        ];
    }

    Step 3 :
    Get attribute data.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Service\Client;

    $item = Client::find(1);

    // Get feature array
    $features = $item->feature;

    Step 4 :
    Run the deploy script and update attribute data.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Service\Client;

    $item = Client::find(1);

    // Client request get the deployment
    $deploy = $item->triggerFeatureDeploy('add_gold', [
         'quantity' => 100
       ], [], true)->triggerFeatureDeploy('add_gold', [
         'quantity' => 50
       ], [], true)->gatherFeatureDeploy(); // Get the deployment result

    // Update feature array
    $item->update(['feature' => $deploy]);

    Step 5 :
    Run the handle script.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Service\Client;

    $item = Client::find(1);

    // Get feature array
    $features = $item->feature;

    // Run feature handle input deployment
    foreach($features as $input) {
       $item->triggerFeatureHandle($input['code'], [], $input['deploy'], false);
    }
    // Get handle result
    $item->gatherFeatureHandle();
