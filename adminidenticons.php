<?php
namespace Grav\Plugin;

use Grav\Common\Data;
use Grav\Common\Plugin;
use Grav\Common\Grav;
use Grav\Common\Uri;
use Grav\Common\Taxonomy;
use Grav\Common\Page\Page;
use RocketTheme\Toolbox\Event\Event;
require __DIR__ . '/vendor/autoload.php';
use Hedronium\Avity\Avity;

class AdminIdenticonsPlugin extends Plugin
{
	public static function getSubscribedEvents() {
		return [
			'onPageContentProcessed' => ['onPageContentProcessed', 0],
			'onAdminTwigTemplatePaths' => ['onAdminTwigTemplatePaths', 0],
			'onAssetsInitialized' => ['onAssetsInitialized', 0]
		];
	}
    public function onAssetsInitialized() {
		$pluginsobject = (array) $this->config->get('plugins');
		$pluginsobject = $pluginsobject['adminidenticons'];
		if (isset($pluginsobject['border_radius'])) {
			if ($pluginsobject['border_radius'] > 0) {
				$border_radius = $pluginsobject['border_radius'];
			} else {
				$border_radius = 0;
			}
		}
		$this->grav['assets']->addInlineCss('.user-details img, #admin-user-details img, .admin-user-details img {border-radius: '.$border_radius.'%;}');
    }
	public function onPageContentProcessed(Event $event) {
		if ($this->isAdmin()) {
			$pluginsobject = (array) $this->config->get('plugins');
			$pluginsobject = $pluginsobject['adminidenticons'];
			if (isset($pluginsobject) && $pluginsobject['enabled']) {
				if ($pluginsobject['type'] == 'identicon') {
					$hash = $this->grav['user']->fullname;
					ob_start();
					$avity = Avity::init([
						'generator' => \Hedronium\Avity\Generators\Hash::class
					])
					->height(128)
					->width(128);
					$avity->hash($hash);
					
					if ($pluginsobject['padding']) {
						$avity->padding($pluginsobject['padding']);
					} else {
						$avity->padding(28);
					}
					if ($pluginsobject['rows']) {
						$avity->rows($pluginsobject['rows']);
					}
					if ($pluginsobject['columns']) {
						$avity->columns($pluginsobject['columns']);
					}
					if ($pluginsobject['varied']) {
						$avity->style()
						->variedColor();
					}
					if ($pluginsobject['background']) {
						$background = $this->hex2RGB($pluginsobject['background']);
						$avity->style()
						->background($background['red'], $background['green'], $background['blue']);
					}
					if ($pluginsobject['foreground']) {
						$foreground = $this->hex2RGB($pluginsobject['foreground']);
						$avity->style()
						->foreground($foreground['red'], $foreground['green'], $foreground['blue']);
					}
					if ($pluginsobject['spacing']) {
						$avity->style()
						->spacing($pluginsobject['spacing']);
					}
					
					$avity->generate()
					->png()
					->quality(100)
					->toBrowser();
					$b64 = base64_encode(ob_get_contents());
					ob_end_clean();
					$imageDataUri = 'data:image/png;base64,' . $b64;
					$this->grav['twig']->twig_vars['identicon'] = $imageDataUri;
				} elseif ($pluginsobject['type'] == 'pattern') {
					$hash = $this->grav['user']->fullname;
					$hash = md5($hash);
					$hash = str_repeat($hash, 8);
					
					if ($pluginsobject['tiles']) {
						$tiles = $pluginsobject['tiles'];
					} else {
						$tiles = 6;
					}
					if ($pluginsobject['colors']) {
						$colors = $pluginsobject['colors'];
					} else {
						$colors = 2;
					}
					
					ob_start();
					$tile = new \Ranvis\Identicon\Tile();
					$identicon = new \Ranvis\Identicon\Identicon(128, $tile, $tiles, $colors);
					$identicon->draw($hash)->output();
					$b64 = base64_encode(ob_get_contents());
					ob_end_clean();
					$imageDataUri = 'data:image/png;base64,' . $b64;
					$this->grav['twig']->twig_vars['identicon'] = $imageDataUri;
				}
			}
		}
	}
	public function onAdminTwigTemplatePaths($event) {
		$event['paths'] = [__DIR__ . '/admin/templates'];
	}
	/**
	 * Convert hexadecimal color code to RGB
	 * Source: http://php.net/manual/en/function.hexdec.php#99478
	 */
	private function hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
		$hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr);
		$rgbArray = array();
		if (strlen($hexStr) == 6) {
			$colorVal = hexdec($hexStr);
			$rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
			$rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
			$rgbArray['blue'] = 0xFF & $colorVal;
		} elseif (strlen($hexStr) == 3) {
			$rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
			$rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
			$rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
		} else {
			return false;
		}
		return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray;
	}
}