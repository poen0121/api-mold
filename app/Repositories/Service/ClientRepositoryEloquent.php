<?php

namespace App\Repositories\Service;

use App\Libraries\Upgrades\BetterBaseRepository as BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Service\ClientRepository;
use App\Entities\Service\Client;
use App\Validators\Service\ClientValidator;
use App\Exceptions\Service\ClientExceptionCode as ExceptionCode;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use DB;
use Lang;
use Exception;

/**
 * Class ClientRepositoryEloquent.
 *
 * @package namespace App\Repositories\Service;
 */
class ClientRepositoryEloquent extends BaseRepository implements ClientRepository
{

    /**
     * The ban columns list.
     *
     * @var array
     */
    private static $columns = [
        'number',
        'description',
        'status',
        'allow_named',
        'unallow_named'
    ];

    /**
     * Client bans list
     *
     * @var array
     */
    private static $bans;

    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        // Return empty is close presenter default transformer.
        return "App\\Presenters\\Service\\ClientPresenter";
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Client::class;
    }

    /**
     * Specify Validator class name
     *
     * @return string
     */
    public function validator()
    {
        // Return empty is to close the validator about create and update on the repository.
        return ClientValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Get a list of data for the existing ban number.
     *
     * @param array $column
     * @param int|null $number
     *
     * @return array
     * @throws \Exception
     */
    public function bans(array $column = [], ?int $number = null): array
    {
        /* Use column */
        if (count($column) > 0) {
            $diff = array_unique(array_diff($column, self::$columns));
            /* Check column name */
            if (count($diff) > 0) {
                throw new Exception('Query Ban: Column not found: Unknown column ( \'' . implode('\', \'', $diff) . '\' ) in \'field list\'.');
            }
        }
        /* Build cache reset description */
        if (!isset(self::$bans)) {
            $bans = config('ban.release');
            self::$bans = collect($bans)->map(function ($item, $key) {
                return [
                    'number' => (int) $key,
                    'description' => Lang::dict('ban', 'release.' . $item['description'], 'Undefined'),
                    'status' => (bool) $item['status'],
                    'allow_named' => (is_array($item['allow_named']) ? $item['allow_named'] : []),
                    'unallow_named' => (is_array($item['unallow_named']) ? $item['unallow_named'] : [])
                ];
            })
                ->all();
        }
        /* Return result */
        if (is_null($number)) {
            $bans = self::$bans;
            if (count($column) > 0) {
                /* Forget column */
                $forget = array_diff(self::$columns, $column);
                /* Get result */
                $bans = collect($bans)->map(function ($item) use ($forget) {
                    return collect($item)->forget($forget)->all();
                })
                    ->all();
            }
            return array_values($bans);
        } else {
            /* Get bans */
            if (isset(self::$bans[$number])) {
                $ban = self::$bans[$number];
                if (count($column) > 0) {
                    /* Forget column */
                    $forget = array_diff(self::$columns, $column);
                    /* Get result */
                    $ban = collect($ban)->forget($forget)->all();
                }
                return $ban;
            } else {
                throw new ModelNotFoundException('Query Ban: No query results for bans: Unknown ban number \'' . $number . '\'.');
            }
        }
    }

    /**
     * Get a list of data for existing clients.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     * @throws \Exception
     */
    public function index(object $request): array
    {
        /* Criteria Index */
        $this->pushCriteria(app('App\Criteria\Service\Client\IndexCriteria')
        ->putFiller('name', $request->input('name'))
        ->putFiller('creation_sort', $request->input('creation_sort'))
        ->putFiller('start', $request->input('start'))
        ->putFiller('end', $request->input('end')));

        $result = $this->paginate($request->input('rows', 15));
        if (isset($result['meta']['pagination']['links'])) {
            unset($result['meta']['pagination']['links']);
        }
        return $result;
    }

    /**
     * Get data centered on existing client's primary key id.
     *
     * @param int $id
     *
     * @return array
     * @throws \Exception
     */
    public function focusClient(int $id): array
    {
        $result = $this->find($id);

        return $result['data'];
    }

    /**
     * Reset the secret of the focus client.
     *
     * @param int $id
     *
     * @return string
     * @throws \Exception
     */
    public function focusResetSecret(int $id): string
    {
        /* Required columns client_id */
        $result = $this->find($id, [
            'client_id'
        ]);

        $key = hash_hmac('md5', $result['data']['client_id'], microtime());

        $clientSecret = hash_hmac('md5', $result['data']['client_id'], $key);

        $this->update([
            'key' => $key,
            'client_secret' => $clientSecret
        ], $id);

        return $clientSecret;
    }

    /**
     * Disable focus client.
     *
     * @param int $id
     *
     * @return void
     * @throws \Exception
     */
    public function focusDisable(int $id)
    {
        $this->update([
            'status' => 0
        ], $id);
    }

    /**
     * Enable focus client.
     *
     * @param int $id
     *
     * @return void
     * @throws \Exception
     */
    public function focusEnable(int $id)
    {
        $this->update([
            'status' => 1
        ], $id);
    }

    /**
     * Generate a new client service.
     *
     * @param string $name
     * @param int $ban
     * @param string|null $expire
     *
     * @return array
     * @throws \Exception
     */
    public function build(string $name, int $ban, ?string $expire = null): array
    {
        try {
            $result = $this->create([
                'name' => $name,
                'ban' => $ban,
                'status' => 1,
                'expired_at' => $expire
            ]);

            return $result['data'];
        } catch (\Throwable $th) {
            if (strpos($th->getMessage(), 'clients_name_unique') !== false) {
                throw new ExceptionCode(ExceptionCode::SERVICE_NAME_EXISTS);
            } elseif (strpos($th->getMessage(), 'clients_client_id_unique') !== false) {
                throw new ExceptionCode(ExceptionCode::SERVICE_ID_EXISTS);
            } else {
                throw $th;
            }
        }
    }

    /**
     * Rename focus client.
     *
     * @param int $id
     * @param string $name
     *
     * @return void
     * @throws \Exception
     */
    public function focusRename(int $id, string $name)
    {
        try {
            $this->update([
                'name' => $name
            ], $id);
        } catch (\Throwable $th) {
            if (strpos($th->getMessage(), 'clients_name_unique') !== false) {
                throw new ExceptionCode(ExceptionCode::SERVICE_NAME_EXISTS);
            } else {
                throw $th;
            }
        }
    }

    /**
     * Change ban number for focus client.
     *
     * @param int $id
     * @param int $ban
     *
     * @return void
     * @throws \Exception
     */
    public function focusRewriteBan(int $id, ?string $ban)
    {
        $this->update([
            'ban' => $ban
        ], $id);
    }

    /**
     * Change expiration for focus client.
     *
     * @param int $id
     * @param string|null $expire
     *
     * @return void
     * @throws \Exception
     */
    public function focusReschedule(int $id, ?string $expire = null)
    {
        $this->update([
            'expired_at' => $expire
        ], $id);
    }
}
