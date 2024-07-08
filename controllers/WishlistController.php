<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Wishlist;
use app\models\WishlistForm;

/**
 * Класс контроллер для таблицы wishlist БД.
 */
class WishlistController extends Controller {
    /**
     * Действие по созданию и добавлению элемента в таблицу wishlist БД.
     * Если пользователь не авторизован, будет вызвано действие my-error.
     * 
     * @return string
     */
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
                    ->execute()
                ;

                return $this->render('create-wish', ['wish' => $wish]);
            } else {

                return $this->render('create-wish', ['wish' => $wish]);
            }
        } else {
            $this->actionMyError('Чтобы добавить подарок, авторизуйтесь!');
        }
    }

    /**
     * Метод получения всех элементов из таблицы wishlist БД.
     * 
     * @return string
     */
    public function actionGetWishlist() {
        $userId = Yii::$app->request->get('userId', null);
        if ($userId === null) {
            throw new \yii\web\BadRequestHttpException('Missing required parameters: userId');
        }

        $wishlist = Yii::$app->db->createCommand('SELECT get_wishlist(:userId)')
                                ->bindValue(':userId', (int) $userId)
                                ->queryAll()
        ;

        return $this->render('get-wishlist', ['wishlist' => $wishlist]);
    }

    /**
     * Метод редактирования существующего элемента таблицы wishlist БД.
     * Редактируемый элемент определяется в URL
     * 
     * @return string
     */
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
                    ->execute()
                ;

                return $this->render('update-wish', ['wish' => $wish]);
            } else {

                return $this->render('update-wish', ['wish' => $wish]);
            }
        } else {
            $this->actionMyError('Чтобы редактировать подарок, авторизуйтесь!');
        }
    }

    /**
     * Метод удаления элемента таблицы wishlist БД.
     * Удаляемый элемент определяется в URL
     * 
     * @return string
     */
    public function actionDeleteWish() {
        if (Yii::$app->user->identity) {
            $wishid = Yii::$app->request->get('wishid', null);
            Yii::$app->db->createCommand()
            ->delete('wishlist', 'wishid=:wishid')
            ->bindValue(':wishid', $wishid)
            ->execute()
        ;

            return $this->render('delete-wish', ['wishid' =>$wishid]);
        } else {
            $this->actionMyError('Чтобы удалить подарок, авторизуйтесь');
        }
    }

    /**
     * Метод вывода пользовательской ошибки.
     * 
     * @param string $message   Сообщение, которое передаётся в действие my-error и будет выведено пользователю
     * 
     * @return string
     */
    public function actionMyError($message = '') {
        return $this->render('my-error', ['message' => $message]);
    }
}

?>