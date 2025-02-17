Documentation PHP:

 >> Information

    Title		: Attach Muppet Info Resources
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    03-11-2021		Poen		03-11-2021	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Traits/Info/Attach/Muppet.php) :
    The functional base class.

    Populate resources for interaction.

 >> Learn

    Step 1 :
    In App\Criteria Class, Use App\Libraries\Traits\Info\Attach\Muppet

    File : app/Criteria/Service/Client/IndexCriteria.php

    Example :
    --------------------------------------------------------------------------
    namespace App\Criteria\Service\Client;

    use Prettus\Repository\Contracts\CriteriaInterface;
    use Prettus\Repository\Contracts\RepositoryInterface;
    use App\Libraries\Traits\Info\Attach\Muppet;

    class IndexCriteria implements CriteriaInterface
    {
        use Muppet;
        
    }

    ==========================================================================

    Available Functions :

    Usage 1 :
    Put resources to the filler.

    Example :
    --------------------------------------------------------------------------
    app('App\Criteria\Service\Client\IndexCriteria')
    ->putFiller('start', request()->input('start'))
    ->putFiller('end', request()->input('end'));

    Usage 2 :
    Get resources from the filler.

    Example :
    --------------------------------------------------------------------------
    namespace App\Criteria\Service\Client;

    use Prettus\Repository\Contracts\CriteriaInterface;
    use Prettus\Repository\Contracts\RepositoryInterface;
    use App\Libraries\Traits\Info\Attach\Muppet;

    class IndexCriteria implements CriteriaInterface
    {
        use Muppet;

        public function apply($model, RepositoryInterface $repository)
        {
            $start = $this->getFiller('start');
            $end = $this->getFiller('end');

            // Query logic
            
            return $model;
        }
    }
