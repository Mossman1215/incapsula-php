<?php

namespace Incapsula\Api;

class SitesApi extends AbstractApi
{
    private $apiUri = 'https://my.incapsula.com/api/prov/v1/sites';

    /**
     * @param int $pageSize
     * @param int $pageNum
     *
     * @return array
     */
    public function list($pageSize = 50, $pageNum = 0)
    {
        return $this->client->send(sprintf('%s/list', $this->apiUri), [
            'page_size' => $pageSize,
            'page_num' => $pageNum,
        ]);
    }

    /**
     * @param string domain
     * @param string siteHost
     * @param string accountId
     * @return array sitestatus
     */
    public function add(string $accountId="",string $domain,string $siteHost)
    {
        $params = [
            "site_ip" => $siteHost,
            "domain" => $domain,
            "send_site_setup_emails" => "false",
            "force_ssl" => "true"
        ];
        #account ID is optional and is used for sub account support where applicable
        if ($accountId!="") {
            array_push($params,["account_id" => $accountId]);
        }
        return $this->client->send(sprintf('%s/add', $this->apiUri), $params);
    }
    /**
     * @param string $siteId
     *
     * @return array
     */
    public function status($siteId)
    {
        return $this->client->send(sprintf('%s/status', $this->apiUri), [
            'site_id' => $siteId,
        ]);
    }
    /**
     * @param string $siteId
     *
     * @return array
     */
    public function delete($siteId)
    {
        return $this->client->send(sprintf('%s/delete', $this->apiUri), [
            'site_id' => $siteId,
        ]);
    }

    /**
     * @param string $siteId
     * @param string $certificate
     * @param string $privateKey
     *
     * @return array
     */
    public function uploadCustomCertificate($siteId, $certificate, $privateKey)
    {
        return $this->client->send(sprintf('%s/customCertificate/upload', $this->apiUri), [
            'site_id' => $siteId,
            'certificate' => base64_encode($certificate),
            'private_key' => base64_encode($privateKey),
        ]);
    }

    /**
     * @param string $siteId
     *
     * @return array
     */
    public function removeCustomCertificate($siteId)
    {
        return $this->client->send(sprintf('%s/removeCustomCertificate', $this->apiUri), [
            'site_id' => $siteId,
        ]);
    }

    /**
     * @param string $siteId       site to purge
     * @param string $purgePattern is optional but to purge specific resources the format is as follows
     *                             purge all urls that contain text requires no additional formatting, e.g. image.jpg,
     *                             or to purge URLs starting with a pattern use  '^' e.g. "^maps/" ,
     *                             or to purge all URLs that end with a pattern use '$' e.g. ".jpg$"
     *                             See incapsula docs for details
     *                             https://docs.incapsula.com/Content/API/sites-api.htm#Purge
     *
     * @return array
     */
    public function purgeCache($siteId, $purgePattern = '')
    {
        return $this->client->send(sprintf('%s/cache/purge', $this->apiUri), [
            'site_id' => $siteId,
            'purge_pattern' => $purgePattern,
        ]);
    }

    /**
     * @param string $siteId        site to move
     * @param string $destAccountId account id to move the site to
     *
     * @return array containing response from incapsula with new dns details
     */
    public function moveSite($siteId, $destAccountId)
    {
        return $this->client->send(sprintf('%s/moveSite', $this->apiUri), [
            'site_id' => $siteId,
            'destination_account_id' => $destAccountId,
        ]);
    }

    /**
     * @param int $siteId   The site ID to retrieve all cache rules for
     * @param int $pageSize The number of rules to return per page
     * @param int $pageNum  The page number to return (if more than one page of results)
     *
     * @throws \Exception
     *
     * @return array
     */
    public function listCacheRules($siteId, $pageSize = 50, $pageNum = 0)
    {
        return $this->client->sendRaw(sprintf('%s/performance/caching-rules/list', $this->apiUri), [
            'site_id' => $siteId,
            'page_size' => $pageSize,
            'page_num' => $pageNum,
        ]);
    }
    /**
     * send request to set cache rule
     * @param string $rule
     * @param string $status
     */
    public function SetCacheRules($siteId,$rule,$status)
    {
        #TODO insert validation here
        $params["site_id"] = $siteId;
        $params["param"] = $rule;
        $params["value"] = $status;
        return $this->client->send(sprintf('%s/performance/advanced', $this->apiUri), $params);
    }
    /**
     * @param int $siteId   The site ID to add cache rule to

     * @throws \Exception
     *
     * @return array
     */
    public function addProtocolCacheRule($siteId)
    {
        return $this->client->send(sprintf('%s/performance/caching-rules/add', $this->apiUri), [
            'site_id' => $siteId,
            "name" => "fix incap caching protocol",
            "action" => "HTTP_CACHE_DIFFERENTIATE_SSL"
        ]);
    }

    /**
     * @param int $siteId   The site ID
     *
     * @return array
     */
    public function setStaticCacheMode($siteId)
    {
        return $this->client->send(sprintf('%s/performance/cache-mode', $this->apiUri), [
            'site_id' => $siteId,
            'cache_mode' => 'static_only',
        ]);
    }

    /**
     * @param int $siteid
     *
     * @return array
     */
    public function setSecurityRules($siteId)
    {
        /**
         * get json data for request parameters as arrays
         *  security.json => /api/prov/v1/sites/configure/security
         * make requests to api and append to a results datastructure
         */
        $security_conf = file_get_contents(__DIR__."/../../conf/security.json");
        if(!$security_conf){
            #crash saying: "failed to load config
            \throwException(new Exception("failed to load config at: ".__DIR__."/../../conf/security.json"));
        }
        $security_arr = json_decode($security_conf,true);
        if(!$security_arr){
            \throwException(new Exception("failed to parse as json at: ".__DIR__."/../../conf/security.json"));
        }
        $resultSet = [];
        foreach ($security_arr as $name => $settings) {
            sleep(0.1);
            $settings["site_id"]=$siteId;
            echo "for $name config".\var_export($settings).PHP_EOL;
            $result = $this->client->send(sprintf('%s/configure/security', $this->apiUri), array_merge($settings));
            //if result is ok add to result set
            $resultSet = array_merge($resultSet,$result);
        }
        return $resultSet;
    }
    /**
     * load config from a json file and push to incapsula
     *
     * @param int $siteid
     * @return array
     */
    public function addWhitelist($siteId){
        $xss_conf = file_get_contents(__DIR__."/../../conf/cross-site-whitelist.json");
        if(!$xss_conf){
            #crash saying: "failed to load config
            \throwException(new Exception("failed to load config at: ".__DIR__."/../../conf/cross-site-whitelist.json"));
        }
        $xss_conf_arr = json_decode($xss_conf,true);
        if(!$xss_conf_arr){
            #crash saying: "failed to load config
            \throwException(new Exception("failed to parse json at: ".__DIR__."/../../conf/cross-site-whitelist.json"));
        }
        $params = \array_merge($xss_conf_arr,['site_id' => $siteId]);
        echo \var_export($params).PHP_EOL;
        return $this->client->send(sprintf('%s/configure/whitelists', $this->apiUri), $params);
    }
}
