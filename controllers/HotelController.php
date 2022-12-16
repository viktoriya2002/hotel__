<?php
namespace app\controllers;
use app\models\Hotel;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\web\Request;
use yii\web\UploadedFile;

class HotelController extends FunctionController

{
    public $modelClass = 'app\models\Hotel';
    public function behaviors()
    {
        /*
         * Указание на аутентификации по токену
         */
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'only'=>['create']
        ];

        return $behaviors;
    }

    public function actionCreate()
    {
        $request = Yii::$app->request->post();
        $hotel = new Hotel($request);
        $user=Yii::$app->user->identity;
        if ($user->admin==0) return $this->send(403,['message'=>'Forbidden']);
        if (!$hotel->validate()) return $this->validation($hotel);
        $hotel->save();//Сохранение модели в БД
        return $this->send(200, ['content' => ['code' => 200, 'message' => 'Отель успешно создан!']]);

    }

    public function actionHotel($id)
    {
        $hotel=Hotel::findOne($id);
        if (!$hotel) return $this->send (404, ['data'=>['hotel'=>[]]]);
        return $this->send(200,$hotel);
    }

    public function actionSearch()
    {
        $hotel=Hotel::find()->all();
        return $this->send(200,['data'=>['orders'=>$hotel]]);
    }

    public function actionDelete($id)
    {
        $hotel=Hotel::findOne($id);
        $user=Yii::$app->user->identity;
        if ($user->admin==0) return $this->send(403,['message'=>'Forbidden']);
        if (!$hotel)
            return $this->send(404,['message'=>'Отель не найден.']);
        $hotel->delete();
        return $this->send(200, ['body'=>'Отель удален.']);
    }
}