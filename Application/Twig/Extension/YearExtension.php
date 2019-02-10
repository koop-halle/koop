<?php

namespace Application\Twig\Extension;

/**
 * Class YearExtension
 *
 * @package Application\Twig\Extension
 */
class YearExtension extends \Twig\Extension\AbstractExtension
{
    /**
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction(
                'year',
                function () {
                    return date('Y');
                }
            ),
        ];
    }
}
