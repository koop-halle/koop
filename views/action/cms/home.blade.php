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
/**
 * @var \Exception $exception
 * @var int        $code
 */
?>
@extends('layouts.app')

@section('title')
    <?= $translator->translate('hello, world!') ?>
@endsection

@section('content')
    foobar

@endsection
