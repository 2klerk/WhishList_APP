<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\EntryForm;
use \app\models\RegistrationForm;
use app\models\ContactForm;
use yii\data\ActiveDataProvider;


class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }


    /**
     * Отображает страницу со списком желаний
     *
     * @return string
     */
    public function actionWishlist()
    {
        return $this->render('wishlist');
    }

    /**
     * Отображает страницу с личным кабинетом пользователя
     *
     * @return string
     */
    public function actionCabinet()
    {
        return $this->render('cabinet');
    }

    public function actionFriends()
    {
        return $this->render('friends');
    }

    public function actionPhpinfo()
    {
        phpinfo();
    }

    public function actionEntry()
    {
        $model = new EntryForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            return $this->render('entry-confirm', ['model' => $model]);
        } else {
            // либо страница отображается первый раз, либо есть ошибка в данных
            return $this->render('entry', ['model' => $model]);
        }
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionReg()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new RegistrationForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $command = Yii::$app->db->createCommand('CALL add_user(:name, :surname, :email, :password)');
            $command->bindValue(':name', $model->name);
            $command->bindValue(':surname', $model->surname);
            $command->bindValue(':email', $model->email);
            $command->bindValue(':password', $model->password);

            try {
                $command->execute();
                Yii::$app->session->setFlash('success', 'Пользователь успешно зарегистрирован. ('.$model->name.' '.$model->surname.')');
                return $this->redirect(['site/login']);
            } catch (\Exception $e) {
                Yii::error('Ошибка при создании пользователя: ' . $e->getMessage());
                Yii::$app->session->setFlash('error', 'Произошла ошибка при регистрации пользователя.');
            }
        }

        return $this->render('reg', [
            'model' => $model,
        ]);
    }


}
