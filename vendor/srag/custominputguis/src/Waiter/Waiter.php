<?php

namespace srag\CustomInputGUIs\OneDrive\Waiter;

use srag\DIC\OneDrive\DICTrait;
use ilGlobalPageTemplate;

/**
 * Class Waiter
 *
 * @package srag\CustomInputGUIs\OneDrive\Waiter
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Waiter
{

    use DICTrait;

    /**
     * @var string
     */
    const TYPE_PERCENTAGE = "percentage";
    /**
     * @var string
     */
    const TYPE_WAITER = "waiter";
    /**
     * @var bool
     */
    protected static $init = false;


    /**
     * Waiter constructor
     */
    private function __construct()
    {

    }


    /**
     * @param string          $type
     * @param ilGlobalPageTemplate|ilTemplate|null $tpl
     */
    public static final function init(string $type, /*?ilGlobalPageTemplate*/ $tpl = null)/*: void*/
    {
        $tpl = $tpl ?? self::dic()->ui()->mainTemplate();

        if (self::$init === false) {
            self::$init = true;

            $dir = __DIR__;
            $dir = "./" . substr($dir, strpos($dir, "/Customizing/") + 1);

            $tpl->addCss($dir . "/css/waiter.css");

            $tpl->addJavaScript($dir . "/js/waiter.min.js?v=1");
        }

        $tpl->addOnLoadCode('il.waiter.init("' . $type . '");');
    }
}
