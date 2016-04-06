<?php

namespace Phpro\AnnotatedCache\KeyGenerator;

use Phpro\AnnotatedCache\Exception\UnsupportedKeyParameterException;
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
     * @param array  $parameters
     *
     * @param string $format
     *
     * @return string
     */
    public function generateKey(array $parameters, $format = '')
    {
        if ($format && $result = $this->language->evaluate($format, $parameters)) {
            $parameters = ['expression' => $result];
        }

        return $this->keyGenerator->generateKey($parameters, $format);
    }
}
