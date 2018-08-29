<?php

namespace AppBundle\Twig;

use Symfony\Bridge\Doctrine\RegistryInterface;

class UserStatsExtension extends \Twig_Extension {

    protected $doctrine;

    public function __construct(RegistryInterface $doctrine) {
        $this->doctrine = $doctrine;
    }

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('user_stats', array($this, 'userStatsFilter'))
        );
    }

    public function userStatsFilter($user) {
        $like_repo = $this->doctrine->getRepository('BackendBundle:Like');
        $publication_repo = $this->doctrine->getRepository('BackendBundle:Publication');
        $following_repo = $this->doctrine->getRepository('BackendBundle:Following');

        $user_folowwing = $following_repo->findBy(array('user' => $user));
        $user_folowwers = $following_repo->findBy(array('followed' => $user));
        $user_publications = $publication_repo->findBy(array('user' => $user));
        $user_likes = $like_repo->findBy(array('user' => $user));

        $result = array(
            'following' => count($user_folowwing),
            'followers' => count($user_folowwers),
            'publications' => count($user_publications),
            'likes' => count($user_likes)
        );
        return $result;
    }

    public function getName() {
        return 'user_stats_extension';
    }

}
