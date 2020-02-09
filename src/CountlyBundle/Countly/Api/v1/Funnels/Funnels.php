<?php
/**
 * Created by PhpStorm.
 * User: Yulius Ardian Febrianto <yuliusardin@gmail.com>
 * Date: 09/02/2020
 * Time: 14:30
 */

namespace SaltId\CountlyBundle\Countly\Api\v1\Funnels;

use GuzzleHttp\Exception\GuzzleException;
use SaltId\CountlyBundle\Countly\Api\v1\Countly;

class Funnels extends Countly
{
    public function getFunnels()
    {
        $params = [
            'query' => [
                'method' => 'get_funnels'
            ]
        ];
        try {
            return $this->executeRequest('GET', '', $params);
        } catch (GuzzleException $e) {

        }
    }

    public function getFunnelDetail($funnelId, $period = '7days')
    {
        $params = [
            'query' => [
                'method' => 'funnel',
                'funnel' => $funnelId,
                'period' => $period,
            ]
        ];

        try {
            return $this->executeRequest('GET', '', $params);
        } catch (GuzzleException $e) {

        }
    }
}