Documentation PHP:

 >> Information

    Title		: Identity
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    12-21-2019		Poen		07-24-2020	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Traits/Entity/Swap/Identity.php) :
    The functional base class.
    Conversion ID pseudo-identification.

 >> Learn

    Step 1 :
    In App\Entities Model Class, Use App\Libraries\Traits\Entity\Swap\Identity

    File : app/Entities/Service/Client.php

    Example :
    --------------------------------------------------------------------------
    namespace App\Entities\Service;

    use Illuminate\Database\Eloquent\Model;
    use Prettus\Repository\Contracts\Transformable;
    use Prettus\Repository\Traits\TransformableTrait;
    use App\Libraries\Traits\Entity\Swap\Identity;

    class Client extends Model implements Transformable {
        use TransformableTrait;
        use Identity;

    }

    Step 2 :
    Get pseudo-identification info.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Service\Client;

    $item = Client::find(1);

    $item->tid;

    ==========================================================================

    Custom Definition :

    Usage 1 :
    Get the transform id suffix length by eloquent model.

    Example :
    --------------------------------------------------------------------------
    protected function getPrimaryTidSuffixLength(): int
    {
        return 6;
    }

    Usage 2 :
    Get the transform id prefix word A-Z by eloquent model.

    Example :
    --------------------------------------------------------------------------
    protected function getPrimaryTidPrefixWord()
    {
        return 'C';
    }

    ==========================================================================

    Available Functions :

    Usage 1 :
    Get the transform id dependency id.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Service\Client;

    app(Client::class)->asPrimaryId('1294583');

    Usage 2 :
    Get the transform id list dependency id list.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Service\Client;

    app(Client::class)->asPrimaryIds(['1294583', '2215437']);

    Usage 3 :
    Get the id dependency transform id.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Service\Client;

    app(Client::class)->asPrimaryTid(1);

    Usage 4 :
    Get the id list dependency transform id list.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Service\Client;

    app(Client::class)->asPrimaryTids([1, 2]);
