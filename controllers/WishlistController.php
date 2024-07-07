<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Wishlist;
use app\models\WishlistForm;

class WishlistController extends Controller {
    public function actionCreateWish() {
        if (Yii::$app->user->identity) {
            $wish = new WishlistForm();

            if ($wish->load(Yii::$app->request->post()) && $wish->validate()) {
                return $this->render('create-wish', ['wish' => $wish]);
            }   // TODO: Добавить обработку ошибки
        } else {
            actionMyError('Чтобы создать хотелку, авторизуйтесь!');
        }
    }

    public function actionGetWishlist() {
        $wishlist = Yii::$app->db->createCommand('CALL add_wishlist()');
        return $this->render('get-wishlist', ['wishlist' => $wishlist]);
    }

    // Метод для возврата пользовательской ошибки
    public function actionMyError($message) {
        return $this->render('my-error', ['message' => $message]);
    }
}

?>