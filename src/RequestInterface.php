<?php

namespace Parcel\Request;

/**
 * Class RequestInterface
 *
 * @version 1.0.0
 * @author Chris Butcher
 * @package Parcel\Request
 */
interface RequestInterface {

	/**
	 * Check to see whether an array key exists in any of the global request variables.
	 *
	 * @param string|integer $name   The array key that we are looking for.
	 * @param integer        $filter A specific list of global variables to look through.
	 *
	 * @return bool
	 */
	public function Has( $name, $filter );

	/**
	 * Searches through the global request variables for the value corresponding to the supplied array key name.
	 * If the supplied array key cannot be found in the request variables, then the default value will be returned.
	 *
	 * @param string|integer $name    The array key that we are looking for.
	 * @param mixed|null     $default The default value that is returned if a value for the specific name cannot be found.
	 * @param integer        $filter  A specific list of global variables to look through.
	 *
	 * @return bool|mixed|null
	 */
	public function Get( $name, $default, $filter );

	/**
	 * Returns the requests raw body string.
	 *
	 * @return string|null
	 */
	public function GetRequestBody();
}