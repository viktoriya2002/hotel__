<?php
namespace app\controllers;
use yii\rest\ActiveController;
use app\models\Booking;
use app\models\ContactForm;
use app\models\Hotel;
use app\models\LoginForm;
use app\models\User;
use Yii;
use yii\filters\auth\HttpBearerAuth;
class UserController extends FunctionController
{
    public $modelClass = 'app\models\User';


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'only'=>['delete', 'account']
        ];
        return $behaviors;
    }

    public function actionCreate()
    {
        $request = Yii::$app->request->post(); //получение данных из post запроса
        $user = new User($request); // Создание модели на основе присланных данных
        if (!$user->validate()) return $this->validation($user); //Валидация модели
        $user->password = Yii::$app->getSecurity()->generatePasswordHash($user->password); //хэширование пароля
        $user->save();//Сохранение модели в БД
        return $this->send(201, ['content' => ['code' => 201, 'message' => 'Вы успешно зарегистрировались!']]);//Отправка сообщения пользователю
    }

    public function actionLogin()
    {
        $request = Yii::$app->request->post();
        $loginForm=new LoginForm($request);
        if (!$loginForm->validate()) return $this->validation($loginForm);
        $user=User::find()->where(['login'=>$request['login']])->one();
        if (isset($user) && Yii::$app->getSecurity()->validatePassword($request['password'], $user->password)){
            $user->token=Yii::$app->getSecurity()->generateRandomString();
            $user->save(false);
            return $this->send(200, ['content'=>['token'=>$user->token]]);
        }
        return $this->send(401, ['content'=>['code'=>401, 'message'=>'Неверный логин или пароль!']]);
    }

    public function actionDelete()
{
    $user=Yii::$app->user->identity;
    $user->delete();
    return $this->send(200, ['body'=>'Пользователь удален. Счастливого пути!']);
}

    public function actionAccount()
    {
        $user=Yii::$app->user->identity;
        return $this->send(200,$user);
    }
}
