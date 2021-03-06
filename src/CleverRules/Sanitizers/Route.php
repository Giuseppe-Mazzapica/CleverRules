<?php
namespace CleverRules\Sanitizers;

use CleverRules\Interfaces as CRI;

/**
 * Route Class
 * Used to sanitize the route rule argument
 *
 * @package CleverRules
 * @author Giuseppe Mazzapica
 */
class Route implements CRI\TypeSanitizer {


    public function sanitize( $value ) {
        if ( $value === '/' ) return $value;
        if ( ! \is_string( $value ) || ! \substr_count( $value, '/' ) ) return null;
        $clean = \trim( $value, '\\/ ' );
        $pieces = \explode( '/', $clean );
        $check = \array_filter( \array_map( array($this, 'sanitize_piece'), $pieces ) );
        return \count( $pieces ) == \count( $check ) ? $clean : null;
    }


    function sanitize_piece( $p ) {
        $val = \preg_replace( '/\{[0-9]\}/', '', \str_replace( array('%d', '%s'), '', $p ) );
        return ( \preg_match( '/^[a-z0-9\-_]*$/i', $val ) === 1 ) ? $p : null;
    }


}