<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;

/**
 * Login form
 */
class AdminLoginForm extends Model
{
    public $password;
    public $phone_number;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // phone number and password are both required
            [['phone_number', 'password'], 'required'],

            // password is validated by validatePassword()
            ['password', 'validatePassword'], 
            ['phone_number',  'isActive'],

        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        
        if (!$this->hasErrors()) {
            $admin = $this->getAdmin();

            if (!Yii::$app->getSecurity()->validatePassword($this->password, $admin->password)) {
                $this->addError($attribute, 'Incorrect phone number or password.');
            }
        }
    }

    /**
     * Logs in a admin using the provided phone_number and password.
     *
     * @return bool whether the admin is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getAdmin());
        }

        return false;
    }

    public function isActive()
    {
        if (!$this->hasErrors()) {
            $admin = $this->getadmin();
            if ($admin->status === Admin::STATUS_INACTIVE) {
                $this->addError($admin->phone_number, 'Inactive Phone number Contact Us for more information.');
            }
        }
        return false;
    }
    
    /**
     * Finds admin by [[phone_number]]
     *
     * @return admin|null
     */
    protected function getAdmin()
    {
        if (($model = Admin::findByPhoneNumber($this->phone_number)) !== null) {
            return $model;
        } else {
          
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
