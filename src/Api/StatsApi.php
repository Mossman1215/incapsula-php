<?php

namespace Incapsula\Api;

/**
 * Wrapper for the API endpoint for statistics
 * https://docs.incapsula.com/Content/API/traffic-api.htm.
 */
class StatsApi extends AbstractApi
{
    private $apiUri = 'https://my.incapsula.com/api/stats/v1';

    /**
     * @param string $siteId
     * @param mixed  $startTime
     * @param mixed  $endTime
     *
     * @return array
     */
    public function getBandwidthStats($siteId, $startTime, $endTime)
    {
        $time = $this->parseDateTime($startTime, $endTime);
        $incap_response = $this->client->send($this->apiUri, [
            'site_id' => $siteId,
            'time_range' => 'custom',
            'start' => $time['startTimeMili'],
            'end' => $time['endTimeMili'],
            'stats' => 'bandwidth_timeseries',
        ]);
        $incap_data = $incap_response['bandwidth_timeseries'][0]['data'];
        ksort($incap_data);

        return $incap_data;
    }

    public function getCacheStats($siteId, $startTime, $endTime)
    {
        $time = $this->parseDateTime($startTime, $endTime);
        $startMili = $time['startTimeMili'];
        $endMili = $time['endTimeMili'];
        $incap_response = $this->client->send($this->apiUri, [
            'site_id' => $siteId,
            'time_range' => 'custom',
            'start' => $startMili,
            'end' => $endMili,
            'stats' => 'caching_timeseries',
        ]);
        //$cache['caching_timeseries']['1']['data'] standard caching data
        $incap_data['StandardCache'] = $incap_response['caching_timeseries']['1']['data'];
        ksort($incap_data['StandardCache']);
        //$cache['caching_timeseries']['3']['data'] advanced caching data
        $incap_data['AdvancedCache'] = $incap_response['caching_timeseries']['3']['data'];
        ksort($incap_data['AdvancedCache']);
        return $incap_data;
    }

    /**
     *   @param mixed $startTime
     *   @param mixed $endTime
     *   check date function
     *    TODO: input sanity checks
     *    All dates should be specified as number of milliseconds since midnight 1970 (UNIX time * 1000)
     */
    private function parseDateTime($startTime, $endTime)
    {
        if (!strtotime($startTime)) {
            echo 'start date parse failure'.PHP_EOL;
        }
        $time['startTimeMili'] = strtotime($startTime) * 1000;
        if (!strtotime($endTime)) {
            echo 'end date parse failure'.PHP_EOL;
        }
        $time['endTimeMili'] = strtotime($endTime) * 1000;
        //TODO enforce start < end?
        return $time;
    }

    // TODO:convert incapsula mili seconds unix time to standard php date objects
    // TODO:convert to MiB
}
