<?php











namespace Composer;

use Composer\Autoload\ClassLoader;
use Composer\Semver\VersionParser;






class InstalledVersions
{
private static $installed = array (
  'root' => 
  array (
    'pretty_version' => 'dev-dev',
    'version' => 'dev-dev',
    'aliases' => 
    array (
    ),
    'reference' => '89a59eabcd320df71cef4912fc679aa435bf821c',
    'name' => 'laswitchtech/coredb',
  ),
  'versions' => 
  array (
    'components/jquery' => 
    array (
      'pretty_version' => '3.6.0',
      'version' => '3.6.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '6cf38ee1fd04b6adf8e7dda161283aa35be818c3',
    ),
    'composer/ca-bundle' => 
    array (
      'pretty_version' => '1.3.4',
      'version' => '1.3.4.0',
      'aliases' => 
      array (
      ),
      'reference' => '69098eca243998b53eed7a48d82dedd28b447cd5',
    ),
    'composer/class-map-generator' => 
    array (
      'pretty_version' => '1.0.0',
      'version' => '1.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '1e1cb2b791facb2dfe32932a7718cf2571187513',
    ),
    'composer/composer' => 
    array (
      'pretty_version' => '2.4.4',
      'version' => '2.4.4.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e8d9087229bcdbc5867594d3098091412f1130cf',
    ),
    'composer/metadata-minifier' => 
    array (
      'pretty_version' => '1.0.0',
      'version' => '1.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c549d23829536f0d0e984aaabbf02af91f443207',
    ),
    'composer/pcre' => 
    array (
      'pretty_version' => '3.1.0',
      'version' => '3.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '4bff79ddd77851fe3cdd11616ed3f92841ba5bd2',
    ),
    'composer/semver' => 
    array (
      'pretty_version' => '3.3.2',
      'version' => '3.3.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '3953f23262f2bff1919fc82183ad9acb13ff62c9',
    ),
    'composer/spdx-licenses' => 
    array (
      'pretty_version' => '1.5.7',
      'version' => '1.5.7.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c848241796da2abf65837d51dce1fae55a960149',
    ),
    'composer/xdebug-handler' => 
    array (
      'pretty_version' => '3.0.3',
      'version' => '3.0.3.0',
      'aliases' => 
      array (
      ),
      'reference' => 'ced299686f41dce890debac69273b47ffe98a40c',
    ),
    'datatables/datatables' => 
    array (
      'pretty_version' => '1.10.21',
      'version' => '1.10.21.0',
      'aliases' => 
      array (
      ),
      'reference' => '83e59694a105225ff889ddfa0d723a3ab24fda78',
    ),
    'justinrainbow/json-schema' => 
    array (
      'pretty_version' => '5.2.12',
      'version' => '5.2.12.0',
      'aliases' => 
      array (
      ),
      'reference' => 'ad87d5a5ca981228e0e205c2bc7dfb8e24559b60',
    ),
    'laswitchtech/bootstrap-panel' => 
    array (
      'pretty_version' => 'v1.0.8',
      'version' => '1.0.8.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f50cd6bc0d4805d1fc2d905668eae3a7385c47dc',
    ),
    'laswitchtech/coredb' => 
    array (
      'pretty_version' => 'dev-dev',
      'version' => 'dev-dev',
      'aliases' => 
      array (
      ),
      'reference' => '89a59eabcd320df71cef4912fc679aa435bf821c',
    ),
    'laswitchtech/php-api' => 
    array (
      'pretty_version' => 'v1.4.16',
      'version' => '1.4.16.0',
      'aliases' => 
      array (
      ),
      'reference' => '7b293ac3d92a56d01c3a96a44b01d2465c71939e',
    ),
    'laswitchtech/php-auth' => 
    array (
      'pretty_version' => 'v1.5.23',
      'version' => '1.5.23.0',
      'aliases' => 
      array (
      ),
      'reference' => '92cf5da54ca33d953dc7f4439912ba186c9a9521',
    ),
    'laswitchtech/php-database' => 
    array (
      'pretty_version' => 'v2.1.5',
      'version' => '2.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '926c61b339a911f8214bb21eae6c516aac2ef86c',
    ),
    'laswitchtech/php-router' => 
    array (
      'pretty_version' => 'v1.3.8',
      'version' => '1.3.8.0',
      'aliases' => 
      array (
      ),
      'reference' => '0df65a76851d69804b1977c29c247aba13ec640c',
    ),
    'laswitchtech/php-smtp' => 
    array (
      'pretty_version' => 'v1.0.13',
      'version' => '1.0.13.0',
      'aliases' => 
      array (
      ),
      'reference' => '49fd8bf935a6605c7b3281cbcebba4f8dd946525',
    ),
    'phpmailer/phpmailer' => 
    array (
      'pretty_version' => 'v6.7.1',
      'version' => '6.7.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '49cd7ea3d2563f028d7811f06864a53b1f15ff55',
    ),
    'psr/container' => 
    array (
      'pretty_version' => '2.0.2',
      'version' => '2.0.2.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c71ecc56dfe541dbd90c5360474fbc405f8d5963',
    ),
    'psr/log' => 
    array (
      'pretty_version' => '3.0.0',
      'version' => '3.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'fe5ea303b0887d5caefd3d431c3e61ad47037001',
    ),
    'psr/log-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0|2.0|3.0',
      ),
    ),
    'react/promise' => 
    array (
      'pretty_version' => 'v2.9.0',
      'version' => '2.9.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '234f8fd1023c9158e2314fa9d7d0e6a83db42910',
    ),
    'rmm5t/jquery-timeago' => 
    array (
      'pretty_version' => 'v1.6.7',
      'version' => '1.6.7.0',
      'aliases' => 
      array (
      ),
      'reference' => '48fdda3ca724dcd655e8e990f6d7fbd203718905',
    ),
    'seld/jsonlint' => 
    array (
      'pretty_version' => '1.9.0',
      'version' => '1.9.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '4211420d25eba80712bff236a98960ef68b866b7',
    ),
    'seld/phar-utils' => 
    array (
      'pretty_version' => '1.2.1',
      'version' => '1.2.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'ea2f4014f163c1be4c601b9b7bd6af81ba8d701c',
    ),
    'seld/signal-handler' => 
    array (
      'pretty_version' => '2.0.1',
      'version' => '2.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f69d119511dc0360440cdbdaa71829c149b7be75',
    ),
    'symfony/console' => 
    array (
      'pretty_version' => 'v6.0.16',
      'version' => '6.0.16.0',
      'aliases' => 
      array (
      ),
      'reference' => 'be294423f337dda97c810733138c0caec1bb0575',
    ),
    'symfony/filesystem' => 
    array (
      'pretty_version' => 'v6.0.13',
      'version' => '6.0.13.0',
      'aliases' => 
      array (
      ),
      'reference' => '3adca49133bd055ebe6011ed1e012be3c908af79',
    ),
    'symfony/finder' => 
    array (
      'pretty_version' => 'v6.0.11',
      'version' => '6.0.11.0',
      'aliases' => 
      array (
      ),
      'reference' => '09cb683ba5720385ea6966e5e06be2a34f2568b1',
    ),
    'symfony/polyfill-ctype' => 
    array (
      'pretty_version' => 'v1.27.0',
      'version' => '1.27.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '5bbc823adecdae860bb64756d639ecfec17b050a',
    ),
    'symfony/polyfill-intl-grapheme' => 
    array (
      'pretty_version' => 'v1.27.0',
      'version' => '1.27.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '511a08c03c1960e08a883f4cffcacd219b758354',
    ),
    'symfony/polyfill-intl-normalizer' => 
    array (
      'pretty_version' => 'v1.27.0',
      'version' => '1.27.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '19bd1e4fcd5b91116f14d8533c57831ed00571b6',
    ),
    'symfony/polyfill-mbstring' => 
    array (
      'pretty_version' => 'v1.27.0',
      'version' => '1.27.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8ad114f6b39e2c98a8b0e3bd907732c207c2b534',
    ),
    'symfony/polyfill-php73' => 
    array (
      'pretty_version' => 'v1.27.0',
      'version' => '1.27.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '9e8ecb5f92152187c4799efd3c96b78ccab18ff9',
    ),
    'symfony/polyfill-php80' => 
    array (
      'pretty_version' => 'v1.27.0',
      'version' => '1.27.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '7a6ff3f1959bb01aefccb463a0f2cd3d3d2fd936',
    ),
    'symfony/process' => 
    array (
      'pretty_version' => 'v6.0.11',
      'version' => '6.0.11.0',
      'aliases' => 
      array (
      ),
      'reference' => '44270a08ccb664143dede554ff1c00aaa2247a43',
    ),
    'symfony/service-contracts' => 
    array (
      'pretty_version' => 'v3.0.2',
      'version' => '3.0.2.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd78d39c1599bd1188b8e26bb341da52c3c6d8a66',
    ),
    'symfony/string' => 
    array (
      'pretty_version' => 'v6.0.15',
      'version' => '6.0.15.0',
      'aliases' => 
      array (
      ),
      'reference' => '51ac0fa0ccf132a00519b87c97e8f775fa14e771',
    ),
    'twbs/bootstrap' => 
    array (
      'pretty_version' => 'v5.2.2',
      'version' => '5.2.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '961d5ff9844372a4e294980c667bbe7e0651cdeb',
    ),
    'twbs/bootstrap-icons' => 
    array (
      'pretty_version' => 'v1.10.2',
      'version' => '1.10.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '4deaf1f69ccbe0a996c08a308525a26b05467242',
    ),
    'twitter/bootstrap' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.2.2',
      ),
    ),
  ),
);
private static $canGetVendors;
private static $installedByVendor = array();







