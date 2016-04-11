<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCache\KeyGenerator;

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
    public function generateKey(array $parameters, $format = '') : string
    {
        if ($format && $result = $this->language->evaluate($format, $parameters)) {
            $parameters = ['expression' => $result];
        }

        return $this->keyGenerator->generateKey($parameters, $format);
    }
}
