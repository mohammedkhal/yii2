<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;
use yii\base\Event;
/**
 * Login form
 */
class LoginForm extends ActiveRecord
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
            [['phone_number', 'password'], 'required',],
            ['phone_number', 'isActive'],

            // password is validated by validatePassword()
            ['password', 'validatePassword'], 

        ];
    }

   
    public function afterValidate()
    {
        $userID = Yii::$app->user->id;

        if ($this->hasErrors()) {
            $errors = $this->getErrors();

            foreach ($errors as $error => $oneError) {
                $log = new UsersValidationLog;
                $log->validation_error = $oneError[0];
                $log->user_id = $userID;
                if (!$log->save()) {
                    return false;
                }
            }
        }

        return true;
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

            $user = $this->getUser();
            if (!Yii::$app->getSecurity()->validatePassword($this->password, $user->password)) {
                $this->addError($attribute, 'Incorrect phone number or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided phone_number and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            
            return Yii::$app->user->login($this->getUser());
        }

        return false;
    }

    public function isActive()
    {
        $user = User::findByPhoneNumber($this->phone_number);
        if (!$this->hasErrors()) {
            if ($user === null) {
                return $this->addError('password', 'in correct phone number or passworddddd');
            }

            if ($user->status === User::STATUS_DELETED) {
                $this->addError('password', 'Deleted Phone number Contact Us for more information.');
            }
        }
        return false;
    }
    
    /**
     * Finds user by [[phone_number]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if (($model = User::findByPhoneNumber($this->phone_number)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