public static function getInstalledPackages()
{
$packages = array();
foreach (self::getInstalled() as $installed) {
$packages[] = array_keys($installed['versions']);
}


if (1 === \count($packages)) {
return $packages[0];
}

return array_keys(array_flip(\call_user_func_array('array_merge', $packages)));
}









public static function isInstalled($packageName)
{
foreach (self::getInstalled() as $installed) {
if (isset($installed['versions'][$packageName])) {
return true;
}
}

return false;
}














public static function satisfies(VersionParser $parser, $packageName, $constraint)
{
$constraint = $parser->parseConstraints($constraint);
$provided = $parser->parseConstraints(self::getVersionRanges($packageName));

return $provided->matches($constraint);
}










public static function getVersionRanges($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

$ranges = array();
if (isset($installed['versions'][$packageName]['pretty_version'])) {
$ranges[] = $installed['versions'][$packageName]['pretty_version'];
}
if (array_key_exists('aliases', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['aliases']);
}
if (array_key_exists('replaced', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['replaced']);
}
if (array_key_exists('provided', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['provided']);
}

return implode(' || ', $ranges);
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getVersion($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['version'])) {
return null;
}

return $installed['versions'][$packageName]['version'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getPrettyVersion($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['pretty_version'])) {
return null;
}

return $installed['versions'][$packageName]['pretty_version'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getReference($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['reference'])) {
return null;
}

return $installed['versions'][$packageName]['reference'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getRootPackage()
{
$installed = self::getInstalled();

return $installed[0]['root'];
}







public static function getRawData()
{
return self::$installed;
}



















public static function reload($data)
{
self::$installed = $data;
self::$installedByVendor = array();
}




private static function getInstalled()
{
if (null === self::$canGetVendors) {
self::$canGetVendors = method_exists('Composer\Autoload\ClassLoader', 'getRegisteredLoaders');
}

$installed = array();

if (self::$canGetVendors) {

 foreach (ClassLoader::getRegisteredLoaders() as $vendorDir => $loader) {
if (isset(self::$installedByVendor[$vendorDir])) {
$installed[] = self::$installedByVendor[$vendorDir];
} elseif (is_file($vendorDir.'/composer/installed.php')) {
$installed[] = self::$installedByVendor[$vendorDir] = require $vendorDir.'/composer/installed.php';
}
}
}

$installed[] = self::$installed;

return $installed;
}
}
