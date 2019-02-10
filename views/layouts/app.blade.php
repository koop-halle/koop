<?
/**
 * @var \Application\Action\AbstractAction        $application
 * @var \Application\Doctrine\Model\Player | null $currentUser
 * @var \IntlDateFormatter                        $dateFormatter
 * @var \IntlDateFormatter                        $dateTimeFormatter
 * @var \Plasticbrain\FlashMessages\FlashMessages $flashMessenger
 * @var \AdamWathan\Form\FormBuilder              $formBuilder
 * @var bool                                      $isAjax
 * @var \IntlDateFormatter                        $timeFormatter
 * @var \Application\Translator                   $translator
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docroot/favicon.ico?t=_<?= time() ?>">
    <title>@yield('title') - <?= \Application\Util\DependencyContainer::getInstance()->getConfiguration()->getApplicationName() ?></title>
</head>


<body>
<div>
    asdf
</div>
@yield('content')
</body>
</html>