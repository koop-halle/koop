<?php

namespace Application\Twig\Extension;

/**
 * Class LinkBuilder
 *
 * @package Application\Twig\Extension
 */
class LinkBuilderExtension extends \Twig\Extension\AbstractExtension
{
    /**
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction(
                'link',
                function ($env, $foo2, $foo, ...$args) {

                    return \Application\Util\DependencyContainer::getInstance()->getConfiguration()->getApplicationUrl().
                        call_user_func_array([\Application\Router::class, $foo], $args);
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
