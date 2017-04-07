<?php

namespace Parcel\Request;

/**
 * Class Request
 *
 * @version 1.0.0
 * @author Chris Butcher
 * @package Parcel\Request
 */
class Request extends RequestBase {

	/**
	 * Singleton instance of this class.
	 *
	 * @var self
	 */
	protected static $_Instance = null;

	/**
	 * Request constructor.
	 */
	public function __construct() {
		parent::__construct();

		if ( self::$_Instance === null ) {
			self::$_Instance = $this;

		} else {
			/**
			 * @todo We should populate this new instance with data from the existing instance.
			 */
		}
	}

	/**
	 * Returns the singleton instance of this class.
	 *
	 * @return Request
	 */
	public static function &GetInstance()
	{
		if ( self::$_Instance === null  || ! is_object( self::$_Instance ) ) {
			self::$_Instance = new self();
		}

		return self::$_Instance;
	}
}