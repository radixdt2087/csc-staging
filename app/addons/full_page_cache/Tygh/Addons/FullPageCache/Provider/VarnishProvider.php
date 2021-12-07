<?php
/***************************************************************************
 *                                                                          *
 *   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
 *                                                                          *
 * This  is  commercial  software,  only  users  who have purchased a valid *
 * license  and  accept  to the terms of the  License Agreement can install *
 * and use this program.                                                    *
 *                                                                          *
 ****************************************************************************
 * PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
 * "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
 ****************************************************************************/

namespace Tygh\Addons\FullPageCache\Provider;

use Tygh\Addons\FullPageCache\ProviderInterface;

class VarnishProvider implements ProviderInterface
{
    const HTTP_HEADER_NAME_CACHE_TAGS = 'X-Cache-Tags';
    const HTTP_HEADER_NAME_CACHE_TTL = 'X-Cache-TTL';
    const HTTP_HEADER_NAME_DO_ESI = 'X-Do-ESI';
    const HTTP_SUCCESS_STATUS_CODE = 204;

    /**
     * @var string Varnish server IP
     */
    protected $server_ip = '127.0.0.1';

    /**
     * @var string Varnish server port
     */
    protected $server_port = '80';

    /**
     * VarnishProvider constructor
     *
     * @param string $varnish_server_ip
     * @param string $varnish_server_port
     */
    public function __construct($varnish_server_ip, $varnish_server_port)
    {
        $this->server_ip = trim($varnish_server_ip);
        $this->server_port = trim($varnish_server_port);
    }
    /**
     * @inheritDoc
     */
    public function invalidateCacheByTags(array $tags)
    {
        $tags = array_unique($tags);

        return $this->makeRequest('BAN', [
            sprintf('%s: %s', self::HTTP_HEADER_NAME_CACHE_TAGS, implode('|', $tags))
        ]);
    }

    /**
     * @inheritDoc
     */
    public function buildPageHeaders($ttl = 180, array $tags = [], $is_allow_esi = false)
    {
        $tags = array_unique($tags);

        $headers = [
            sprintf('%s: %s', self::HTTP_HEADER_NAME_CACHE_TAGS, implode(',', $tags)),
        ];

        if ($ttl) {
            $headers[] = sprintf('%s: %ss', self::HTTP_HEADER_NAME_CACHE_TTL, (int) $ttl);
        }

        if ($is_allow_esi) {
            $headers[] = sprintf('%s: %s', self::HTTP_HEADER_NAME_DO_ESI, (int) $is_allow_esi);
        }

        return $headers;
    }

    /**
     * @inheritDoc
     */
    public function isEsiRequest()
    {
        return isset($_SERVER['HTTP_X_VARNISH_ESI']) && $_SERVER['HTTP_X_VARNISH_ESI'] == 'true';
    }

    /**
     * @inheritDoc
     */
    public function renderESIBlock($url, $block_content, $debug = false)
    {
        $return = sprintf('%s<esi:include src="%s"/><esi:remove>%s</esi:remove>',
            $debug ? "<!-- ESI render URL: {$url} -->" : '',
            $url,
            $block_content
        );

        return $return;
    }

    /**
     * @param string $method
     * @param array  $headers
     *
     * @return bool
     */
    protected function makeRequest($method, array $headers = [])
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->server_ip);
        curl_setopt($ch, CURLOPT_PORT, $this->server_port);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        $content = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($http_code === self::HTTP_SUCCESS_STATUS_CODE) {
            return true;
        }

        error_log(sprintf('[%s]: Full Page Cache Add-on: Unable to make request on varnish server %s:%s; file %s; line: %s',
            date('d-m-Y H:i:s'),
            $this->server_ip,
            $this->server_port,
            __FILE__,
            __LINE__
        ));

        return false;
    }
}