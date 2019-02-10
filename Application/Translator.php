<?php

namespace Application;

/**
 * Class Translator
 *
 * @package Application
 */
class Translator
{
    /**
     * @var \DElfimov\Translate\Translate
     */
    protected $translator;


    /**
     * Translator constructor.
     */
    public function __construct()
    {
        $this->translator = new \DElfimov\Translate\Translate(
            new \DElfimov\Translate\Loader\PhpFilesLoader(ROOT_DIR . DIRECTORY_SEPARATOR . 'messages'),
            [
                "default"   => 'de',
                "available" => ['de'],
            ]
        );
    }


    /**
     * @param string $what
     *
     * @return string
     */
    public function translate($what)
    {
        return $this
            ->translator
            ->setLogger(\Application\Util\DependencyContainer::getInstance()->getLogger())
            ->t($what)
            ;
    }


    /**
     * @param string $what
     * @param int    $count
     *
     * @return string
     */
    public function plural($what, $count = 1)
    {
        return $this
            ->translator
            ->setLogger(\Application\Util\DependencyContainer::getInstance()->getLogger())
            ->plural($what, $count)
            ;
    }


    /**
     * @param string $language
     *
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->translator->setLanguage($language);

        return $this;
    }
}
