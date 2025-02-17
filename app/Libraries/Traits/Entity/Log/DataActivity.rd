Documentation PHP:

 >> Information

    Title		: DataActivity
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    12-31-2019		Poen		01-13-2020	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Traits/Entity/Log/DataActivity.php) :
    The functional base class.

    file > (config/activitylog.php) :
    The activitylog about config.

    file > (resources/lang/ language dir /activitylog.php) :
    Edit language file.

    According to Spatie\Activitylog\Traits\LogsActivity class.

    Data activity logger.

 >> Note

    Can't record data objects by batch processing.

    Record object needs to have a clear integer id field.

 >> Learn

    Step 1 :
    In App\Entities Model Class, Use App\Libraries\Traits\Entity\Log\DataActivity

    File : app/Entities/Service/Client.php

    Example :
    --------------------------------------------------------------------------
    namespace App\Entities\Service;

    use Illuminate\Database\Eloquent\Model;
    use Prettus\Repository\Contracts\Transformable;
    use Prettus\Repository\Traits\TransformableTrait;
    use App\Libraries\Traits\Entity\Log\DataActivity;

    class Client extends Model implements Transformable {
        use TransformableTrait;
        use DataActivity;

    }

    Step 2 :
    Push data activity log name.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Service\Client;

    $item = Client::find(1);

    // Update updated_at
    $item->pushLogName('test')->touch();

    Step 3 :
    Push data activity log description.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Service\Client;

    $item = Client::find(1);

    // Update updated_at
    $item->pushLog('about')->touch();

    Step 4 :
    Push the log properties.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Service\Client;

    $item = Client::find(1);

    // Update updated_at
    $item->pushProperties(['ip' => '127.0.0.1'])->touch();
    ==========================================================================

    Custom Definition :

    Usage 1 :
    Get the custom default log name by eloquent model.

    Example :
    --------------------------------------------------------------------------
    protected function getCustomDefaultLogName()
    {
        return 'model';
    }
