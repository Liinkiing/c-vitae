<?php
/**
 * Created by PhpStorm.
 * User: Linking
 * Date: 17/02/2016
 * Time: 02:00
 */

namespace AppBundle\Twig;


use AppBundle\AppBundle;
use League\HTMLToMarkdown\HtmlConverter;
use NumberFormatter;
use phpbrowscap\Browscap;

class AppExtension extends \Twig_Extension
{


    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'GenerateColor';
    }


    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('generateColor', function(){
                return "#" . str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT) . str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT) . str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
            })
        ,   new \Twig_SimpleFunction('convertHtmlToMarkdown', function($html){
                $parser = new HtmlConverter();
                return $parser->convert($html);
            })
        ,   new \Twig_SimpleFunction('convertMarkdownToHtml', function($markdown){
                $parser = new \Parsedown();
                return $parser->parse($markdown);
            }));
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('str_replace', function($string, $search, $replace){
                return str_replace($search, $replace, $string);
            }),
            new \Twig_SimpleFilter('strpos', function($source, $chars){
                return strpos($source, $chars) === 0;
            })
        );
    }

}




