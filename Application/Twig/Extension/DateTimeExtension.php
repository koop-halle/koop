<?php

namespace Application\Twig\Extension;

/**
 * Class DateTimeExtension
 *
 * @package Application\Twig\Extension
 */
class DateTimeExtension extends \Twig\Extension\AbstractExtension
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
