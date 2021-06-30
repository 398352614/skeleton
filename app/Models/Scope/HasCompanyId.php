<?php

/**
 * @Author: h9471
 * @Created: 2019/10/23 15:10
 */

namespace App\Models\Scope;

use App\Exceptions\BusinessLogicException;
use App\Models\Company;
use App\Models\Driver;
use App\Models\Employee;
use Illuminate\Support\Facades\Cache;

trait HasCompanyId
{
    public static function getCompanyId()
    {
        $user = auth()->user();
        if ($user instanceof Employee) {
            return $user->company_id;
        } elseif ($user instanceof Driver) {
            return $user->company_id;
        } elseif ($companyID = self::getCompanyIdFromReqPath()) {
            return $companyID;
        } else {
            $id = self::getUuidFromHeader();
            throw_unless($id, new BusinessLogicException('公司不存在'));
            return $id;
        }
    }

    public static function getCompanyIdFromReqPath()
    {
        $arr = explode('/', request()->path());

        end($arr);
        $uuid = current($arr);

        return self::getValidCompanyId($uuid);
    }

    public static function getValidCompanyId(string $uuid): ?int
    {
        if (strlen($uuid) != 4) {
            return null;
        }

        $company = Company::where('company_code', $uuid)->withoutGlobalScope(CompanyScope::class)->select('id')->first();

        return $company ? $company->company_id : null;
    }

    /**
     * @return int|null
     */
    public static function getUuidFromHeader(): ?int
    {
        if (request()->hasHeader('X-Uuid')) {
            $uuid = request()->header('X-Uuid');

            return Cache::get($uuid, function () use ($uuid) {
                $id = Company::where('company_code', $uuid)->withoutGlobalScope(CompanyScope::class)->firstOrFail()->id;

                Cache::forever($uuid, $id);

                return $id;
            });
        }

        return null;
    }
}
