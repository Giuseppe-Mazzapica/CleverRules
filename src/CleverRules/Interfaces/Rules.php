<?php
namespace CleverRules\Interfaces;


/**
 * Rules Interface
 *
 * @package CleverRules
 * @author Giuseppe Mazzapica
 */
interface Rules {


    /**
     * Contructor
     *
     * @param CleverRules\Interfaces\Url $url object implementing Url interface
     * @param CleverRules\Interfaces\Settings $s object implementing Settings interface
     * @param CleverRules\Interfaces\Matcher $m pbject implementing Matcher interface
     * @param CleverRules\Interfaces\Parser $p object implementing Parser interface
     */
    function __construct( Url $u, Settings $s, Matcher $m, Parser $p );


    /**
     * Loop through registered rules to find ones compatible with url.
     * If more rules are found, order them by priority.
     *
     * @return bool True if one or more rules are found, false otherwise
     * @access public
     */
    function found();


    /**
     * Loop through found rules to find one that match (if exists)
     *
     * @return null
     * @access public
     */
    function match();


    /**
     * When a rule match this method setup the query vars according to rule
     *
     * @return bool if a rule match and query vars are set up, false otherwise
     * @access public
     */
    function parse();


}


