<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\Controller;

// Обратите внимание на этот импорт
use app\models\Wish;

class WishController extends Controller
{
    public $modelClass = 'app\models\Wish';

    public function actionGetWish()
    {
        $userid = Yii::$app->user->id;
        if ($userid === null) {
            throw new \yii\web\UnauthorizedHttpException('Вы должны быть авторизованы.');
        }

        $wishes = Wish::find()->where(['userid' => $userid])->all();

        return $this->render('wish', [
            'model' => new $this->modelClass(),
            'wishes' => $wishes,
        ]);
    }


    public function actionAddWish()
    {
        $data = Yii::$app->request->post();
//        $data['userid'] = Yii::$app->user->id;
        Yii::info("print-json: " . json_encode($data), __METHOD__);

        $wish = new $this->modelClass();
        $wish->userid = $data['userid'];
        $wish->name = $data['name'] ?? null;
        $wish->price = $data['price'] ?? null;
        $wish->img_path = $data['img_path'] ?? null;
        $wish->url = $data['url'] ?? null;

        if ($wish->save()) {
            return $wish;
        } else {
            Yii::error($wish->getErrors(), __METHOD__);
            throw new \yii\web\ServerErrorHttpException('Failed to create the wish for unknown reasons.');
        }
    }


    public function actionCreateWish()
    {
        $model = new Wish();
        $data = Yii::$app->request->post();

        if ($data->load(Yii::$app->request->post()) && $model->save()) {
            Yii::info('Успешное создание желания');
            return $this->asJson([
                'success' => true,
                'wishes' => Wish::find()->where(['userid' => Yii::$app->user->id])->all(),
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $wish = Wish::findOne($id);
        if ($wish === null || $wish->userid !== Yii::$app->user->id) {
            throw new NotFoundHttpException('Желание не найдено.');
        }

        if ($wish->load(Yii::$app->request->post()) && $wish->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $wish,
        ]);
    }

    public function actionDelete($id)
    {
        $wish = Wish::findOne($id);
        if ($wish !== null && $wish->userid === Yii::$app->user->id) {
            $wish->delete();
        }

        return $this->redirect(['index']);
    }
}
