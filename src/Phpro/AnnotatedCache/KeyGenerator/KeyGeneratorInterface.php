<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCache\KeyGenerator;

use Phpro\AnnotatedCache\Annotation\CacheAnnotationInterface;
use Phpro\AnnotatedCache\Exception\UnsupportedKeyParameterException;
use Phpro\AnnotatedCache\Interception\InterceptionInterface;

/**
 * Interface KeyGeneratorInterface
 *
 * @package Phpro\AnnotatedCache\KeyGenerator
 */
interface KeyGeneratorInterface
{

    /**
     * @param InterceptionInterface    $interception
     * @param CacheAnnotationInterface $annotation
     *
     * @return string
     * @throws UnsupportedKeyParameterException
     */
    public function generateKey(InterceptionInterface $interception, CacheAnnotationInterface $annotation) : string;
}
