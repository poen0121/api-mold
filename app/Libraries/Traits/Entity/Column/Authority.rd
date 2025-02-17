Documentation PHP:

 >> Information

    Title		: Authority Column
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    01-22-2020		Poen		02-24-2020	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Traits/Entity/Column/Authority.php) :
    The functional base class.

    Authority column format data.

 >> Middleware

    token.ban : Verify authorization interface authority

 >> Artisan Commands

    Add a new authority column to database table.
    $php artisan mg-column:append-authority

 >> Learn

    Step 1 :
    Append authority column to database table.

    Artisan Command :

    $php artisan mg-column:append-authority

    Step 2 :
    In App\Entities Model Class, Use App\Libraries\Traits\Entity\Column\Authority

    File : app/Entities/Service/Client.php

    Example :
    --------------------------------------------------------------------------
    namespace App\Entities\Service;

    use Illuminate\Database\Eloquent\Model;
    use Prettus\Repository\Contracts\Transformable;
    use Prettus\Repository\Traits\TransformableTrait;
    use App\Libraries\Traits\Entity\Column\Authority;

    class Client extends Model implements Transformable {
        use TransformableTrait;
        use Authority;

        // Push the 'authority' attributes that are mass assignable.
        protected $fillable = [
            'authority'
        ];
    }

    Step 3 :
    Get attribute data.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Service\Client;

    $item = Client::find(1);

    // Get authority array
    $authority = $item->authority;

    Step 4 :
    Update attribute data.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Service\Client;

    $item = Client::find(1);

    // Update authority array
    $item->update(['authority' => ['auth.read.service']]);
