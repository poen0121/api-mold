Documentation PHP:

 >> Information

    Title		: Setting Column
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    04-06-2020		Poen		04-06-2020	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Traits/Entity/Column/Setting.php) :
    The functional base class.

    Setting column format data.

 >> Artisan Commands

    Add a new setting column to database table.
    $php artisan mg-column:append-setting

 >> Learn

    Step 1 :
    Append setting column to database table.

    Artisan Command :

    $php artisan mg-column:append-setting

    Step 2 :
    In App\Entities Model Class, Use App\Libraries\Traits\Entity\Column\Setting

    File : app/Entities/Service/Client.php

    Example :
    --------------------------------------------------------------------------
    namespace App\Entities\Service;

    use Illuminate\Database\Eloquent\Model;
    use Prettus\Repository\Contracts\Transformable;
    use Prettus\Repository\Traits\TransformableTrait;
    use App\Libraries\Traits\Entity\Column\Setting;

    class Client extends Model implements Transformable {
        use TransformableTrait;
        use Setting;

        // Push the 'setting' attributes that are mass assignable.
        protected $fillable = [
            'setting'
        ];
        // Push the setting of assignable attribute options and default value.
        public function getSettingOptions(): array
        {
            return [
                'mode' => 0
            ];
        }
    }

    Step 3 :
    Get attribute data.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Service\Client;

    $item = Client::find(1);

    // Get setting array
    $setting = $item->setting;

    Step 4 :
    Update attribute data.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Service\Client;

    $item = Client::find(1);

    // Update setting array
    $item->update(['setting' => ['mode' => 1]]);
