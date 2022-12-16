<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hotel".
 *
 * @property int $id
 * @property string $name
 * @property string $hot_phone
 * @property string $email
 * @property string $address
 * @property string|null $photo
 * @property string $description
 *
 * @property Booking[] $bookings
 */
class Hotel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hotel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'hot_phone', 'email', 'address', 'description'], 'required'],
            [['name', 'hot_phone', 'email', 'address', 'photo', 'description'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'hot_phone' => 'Hot Phone',
            'email' => 'Email',
            'address' => 'Address',
            'photo' => 'Photo',
            'description' => 'Description',
        ];
    }

    /**
     * Gets query for [[Bookings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::className(), ['hotel_id' => 'id']);
    }

}
