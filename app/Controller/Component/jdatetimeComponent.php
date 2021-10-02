<?php
class jdatetimeComponent extends Object {
	var $jdate;
	function __construct() {
		date_default_timezone_set ( 'Asia/Tehran' );
		App::import ( 'Vendor', 'jdatetime/jDateTime' );
		$this->jdate = new jDateTime ( true, true, 'Asia/Tehran' );
	}
	function shamsi($time, $format) {
		return $this->jdate->date ( $format, $time, false );
	}
	function miladi(&$date, $format) {
		if (empty ( $date )) {
			$date = $this->shamsi ( strtotime ( date ( $format ) ), $format );
			return date ( $format );
		}
		$errFormat = array (
				'date' => array (
						'فرمت تاریخ اشتباه می باشد' 
				) 
		);
		if (strlen ( $date ) != 16 && strlen ( $date ) != 19) {
			AppController::applicationException ( $errFormat );
		}
		try {
			if (strlen ( $date ) == 16) {
				$date .= ':00';
			}
			$hour = intval ( substr ( $date, 11, 2 ) );
			$minute = intval ( substr ( $date, 14, 2 ) );
			$second = intval ( substr ( $date, 17, 2 ) );
			;
			$month = intval ( substr ( $date, 5, 2 ) );
			$day = intval ( substr ( $date, 8, 2 ) );
			$year = intval ( substr ( $date, 0, 4 ) );
			$timestamp = $this->jdate->mktime ( $hour, $minute, $second, $month, $day, $year );
			return $this->jdate->date ( $format, $timestamp, false, false );
		} catch ( Exception $e ) {
			AppController::applicationException ( $errFormat );
		}
	}
	function beforeRedirect() {
	}
	function getEnclosure() {
	}
	function initialize() {
	}
	function startup() {
	}
	function shutdown() {
	}
	function beforeRender() {
	}
}
