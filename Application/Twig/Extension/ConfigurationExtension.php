<?php

namespace Application\Twig\Extension;

/**
 * Class ConfigurationExtension
 *
 * @package Application\Twig\Extension
 */
class ConfigurationExtension extends \Twig\Extension\AbstractExtension
{
    /**
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction(
                'conf',
                function () {
                    return \Application\Util\DependencyContainer::getInstance()->getConfiguration();
                },
                [
                    'needs_environment' => true,
                    'needs_context'     => true,
                    'is_variadic'       => false,
                    'is_safe'           => ['html'],
                    'is_safe_callback'  => true,
                    'deprecated'        => false,
                ]
            ),
        ];
    }
}
