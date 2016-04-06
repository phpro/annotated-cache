<?php

namespace Phpro\AnnotatedCache\KeyGenerator;

use Phpro\AnnotatedCache\Exception\UnsupportedKeyParameterException;

/**
 * Interface KeyGeneratorInterface
 *
 * @package Phpro\AnnotatedCache\KeyGenerator
 */
interface KeyGeneratorInterface
{

    /**
     * @param array  $parameters
     * @param string $format
     *
     * @return string
     * @throws UnsupportedKeyParameterException
     */
    public function generateKey(array $parameters, $format = '');
}
