<?php
App::uses('AppHelper', 'View/Helper');

class FrontHelper extends AppHelper {

	public $helpers = array('Html');

	static function sanitizeStringForURL($str) {
		$sanitized = mb_ereg_replace("۱","1",mb_ereg_replace("۲","2",mb_ereg_replace("۳","3",mb_ereg_replace("۴","4",mb_ereg_replace("۵","5",mb_ereg_replace("۶","6",mb_ereg_replace("۷","7",mb_ereg_replace("۸","8",mb_ereg_replace("۹","9",mb_ereg_replace("۰","0",
            mb_ereg_replace("(^-)|(-$)","",
                mb_ereg_replace("(?<=-)(ها|و|های|ی|ي|هاي|هایی|هايي|به|طی|طي|یک|یکی|یکي|يکی|يکي|خود|با|را|در|که|است|روی|برای|براي|بیش||تا|باید|ای)(-|$)", "",
                    mb_ereg_replace("[-._\\/\\s#'\"&+`)(«‌»؛><:،,!?؟]+", "-",
                        mb_ereg_replace("َ|ِ|ُ|ّ|ٌ|ً|ـ", "",
                            trim($str)))))))))))))));
		return mb_convert_encoding($sanitized, "UTF-8");
	}

	static function truncText($text, $size = 130) {
		if(mb_strlen($text) > $size)
			return mbereg_replace("(.{,".$size."})($|\\s.*)", "\\1", $text) . '...';
		return $text;
	}

	static function farsifyText($text) {
		return !empty($text) ? mberegi_replace("([0-9])",'&#x6f\\1;', $text) : '';
	}

	static function groupDigitsBy3($text) {
		return strrev(mberegi_replace("((?!.*\\..*)[0-9]{3})(?=[0-9])", "\\1,", strrev($text)));
	}

	static function dateStringToTime($text) {
		$array = mb_split(' ', $text);
		return array_pop($array);
	}

	static function datetimeStringToDate($text, $grtime = null) {
		$months = array(
			"فروردین",
			"اردیبهشت",
			"خرداد",
			"تیر",
			"مرداد",
			"شهریور",
			"مهر",
			"آبان",
			"آذر",
			"دی",
			"بهمن",
			"اسفند"
		);
		$daysOfWeek = array(
			"یکشنبه",
			"دوشنبه",
			"سه شنبه",
			"چهارشنبه",
			"پنجشنبه",
			"جمعه",
			"شنبه"
		);
		$day = '';
		$time = strtotime($grtime);
		$array = mb_split(' ', $text);
		$date = mb_split("/", array_shift($array));
		$text1 = ($date[2] + 0) . ' ' . $months[$date[1] - 1] . ' ' . $date[0];
		return $daysOfWeek[date('w',$time)] . ' ' . FrontHelper::farsifyText($text1);
	}
    public static function outputAd($ad, $webroot) {
        App::uses ( 'Advertising', 'Model' );
        if($ad['Advertising']['fileType'] == Advertising::TYPE_IMAGE) {
            echo '<a target="_blank" href="'.$ad['Advertising']['link'].'"><img src="'.$webroot.'sponsors/'.$ad['Advertising']['fileName'].'" alt="'.$ad['Advertising']['description'].'" /></a>';
        } else if($ad['Advertising']['fileType'] == Advertising::TYPE_SWF) {
            echo '<object data="'.$webroot.'sponsors/'.$ad['Advertising']['fileName'].'" type="application/x-shockwave-flash" height="90" width="700">
                <param name="movie" value="'.$webroot.'sponsors/'.$ad['Advertising']['fileName'].'" />
                <param name="menu" value="false" />
            </object>';
        }
    }
    public function link($handler, $id, $title, $page_subdomain = null) {
        $CLEAR_ID = Configure::read('CLEAR_ID');
        $page = is_numeric($page_subdomain) ? $page_subdomain : null;
        $subdomain = !is_numeric($page_subdomain) ? $page_subdomain : null;
        $sanitizedTitle = FrontHelper::sanitizeStringForURL($title);
        if($CLEAR_ID !== true) {
            $crc32 = hexdec(hash("crc32b", $sanitizedTitle));
            $handle = base_convert($crc32 + $id, 10, 36);
        } else {
            $handle = $id;
        }
        return (isset($subdomain) ? "//$subdomain.emruzonline.com" : '') . $this->webroot . $handler . '/' . $handle . '/' . urlencode($sanitizedTitle) . (isset($page) ? '/page' . $page : '') . '.htm';
    }
    public static function slink($webroot, $handler, $id, $title, $page_subdomain = null) {
        $CLEAR_ID = Configure::read('CLEAR_ID');
        $page = is_numeric($page_subdomain) ? $page_subdomain : null;
        $subdomain = !is_numeric($page_subdomain) ? $page_subdomain : null;
        $sanitizedTitle = FrontHelper::sanitizeStringForURL($title);
        if($CLEAR_ID !== true) {
            $crc32 = hexdec(hash("crc32b", $sanitizedTitle));
            $handle = base_convert($crc32 + $id, 10, 36);
        } else {
            $handle = $id;
        }
        return (isset($subdomain) ? "//$subdomain.emruzonline.com" : '') . $webroot . $handler . '/' . $handle . '/' . urlencode($sanitizedTitle) . (isset($page) ? '/page' . $page : '') . '.htm';
    }

    public static function lazifyImages($text) {
        return mb_eregi_replace("(<img[^>]*)\\ssrc=['\"]([^'\"]+)['\"]([^>]*?)/?>", '\\1 class="b-lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="\\2"\\3/>', $text);
    }

    public static function lazifyImagesNotFirst($text) {
        $pos = mb_strpos($text, '<img') + 4;
        $x = mb_substr($text, 0, $pos);
        $y = mb_substr($text, $pos);
        return $x . FrontHelper::lazifyImages($y);
    }

    public static function itemProp($tag, $propName, $text) {
        return mb_eregi_replace('(<'.$tag.'[^>]*)\\s([^>]*?)/?>', '\\1 itemprop="'.$propName.'" \\2/>', $text);
    }

    public static function nvl($value, $default) {
        return isset($value) && !empty($value) ? $value : $default;
    }
}
