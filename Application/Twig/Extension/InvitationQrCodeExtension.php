<?php

namespace Application\Twig\Extension;

/**
 * Class InvitationQrCodeExtension
 *
 * @package Application\Twig\Extension
 */
class InvitationQrCodeExtension extends \Twig\Extension\AbstractExtension
{
    /**
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction(
                'invitationQrCode',
                function (\Twig\Environment $environment, array $foo, \Application\Doctrine\Model\Player $player) {

                    $qrCode = new \Endroid\QrCode\QrCode();

                    $text   = \Application\Util\DependencyContainer::getInstance()->getConfiguration()->getApplicationUrl().\Application\Router::buildPlayerApplyInvitation().'?token=' . $player->getInvitationToken() . '&email=' . $player->getEmail();
                    $qrCode->setText($text);
                    $qrCode->setSize(300);
                    $qrCode->setForegroundColor(['r' => 100, 'g' => 100, 'b' => 100, 'a' => 0.8]);
                    $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0.9]);
                    $qrCode->setLogoPath('docroot/img/logo.jpg');
                    $qrCode->setLogoWidth(50);
                    $qrCode->setRoundBlockSize(true);;
                    $base64Encode = base64_encode($qrCode->writeString());

                    return '<img style="max-width: 100%; max-height: 300px;" src="data:image/gif;base64,' . $base64Encode . '">';
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
