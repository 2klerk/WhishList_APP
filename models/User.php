<?php

namespace app\models;

use app\components\Utility;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public static function tableName(): string
    {
        return 'users'; // Название таблицы в базе данных
    }

    public function rules(): array
    {
        return [
            [['name', 'surname', 'email', 'password'], 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'app\models\User', 'message' => 'This email address has already been taken.'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Name',
            'surname' => 'Surname',
            'email' => 'Email',
            'password' => 'Password',
        ];
    }

    public function getUserType()
    {
        return $this->hasOne(UserType::className(), ['user_type_id' => 'user_type_id']);
    }

    /**
     * Finds an identity by the given ID.
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * @param $id
     * @return User|array|null
     */
    public static function findUserByID($id)
    {
        return User::findModelByID($id);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|integer an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->userid;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return boolean whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->user_token === $authKey;
    }

    public function validatePassword($password)
    {
        // return Yii::$app->getSecurity()->validatePassword($password, $this->password);
        return $password === $this->password;
    }

    /**
     * @param $email
     * @return array|null|\yii\db\self
     */
    public static function findUserByEmail($email)
    {
        return self::find()->where(['email' => $email])->one();
    }

    /**
     * @param $id
     * @return array|null|\yii\db\self
     */
    public static function findModelByID($id)
    {
        return self::find()->where(['active_status' => 1, 'userid' => $id])->one();
    }

}
