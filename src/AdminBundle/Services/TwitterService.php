<?php

namespace AdminBundle\Services;

use Codebird\Codebird;

/**
 * Service to handle twitter post
 *
 * @author Rajesh Meniya
 */
class TwitterService
{
    /**
     * Service container
     *
     * @var container
     */
    protected $container;

    /**
     * construct
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function postTweet($status, $filename = null)
    {
        Codebird::setConsumerKey(
            $this->container->getParameter('twitter_consumer_key'),
            $this->container->getParameter('twitter_consumer_secret'));

        $cb = Codebird::getInstance();

        $cb->setToken(
            $this->container->getParameter('twitter_access_token'),
            $this->container->getParameter('twitter_access_secret'));

        if ($filename){
            $reply = $cb->media_upload([
                'media' => 'https:/medalmanah.ru'.$filename
            ]);
            var_dump($reply);
            // and collect their IDs
            $media_ids[] = $reply->media_id_string;
        }
        $media_ids = implode(',', $media_ids);

        $reply = $cb->statuses_update(['status' => $status, 'media_ids' => $media_ids]);

        return $reply;
    }
}