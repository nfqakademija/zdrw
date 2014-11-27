<?php
namespace Zdrw\OffersBundle\Services;

class UserInfoProvider
{
    public function userInfo($user)
    {
        $id = $user->getId();
        $name = $user->getUsername();
        $nick = $user->getNickname();
        $email = $user->getEmail();
        return array(
            'id' => $id,
            'name' => $name,
            'nick' => $nick,
            'email' => $email
        );
    }
}