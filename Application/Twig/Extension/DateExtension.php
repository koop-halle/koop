<?php

namespace Application\Twig\Extension;

/**
 * Class DateExtension
 *
 * @package Application\Twig\Extension
 */
class DateExtension extends \Twig\Extension\AbstractExtension
{
    /**
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction(
                'date',
                function () {
                    return date('d.m.Y');
                }
            ),
        ];
    }
}
