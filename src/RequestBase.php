<?php

namespace Parcel\Request;

use Parcel\ArrayHelper\ArrayHelper;

/**
 * Class RequestBase
 *
 * @version 1.0.0
 * @author Chris Butcher
 * @package Parcel\Request
 */
abstract class RequestBase implements RequestInterface {

	const FILTER_GET    = 1;
	const FILTER_POST   = 2;
	const FILTER_FILE   = 4;
	const FILTER_HEADER = 8;
	const FILTER_ALL    = 15;

	/**
	 * @var ArrayHelper
	 */
	protected $_Get;

	/**
	 * @var ArrayHelper
	 */
	protected $_Post;

	/**
	 * @var ArrayHelper
	 */
	protected $_Files;

	/**
	 * @var ArrayHelper
	 */
	protected $_Headers;

	/**
	 * @var string|null
	 */
	protected $_Input = null;

	/**
	 * RequestBase constructor.
	 */
	public function __construct() {
		$this->ReadInput();
		$this->ParseRequest();
	}

	/**
	 * @inheritdoc
	 */
	public function Has( $name, $filter = self::FILTER_ALL ) {

		if ( $filter & self::FILTER_GET ) {
			if ( $this->_Get->Has( $name ) ) {
				return true;
			}
		}

		if ( $filter & self::FILTER_POST ) {
			if ( $this->_Post->Has( $name ) ) {
				return true;
			}
		}

		if ( $filter & self::FILTER_FILE ) {
			if ( $this->_Files->Has( $name ) ) {
				return true;
			}
		}

		if ( $filter & self::FILTER_HEADER ) {
			if ( $this->_Headers->Has( $name ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @inheritdoc
	 */
	public function Get( $name, $default = null, $filter = self::FILTER_ALL ) {

		if ( $filter & self::FILTER_GET ) {
			if ( $this->_Get->Has( $name ) ) {
				return $this->_Get->Get( $name );
			}
		}

		if ( $filter & self::FILTER_POST ) {
			if ( $this->_Post->Has( $name ) ) {
				return $this->_Post->Get( $name );
			}
		}

		if ( $filter & self::FILTER_FILE ) {
			if ( $this->_Files->Has( $name ) ) {
				return $this->_Files->Get( $name );
			}
		}

		if ( $filter & self::FILTER_HEADER ) {
			if ( $this->_Headers->Has( $name ) ) {
				return $this->_Headers->Get( $name );
			}
		}

		return $default;
	}

	/**
	 * Loads and processes the request data so it can be easily accessed through the helper methods.
	 *
	 * @return bool
	 */
	protected function ParseRequest() {

		$this->_Get     = isset( $_GET ) ? new ArrayHelper( $_GET ) : new ArrayHelper();
		$this->_Post    = isset( $_POST ) ? new ArrayHelper( $_POST ) : new ArrayHelper();
		$this->_Files   = isset( $_FILES ) ? new ArrayHelper( $_FILES ) : new ArrayHelper();
		$this->_Headers = isset( $_SERVER ) ? new ArrayHelper( $_SERVER ) : new ArrayHelper();

		if ( is_string( $this->_Input ) && strlen( $this->_Input ) > 0 ) {

			$content_type = $this->_Headers->Get( 'Content-Type', 'text/html' );
			$content_type = strtolower( $content_type );

			/**
			 * When the requests content type is set to JSON, then we need to decode the raw request
			 * and then add it to the POST variables.
			 */
			if ( in_array( $content_type, array( 'application/json', 'text/json', 'json' ) ) ) {
				if ( ( $json = json_decode( $this->_Input, true ) ) !== null ) {
					foreach ( $json as $name => $value ) {
						$this->_Post->Set( $name, $value );
					}
				}
			}

			/**
			 * @Todo Add a way to decode XML from the request body to the POST variable.
			 */
		}

		return true;
	}

	/**
	 * Loads the requests body into a string.
	 *
	 * @return string|bool|null
	 *
	 * @throws \Exception
	 */
	protected function ReadInput() {

		if ( function_exists( 'file_get_contents' ) ) {
			if ( ( $this->_Input = file_get_contents( 'php://input' ) ) === false ) {
				throw new \Exception( "Could not read the request input using file_get_contents()" );
			}

		} else if ( function_exists( 'stream_get_contents' ) ) {
			if ( ( $this->_Input = stream_get_contents( STDIN ) ) === false ) {
				throw new \Exception( "Could not read the request input using stream_get_contents()" );
			}

		} else if ( function_exists( 'http_get_request_body' ) ) {
			if ( ( $this->_Input = http_get_request_body() ) === null ) {
				throw new \Exception( "Could not read the request input using http_get_request_body()" );
			}

		} else {
			throw new \Exception( "No method found to read the request body." );
		}

		return $this->_Input;
	}
}
