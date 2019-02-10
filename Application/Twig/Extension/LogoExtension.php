<?php

namespace Application\Twig\Extension;

/**
 * Class LogoExtension
 *
 * @package Application\Twig\Extension
 */
class LogoExtension extends \Twig\Extension\AbstractExtension
{
    /**
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction(
                'logo',
                function () {
                    $base64Encode = base64_encode(file_get_contents('docroot/img/maillogo.jpg'));

                    return '<img style="max-width: 100%; max-height: 100px;" src="data:image/gif;base64,' . $base64Encode . '">';
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
