<?php

namespace born05\twofactorauthentication\controllers;

use Craft;
use craft\web\Controller;
use craft\elements\User;
use born05\twofactorauthentication\Plugin as TwoFactorAuth;
use born05\twofactorauthentication\web\assets\verify\VerifyAsset;

use yii\base\Event;
use yii\web\UserEvent;
use yii\web\Response;

class VerifyController extends Controller
{
    /**
     * Show the verify form.
     */
    public function actionLogin()
    {
        $this->requireLogin();
        Craft::$app->view->registerAssetBundle(VerifyAsset::class);
        return $this->renderTemplate('two-factor-authentication/_verify');
    }

    /**
     * Handle the verify post.
     */
    public function actionLoginProcess()
    {
        $this->requireLogin();
        $this->requirePostRequest();
        $request = Craft::$app->getRequest();

        $authenticationCode = $request->getBodyParam('authenticationCode');

        // Get the current user
        $user = Craft::$app->getUser()->getIdentity();

        if (TwoFactorAuth::$plugin->verify->verify($user, $authenticationCode)) {
            // Get the session duration
            $sessionDuration = Craft::$app->getUser()->getRemainingSessionTime();

            // Throw the after login event, because we blocked it earlier for non-cookieBased events.
            $this->afterLogin($user, false, $sessionDuration);

            return $this->_handleSuccessfulLogin(true);
        } else {
            return $this->asFailure(
                Craft::t('two-factor-authentication', 'Authentication code is invalid.'),
                data: [
                    'errorCode' => User::AUTH_INVALID_CREDENTIALS,
                ],
            );
        }
    }

    /**
     * COPIED from \craft\controllers\UsersController::_handleSuccessfulLogin
     *
     * Redirects the user after a successful login attempt, or if they visited the Login page while they were already
     * logged in.
     *
     * @return Response
     */
    private function _handleSuccessfulLogin(): Response
    {
        // Get the return URL
        $userSession = Craft::$app->getUser();
        $returnUrl = $userSession->getReturnUrl();

        // Clear it out
        $userSession->removeReturnUrl();

        // If this was an Ajax request, just return success:true
        if ($this->request->getAcceptsJson()) {
            return $this->asSuccess(redirect: $returnUrl);
        }

        return $this->redirectToPostedUrl($userSession->getIdentity(), $returnUrl);
    }

    /**
     * COPIED FROM \yii\web\User
     *
     * This method is called after the user is successfully logged in.
     * The default implementation will trigger the [[EVENT_AFTER_LOGIN]] event.
     * If you override this method, make sure you call the parent implementation
     * so that the event is triggered.
     * @param IdentityInterface $identity the user identity information
     * @param bool $cookieBased whether the login is cookie-based
     * @param int $duration number of seconds that the user can remain in logged-in status.
     * If 0, it means login till the user closes the browser or the session is manually destroyed.
     */
    protected function afterLogin($identity, $cookieBased, $duration)
    {
        Event::trigger(\craft\web\User::class, \craft\web\User::EVENT_AFTER_LOGIN, new UserEvent([
            'identity' => $identity,
            'cookieBased' => $cookieBased,
            'duration' => $duration,
        ]));
    }
}
