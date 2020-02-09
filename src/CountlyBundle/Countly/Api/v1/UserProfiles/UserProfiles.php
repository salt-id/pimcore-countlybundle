<?php
/**
 * Created by PhpStorm.
 * User: Yulius Ardian Febrianto <yuliusardin@gmail.com>
 * Date: 09/02/2020
 * Time: 20:40
 */

namespace SaltId\CountlyBundle\Countly\Api\v1\UserProfiles;

use GuzzleHttp\Exception\GuzzleException;
use SaltId\CountlyBundle\Countly\Api\v1\Countly;

class UserProfiles extends Countly
{
    public function getUserDetails($params = [])
    {
        $defaultParams = [
            'query' => [
                'method' => 'user_details'
            ]
        ];

        $params = array_merge_recursive($defaultParams, $params);

        try {
            return $this->executeRequest('GET', '', $params);
        } catch (GuzzleException $e) {

        }
    }
}