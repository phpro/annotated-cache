<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCache\KeyGenerator;

use Phpro\AnnotatedCache\Exception\UnsupportedKeyParameterException;

/**
 * Class SimpleHashKeyGenerator
 *
 * @package Phpro\AnnotatedCache\KeyGenerator
 */
class SimpleHashKeyGenerator implements KeyGeneratorInterface
{
    /**
     * @param array $parameters
     *
     * @return mixed
     * @throws UnsupportedKeyParameterException
     */
    public function generateKey(array $parameters, $format = '') : string
    {
        $hash = 1234;
        foreach ($parameters as $key => $value) {
            if (null === $value) {
                $paramHash = 5678;
            } elseif (is_scalar($value)) {
                $paramHash = sha1($value);
            } elseif (is_array($value) || is_object($value)) {
                $paramHash = sha1(serialize($value));
            } else {
                throw new UnsupportedKeyParameterException(
                    sprintf('Not supported parameter type "%s"', gettype($value))
                );
            }
            $hash .= $key . '=' . $paramHash . ';';
        }
        return sha1($hash);
    }
}
