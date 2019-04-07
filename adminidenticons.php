<?php
/**
 * AdminIdenticons Plugin
 *
 * PHP version 7
 *
 * @category   Extensions
 * @package    Grav
 * @subpackage AdminIdenticons
 * @author     Ole Vik <git@olevik.net>
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @link       https://github.com/OleVik/grav-plugin-adminidenticons
 */

namespace Grav\Plugin;

use Grav\Common\Grav;
use Grav\Common\Plugin;
use Grav\Common\Page\Page;
use RocketTheme\Toolbox\Event\Event;

use Hedronium\Avity\Avity;

/**
 * Use Identicons for avatars in the Admin-plugin
 *
 * Class AdminIdenticonsPlugin
 *
 * @category Extensions
 * @package  Grav\Plugin
 * @author   Ole Vik <git@olevik.net>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/OleVik/grav-plugin-adminidenticons
 */
class AdminIdenticonsPlugin extends Plugin
{

    /**
     * Initialize plugin and subsequent events
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Declare config from plugin-config
     *
     * @return array Plugin configuration
     */
    public function config()
    {
        $pluginsobject = (array) $this->config->get('plugins');
        if (isset($pluginsobject) && $pluginsobject['adminidenticons']['enabled']) {
            $config = $pluginsobject['adminidenticons'];
        } else {
            return;
        }
        return $config;
    }

    /**
     * Register events with Grav
     *
     * @return void
     */
    public function onPluginsInitialized()
    {
        if (!$this->isAdmin()) {
            return;
        }
        if (is_null($this->grav['user']->avatar)) {
            $this->enable(
                [
                    'onAssetsInitialized' => ['onAssetsInitialized', 0],
                    'onPageContentProcessed' => ['onPageContentProcessed', 0],
                    'onAdminTwigTemplatePaths' => ['onAdminTwigTemplatePaths', 0]
                ]
            );
        }
    }

    /**
     * Custom CSS setting
     *
     * @return void
     */
    public function onAssetsInitialized()
    {
        $config = $this->config();
        if (isset($config['border_radius'])) {
            if ($config['border_radius'] > 0) {
                $border_radius = $config['border_radius'];
            } else {
                $border_radius = 0;
            }
        }
        $this->grav['assets']->addInlineCss('.user-details img, #admin-user-details img, .admin-user-details img {border-radius: ' . $border_radius . '%;}');
    }

    /**
     * Register templates and page
     *
     * @param RocketTheme\Toolbox\Event\Event $event Event handler
     *
     * @return array
     */
    public function onAdminTwigTemplatePaths($event)
    {
        $event['paths'] = array_merge(
            $event['paths'],
            [__DIR__ . '/admin/themes/grav/templates']
        );
        return $event;
    }

    /**
     * Create Identicons and push to Twig
     *
     * @return void
     */
    public function onPageContentProcessed()
    {
        $config = $this->config();
        $hash = $this->grav['user']->fullname;
        $imageDataUri = $this->generateIdenticon($hash, $config);
        $this->grav['twig']->twig_vars['identicon'] = $imageDataUri;
    }

    /**
     * Generate Identicon
     *
     * @param string $hash   Unique identifier for user
     * @param array  $config Plugin configuration
     *
     * @return string Base64 encoded png-image
     */
    public function generateIdenticon($hash, $config)
    {
        include __DIR__ . '/vendor/autoload.php';
        if (isset($config['type']) && $config['type'] == 'identicon') {
            ob_start();
            $avity = Avity::init(
                [
                    'generator' => \Hedronium\Avity\Generators\Hash::class
                ]
            )
            ->height(200)
            ->width(200);
            $avity->hash($hash);

            if ($config['padding']) {
                $avity->padding($config['padding']);
            } else {
                $avity->padding(40);
            }
            if ($config['rows']) {
                $avity->rows($config['rows']);
            }
            if ($config['columns']) {
                $avity->columns($config['columns']);
            }
            if ($config['varied']) {
                $avity->style()
                    ->variedColor();
            }
            if ($config['background']) {
                $background = $this->hex2RGB($config['background']);
                $avity->style()
                    ->background($background['red'], $background['green'], $background['blue']);
            }
            if ($config['foreground']) {
                $foreground = $this->hex2RGB($config['foreground']);
                $avity->style()
                    ->foreground($foreground['red'], $foreground['green'], $foreground['blue']);
            }
            if ($config['spacing']) {
                $avity->style()
                    ->spacing($config['spacing']);
            }

            $avity->generate()
                ->png()
                ->quality(100)
                ->toBrowser();
            $b64 = base64_encode(ob_get_contents());
            ob_end_clean();
            $imageDataUri = 'data:image/png;base64,' . $b64;
        } elseif (isset($config['type']) && $config['type'] == 'pattern') {
            $hash = md5($hash);
            $hash = str_repeat($hash, 8);

            if ($config['tiles']) {
                $tiles = $config['tiles'];
            } else {
                $tiles = 6;
            }
            if ($config['colors']) {
                $colors = $config['colors'];
            } else {
                $colors = 2;
            }

            ob_start();
            $tile = new \Ranvis\Identicon\Tile();
            $identicon = new \Ranvis\Identicon\Identicon(200, $tile, $tiles, $colors);
            $identicon->draw($hash)->output();
            $b64 = base64_encode(ob_get_contents());
            ob_end_clean();
            $imageDataUri = 'data:image/png;base64,' . $b64;
        }
        return $imageDataUri;
    }

    /**
     * Convert hexadecimal color code to RGB
     *
     * @param string  $hexStr         Hexadecimal color
     * @param boolean $returnAsString Return a string
     * @param string  $seperator      Separator
     *
     * @return string RGB value
     *
     * @see http://php.net/manual/en/function.hexdec.php#99478
     */
    public function hex2RGB($hexStr, $returnAsString = false, $seperator = ',')
    {
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
