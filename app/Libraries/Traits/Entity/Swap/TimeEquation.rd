Documentation PHP:

 >> Information

    Title		: TimeEquation
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    12-21-2019		Poen		03-03-2020	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Traits/Entity/Swap/TimeEquation.php) :
    The functional base class.
    Conversion timezone datetime.
    Automatically change timezone time to get attributes.

    The client time zone depends on middleware 'App\Http\Middleware\Accept\Timezone' .

 >> Note

    Remember to convert to local time for SQL query.
    Usage : function asLocalTime( Client time )

 >> Learn

    Step 1 :
    In App\Entities Model Class, Use App\Libraries\Traits\Entity\Swap\TimeEquation

    Set the other attributes that should be mutated to dates.

    File : app/Entities/Service/Client.php

    Example :
    --------------------------------------------------------------------------
    namespace App\Entities\Service;

    use Illuminate\Database\Eloquent\Model;
    use Prettus\Repository\Contracts\Transformable;
    use Prettus\Repository\Traits\TransformableTrait;
    use App\Libraries\Traits\Entity\Swap\TimeEquation;

    class Client extends Model implements Transformable {
         use TransformableTrait;
         use TimeEquation;

         // The other attributes that should be mutated to dates.
         // @var array
         protected $dates = [
            //
         ];

    }

    Step 2 :
    Get the client timezone time by automatically changing the local time attributes.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Service\Client;

    $item = Client::find(1);

    // Client timezone time
    $item->created_at;

    // Client timezone time
    $item->updated_at;

    ==========================================================================

    Available Functions :

    Usage 1 :
    Get the application local timezone.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Service\Client;

    app(Client::class)->getTZ();

    Usage 2 :
    Get the accept client timezone.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Service\Client;

    app(Client::class)->getCTZ();

    Usage 3 :
    Swap the client timezone datetime to the local timezone datetime.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Service\Client;

    app(Client::class)->asLocalTime('2018-01-01 00:00:00');

    Usage 4 :
    Swap the local timezone datetime to the client timezone datetime.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Service\Client;

    app(Client::class)->asClientTime('2018-01-01 00:00:00');
