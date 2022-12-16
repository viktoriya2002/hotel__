<?php

namespace app\models;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $gender
 * @property string $birth_day
 * @property string $phone
 * @property string|null $user_pic
 * @property string $e_mail
 * @property string $login
 * @property string $password
 * @property string|null $token
 *
 * @property Booking[] $bookings
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'gender', 'birth_day', 'phone', 'e_mail', 'login', 'password'], 'required'],
            [['gender'], 'string'],
            [['birth_day'], 'safe'],
            [['first_name', 'last_name', 'gender', 'phone', 'user_pic', 'e_mail', 'login', 'password'], 'string', 'max' => 200],
            [['token'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'gender' => 'Gender',
            'birth_day' => 'Birth Day',
            'phone' => 'Phone',
            'user_pic' => 'User Pic',
            'e_mail' => 'E Mail',
            'login' => 'Login',
            'password' => 'Password',
            'token' => 'Token',
        ];
    }

    /**
     * Gets query for [[Bookings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::className(), ['user_id' => 'id']);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return;
    }

    public function validateAuthKey($authKey)
    {
        return;
    }

    public static function findByLogin($login)
    {
        return static::findOne(['login' => $login]);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }



}
