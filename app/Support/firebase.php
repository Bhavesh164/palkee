<?php

namespace App\Support;

include_once(getcwd() . '/app/Support/vendor/autoload.php');

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Firebase\Auth\Token\Exception\InvalidToken;

class firebase
{
    public static function init()
    {
        $firebase = "";
        try {
            
            $serviceAccount = ServiceAccount::fromJsonFile(__DIR__ . '/palkee-taxi-firebase-adminsdk-z4reg-ded453ce82.json');

            $firebase = (new Factory)
                ->withServiceAccount($serviceAccount)
                ->create();
        } catch (Exception $e) {
        }
        return $firebase;
    }
    public static function verify_token($firebase, $idTokenString)
    {
        $user = array();
        try {
            $verifiedIdToken = $firebase->getAuth()->verifyIdToken($idTokenString);
        } catch (InvalidToken $e) {
            //echo $e->getMessage();
        }
        catch (\InvalidArgumentException $e) {
           // echo 'The token could not be parsed: ' . $e->getMessage();
        }
        if (!empty($verifiedIdToken)) {
            $uid = $verifiedIdToken->getClaim('sub');
            $user = $firebase->getAuth()->getUser($uid);
        }
        return $user;
    }

    public static function get_user($firebase, $phone)
    {
        try {
            $user = $firebase->createAuth()->getUserByPhoneNumber($phone);
        } catch (UserNotFound $e) {
            // echo $e->getMessage();
        }
        return $user;
    }

    public static function delete_user($firebase, $user_id)
    {
        if (!empty($user_id)) {
            $firebase->createAuth()->deleteUser($user_id);
        }
    }
}
