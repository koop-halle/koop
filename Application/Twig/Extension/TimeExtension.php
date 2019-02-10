<?php

namespace Application\Twig\Extension;

/**
 * Class TimeExtension
 *
 * @package Application\Twig\Extension
 */
class TimeExtension extends \Twig\Extension\AbstractExtension
{
    /**
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction(
                'datetime',
                function () {
                    return date('d.m.Y, H:i');
                }
            ),
        ];
    }
}
