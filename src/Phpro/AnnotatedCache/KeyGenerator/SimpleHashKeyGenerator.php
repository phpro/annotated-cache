<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCache\KeyGenerator;

use Phpro\AnnotatedCache\Annotation\CacheAnnotationInterface;
use Phpro\AnnotatedCache\Exception\UnsupportedKeyParameterException;
use Phpro\AnnotatedCache\Interception\InterceptionInterface;

/**
 * Class SimpleHashKeyGenerator
 *
 * @package Phpro\AnnotatedCache\KeyGenerator
 */
class SimpleHashKeyGenerator implements KeyGeneratorInterface
{
    /**
     * @param InterceptionInterface    $interception
     * @param CacheAnnotationInterface $annotation
     *
     * @return string
     * @throws UnsupportedKeyParameterException
     */
    public function generateKey(InterceptionInterface $interception, CacheAnnotationInterface $annotation) : string
    {
        $hash = get_class($interception->getInstance()) . '::' . $interception->getMethod() . ';';
        foreach ($interception->getParams() as $key => $value) {
            if (null === $value) {
                $paramHash = 'null';
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
