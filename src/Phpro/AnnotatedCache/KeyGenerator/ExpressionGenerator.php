<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCache\KeyGenerator;

use Phpro\AnnotatedCache\Annotation\CacheAnnotationInterface;
use Phpro\AnnotatedCache\Interception\InterceptionInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * Class ExpressionGenerator
 *
 * @package Phpro\AnnotatedCache\KeyGenerator
 */
class ExpressionGenerator implements KeyGeneratorInterface
{

    /**
     * @var ExpressionLanguage
     */
    private $language;

    /**
     * @var KeyGeneratorInterface
     */
    private $keyGenerator;

    /**
     * ExpressionGenerator constructor.
     */
    public function __construct(KeyGeneratorInterface $keyGenerator)
    {
        $this->language = new ExpressionLanguage();
        $this->keyGenerator = $keyGenerator;
    }

    /**
     * @param InterceptionInterface    $interception
     * @param CacheAnnotationInterface $annotation
     *
     * @return string
     * @throws \Phpro\AnnotatedCache\Exception\UnsupportedKeyParameterException
     */
    public function generateKey(InterceptionInterface $interception, CacheAnnotationInterface $annotation) : string
    {
        $format = property_exists($annotation, 'key') ? $annotation->key : '';
        $parameters = array_merge($interception->getParams(), ['interception' => $interception]);
        if ($format && $result = $this->language->evaluate($format, $parameters)) {
            return sha1(serialize($result));
        }

        return $this->keyGenerator->generateKey($interception, $annotation);
    }
}
