<?php

namespace App\Response;

use DateTime;
use OpenApi\Annotations as OA;

class UserRegisterResponse
{
    /**
     * @OA\Property(type="array", property="roles",
     *     @OA\Items(type="string"))
     */
    public $roles = [];

    public DateTime $createDate;

    /**
     * @var string|null
     */
    public $found;
}
