<?php

namespace Prowebcraft;

/**
 * Class for signing and verifying requests.
 * User: Andrey Mistulov
 */
class Signature
{

    protected string $salt = '';

    /**
     * @param string $salt
     */
    public function __construct(string $salt)
    {
        $this->salt = $salt;
    }

    /**
     * Sign path and params
     * @param string $path
     * @param array $params
     * @return string
     */
    public function sign(string $path = '', array $params = []): string
    {
        $res = [];
        foreach ($params as $key => $val) {
            if (strtolower($key) === 'signature') {
                continue;
            }
            if (is_array($val)) {
                $res[] = $key . json_encode($val);
            } else {
                $res[] = $key . $val;
            }
        }
        sort($res);
        $signString = $path . implode('', $res);
        return strtoupper(bin2hex(hash_hmac("sha1", $signString, $this->salt, true)));
    }

    /**
     * Check incoming request with signature
     * @param string $signature
     * @param string $path
     * @param array $params
     * @return bool
     */
    public function check(string $signature, string $path = '', array $params = []): bool
    {
        return $this->sign($path, $params) === $signature;
    }
}