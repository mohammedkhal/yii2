<?php

namespace backend\Repositories;

use Yii;
use yii\web\Controller;
use common\models\User;

/**
 * user repository
 */
class UserRepository extends Controller
{
    /**
     * initial new User object.
     *
     * @return object
     */

    public function getModel()
    {
        return new User;
    }

    public function fetchActiveUser()
    {
        $users = $this->getModel();

        $users = $users->findAll([
            'status' => User::STATUS_ACTIVE,
        ]);

        return $users;
    }
}
