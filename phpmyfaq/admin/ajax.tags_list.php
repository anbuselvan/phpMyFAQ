<?php
/**
 * AJAX: Search for tags
 *
 * PHP Version 5.3
 *
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 *
 * @category  phpMyFAQ
 * @package   Ajax
 * @author    Matteo Scaramuccia <matteo@scaramuccia.com>
 * @copyright 2005-2012 phpMyFAQ Team
 * @license   http://www.mozilla.org/MPL/2.0/ Mozilla Public License Version 2.0
 * @link      http://www.phpmyfaq.de
 * @since     2005-12-15
 */

if (!defined('IS_VALID_PHPMYFAQ')) {
    header('Location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']));
    exit();
}

// Send headers
$http = new PMF_Helper_Http();
$http->setContentType('application/json');
$http->addHeader();

header("Expires: Thu, 7 Apr 1977 14:47:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Vary: Negotiate,Accept");

$oTag              = new PMF_Tags($faqConfig);
$autoCompleteValue = PMF_Filter::filterInput(INPUT_GET, 'q', FILTER_SANITIZE_STRIPPED);
if (! is_null($autoCompleteValue)) {
    if (strpos($autoCompleteValue, ',')) {
        $arrayOfValues     = explode(',', $autoCompleteValue);
        $autoCompleteValue = end($arrayOfValues);
    }
    $tags = $oTag->getAllTags($autoCompleteValue, false, true);
} else {
    $tags = $oTag->getAllTags();
}

if ($permission['editbt']) {
    $i = 0;
    $tagNames = array();
    foreach ($tags as $tagName) {
        $i++;
        if ($i <= PMF_TAGS_AUTOCOMPLETE_RESULT_SET_SIZE) {
            $tagNames[] = $tagName;
        }
    }

    echo json_encode(array('tags' => $tagNames));
}
