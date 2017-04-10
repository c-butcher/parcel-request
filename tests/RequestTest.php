<?php

namespace Parcel\Request\Tests;

use PHPUnit\Framework\TestCase;
use Parcel\Request\Request;

class RequestTest extends TestCase
{
	public function testGetVariable() {
		$_GET['variable'] = 'get';

		$request = new Request();

		$this->assertEquals('get', $request->Get('variable', null, Request::FILTER_ALL));
		$this->assertEquals('get', $request->Get('variable', null, Request::FILTER_GET));

		$this->assertEquals(null,  $request->Get('variable', null, Request::FILTER_POST));
		$this->assertEquals(null,  $request->Get('variable', null, Request::FILTER_FILE));
		$this->assertEquals(null,  $request->Get('variable', null, Request::FILTER_HEADER));
	}

	public function testPostVariable() {
		$_POST['variable'] = 'post';

		$request = new Request();

		$this->assertEquals('post', $request->Get('variable', null, Request::FILTER_ALL));
		$this->assertEquals('post', $request->Get('variable', null, Request::FILTER_POST));

		$this->assertEquals(null,   $request->Get('variable', null, Request::FILTER_GET));
		$this->assertEquals(null,   $request->Get('variable', null, Request::FILTER_FILE));
		$this->assertEquals(null,   $request->Get('variable', null, Request::FILTER_HEADER));
	}

	public function testFilesVariable() {
		$_FILES['variable'] = array(
			'name' => 'file.txt',
			'type' => 'text/plain',
			'temp_name' => 'tmp/php/path',
		);

		$request = new Request();

		$this->assertArrayHasKey('name', $request->Get('variable', null, Request::FILTER_ALL));
		$this->assertArrayHasKey('type', $request->Get('variable', null, Request::FILTER_FILE));

		$this->assertEquals(null, $request->Get('variable', null, Request::FILTER_GET));
		$this->assertEquals(null, $request->Get('variable', null, Request::FILTER_POST));
		$this->assertEquals(null, $request->Get('variable', null, Request::FILTER_HEADER));
	}

	public function testServerVariable() {
		$_SERVER['variable'] = 'server';

		$request = new Request();

		$this->assertEquals('server', $request->Get('variable', null, Request::FILTER_ALL));
		$this->assertEquals('server', $request->Get('variable', null, Request::FILTER_HEADER));

		$this->assertEquals(null, $request->Get('variable', null, Request::FILTER_GET));
		$this->assertEquals(null, $request->Get('variable', null, Request::FILTER_POST));
		$this->assertEquals(null, $request->Get('variable', null, Request::FILTER_FILE));
	}

	public function testDuplicateVariables() {
		$_GET['duplicate']    = 'get';
		$_POST['duplicate']   = 'post';
		$_FILES['duplicate']  = 'file';
		$_SERVER['duplicate'] = 'server';

		$request = new Request();

		$this->assertEquals('get',    $request->Get('duplicate', null, Request::FILTER_GET));
		$this->assertEquals('post',   $request->Get('duplicate', null, Request::FILTER_POST));
		$this->assertEquals('file',   $request->Get('duplicate', null, Request::FILTER_FILE));
		$this->assertEquals('server', $request->Get('duplicate', null, Request::FILTER_HEADER));
	}
}
