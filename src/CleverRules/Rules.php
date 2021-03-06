<?php
namespace CleverRules;

use CleverRules\Interfaces as CRI;


/**
 * Rules Class
 *
 * @package CleverRules
 * @author Giuseppe Mazzapica
 */
class Rules implements CRI\Rules {


    public static $rules;


    public static $groups;


    public $query_vars;


    protected $url;


    protected $settings;


    protected $parser;


    protected $matcher;


    protected $found = array();


    protected $match;


    public function __construct( CRI\Url $u, CRI\Settings $s, CRI\Matcher $m, CRI\Parser $p ) {
        $this->url = $u;
        $this->settings = $s;
        $this->parser = $p;
        $this->matcher = $m;
    }


    public function found() {
        if ( empty( self::$rules ) ) return false;
        $this->paginate_rules();
        return $this->found_rules();
    }


    public function match() {
        $this->matcher->match( $this->found );
        $this->match = $this->matcher->match;
        return $this->match;
    }


    public function parse() {
        if ( empty( $this->found ) && empty( $this->match ) ) return false;
        $match = ( ! \is_null( $this->match ) ) ? $this->match : $this->match();
        if ( ! empty( $match ) ) {
            $this->parser->parse( $match, $this->matcher->replacements );
            $this->query_vars = $this->parser->qv;
            return ! empty( $this->query_vars );
        } else {
            return false;
        }
    }


    protected function paginate_rules() {
        foreach ( self::$rules as $rule ) {
            $rule->paginate();
        }
    }


    protected function found_rules() {
        if ( empty( self::$rules ) ) return false;
        foreach ( self::$rules as $rule ) {
            $home = $this->found_rule( $rule );
            if ( $home === true ) return true;
        }
        return $this->prioritize();
    }


    protected function found_rule( $rule ) {
        if ( $rule->is_home && empty( $this->url->parts ) && $rule->query ) {
            $this->match = $rule;
            return true;
        }
        if ( $rule->is_home ) return;
        $route = \explode( '/', \trim( $rule->route, '/' ) );
        if ( ( count( $route ) == count( $this->url->parts ) ) && $rule->query ) {
            $priority = $rule->priority ? $rule->priority : \count( $this->found );
            while ( isset( $this->found[$priority] ) )
                $priority ++;
            $this->found[$priority] = $rule;
        }
    }


    protected function prioritize() {
        if ( ! empty( $this->found ) ) {
            \ksort( $this->found );
            $this->found = \array_values( $this->found );
            return true;
        }
    }


}


