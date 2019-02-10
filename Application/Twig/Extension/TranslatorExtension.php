<?php

namespace Application\Twig\Extension;

/**
 * Class ConfigurationExtension
 *
 * @package Application\Twig\Extension
 */
class TranslatorExtension extends \Twig\Extension\AbstractExtension
{
    /**
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction(
                'translate',
                function ($what) {
                    $translation = \Application\Util\DependencyContainer::getInstance()->getTranslator()->translate($what);

                    return $translation;
                },
                [
                    #'needs_environment' => true,
                    #'needs_context'     => true,
                    #'is_variadic'       => false,
                    'is_safe'           => ['html'],
                    'is_safe_callback'  => true,
                    'deprecated'        => false,
                ]
            ),
        ];
    }
}
