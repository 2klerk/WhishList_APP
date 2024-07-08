<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Wishlist;
use app\models\WishlistForm;

class WishlistController extends Controller {
    public function actionCreateWish() {
        if (Yii::$app->user->identity) {
            $userId = Yii::$app->request->get('userId', null);
            $wish = new WishlistForm();
            if ($wish->load(Yii::$app->request->post()) && $wish->validate()) {
                // Yii::$app->db->createCommand()->insert('wishlist', [
                //     'name' => $wish->name,
                //     'price' => $wish->price,
                //     'url' => $wish->url,
                //     'img_path' => $wish->img_path,
                //     'category' => $wish->category,
                // ])->execute();
                Yii::$app->db->createCommand('CALL add_wish(:userid, :name, :surname, :category, :price, :img_path, :url)')
                    ->bindValue(':userid', $userId)
                    ->bindValue(':name', $wish->name)
                    ->bindValue(':surname', "123")
                    ->bindValue(':category', $wish->category)
                    ->bindValue(':price', (int) $wish->price)
                    ->bindValue(':img_path', $wish->img_path)
                    ->bindValue(':url', $wish->url)
                    ->execute();
                return $this->render('create-wish', ['wish' => $wish]);
            } else {
                return $this->render('create-wish', ['wish' => $wish]);
            }
        } else {
            $this->actionMyError('Чтобы создать хотелку, авторизуйтесь!');
        }
    }

    public function actionGetWishlist() {
        $userId = Yii::$app->request->get('userId', null);
        if ($userId === null) {
            throw new \yii\web\BadRequestHttpException('Missing required parameters: userId');
        }

        // Выполнение запроса к базе данных
        $wishlist = Yii::$app->db->createCommand('SELECT get_wishlist(:userId)')
                                ->bindValue(':userId', (int) $userId)
                                ->queryAll();
        return $this->render('get-wishlist', ['wishlist' => $wishlist]);
    }

    public function actionUpdateWish() {
        if (Yii::$app->user->identity) {
            $wishid = Yii::$app->request->get('wishid', null);
            $wish = new WishlistForm();
            if ($wish->load(Yii::$app->request->post()) && $wish->validate()) {
                Yii::$app->db->createCommand()
                    ->update('wishlist', [
                        'name' => $wish->name,
                        'price' => $wish->price,
                        'url' => $wish->url,
                        'img_path' => $wish->img_path,
                        'category' => $wish->category,
                    ],'wishid=:wishid')
                    ->bindValue(':wishid', $wishid)
                    ->execute();
                return $this->render('update-wish', ['wish' => $wish]);
            } else {
                return $this->render('update-wish', ['wish' => $wish]);
            }
        } else {
            $this->actionMyError('Чтобы редактировать хотелку, авторизуйтесь!');
        }
    }

    public function actionDeleteWish() {
        if (Yii::$app->user->identity) {
            $wishid = Yii::$app->request->get('wishid', null);
            Yii::$app->db->createCommand()
            ->delete('wishlist', 'wishid=:wishid')
            ->bindValue(':wishid', $wishid)
            ->execute();
            return $this->render('delete-wish', ['wishid' =>$wishid]);
        } else {
            $this->actionMyError('Чтобы удалить хотелку, авторизуйтесь');
        }
    }

    // Метод для возврата пользовательской ошибки
    public function actionMyError($message = 'sa') {
        return $this->render('my-error', ['message' => $message]);
    }
}

?>