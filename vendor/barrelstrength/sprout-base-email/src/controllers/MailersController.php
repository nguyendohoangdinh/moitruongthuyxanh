<?php

namespace barrelstrength\sproutbaseemail\controllers;

use barrelstrength\sproutbaseemail\base\EmailElement;
use Craft;
use craft\web\Controller;
use Throwable;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class MailersController extends Controller
{
    /**
     * Provides a way for mailers to render content to perform actions inside a a modal window
     *
     * @return Response
     * @throws Throwable
     * @throws BadRequestHttpException
     */
    public function actionGetPrepareModal(): Response
    {
        $this->requirePostRequest();

        $emailId = Craft::$app->getRequest()->getBodyParam('emailId');

        /** @var EmailElement $email */
        $email = Craft::$app->getElements()->getElementById($emailId);

        if (!$email) {
            throw new InvalidArgumentException('No Email exists with the ID: '.$emailId);
        }

        $mailer = $email->getMailer();
        $modal = $mailer->getPrepareModal($email);

        return $this->asJson($modal->getAttributes());
    }
}
