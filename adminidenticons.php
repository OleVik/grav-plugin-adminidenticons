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
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
            'onAdminTwigTemplatePaths' => ['onAdminTwigTemplatePaths', 0]
        ];
    }
    public function onPluginsInitialized()
    {
        $config = (array) $this->config->get('plugins');
        if ($this->isAdmin() && $config['adminidenticons']['enabled']) {
            $this->enable([
                'onAssetsInitialized' => ['onAssetsInitialized', 0],
                'onPageContentProcessed' => ['onPageContentProcessed', 0]
            ]);
        }
    }
    public function onAssetsInitialized()
    {
        $config = (array) $this->config->get('plugins');
        $config = $config['adminidenticons'];
        if (isset($config['border_radius'])) {
            if ($config['border_radius'] > 0) {
                $border_radius = $config['border_radius'];
            } else {
                $border_radius = 0;
            }
        }
        $this->grav['assets']->addInlineCss('.user-details img, #admin-user-details img, .admin-user-details img {border-radius: '.$border_radius.'%;}');
    }
    public function onAdminTwigTemplatePaths($event)
    {
        $event['paths'] = [__DIR__ . '/admin/templates'];
    }
    public function onPageContentProcessed()
    {
        $config = (array) $this->config->get('plugins');
        $config = $config['adminidenticons'];
        if (isset($config['type']) && $config['type'] == 'identicon') {
            $hash = $this->grav['user']->fullname;
            ob_start();
            $avity = Avity::init([
                'generator' => \Hedronium\Avity\Generators\Hash::class
            ])
            ->height(128)
            ->width(128);
            $avity->hash($hash);

            if ($config['padding']) {
                $avity->padding($config['padding']);
            } else {
                $avity->padding(28);
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
            $this->grav['twig']->twig_vars['identicon'] = $imageDataUri;
        } elseif (isset($config['type']) && $config['type'] == 'pattern') {
            $hash = $this->grav['user']->fullname;
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
            $identicon = new \Ranvis\Identicon\Identicon(128, $tile, $tiles, $colors);
            $identicon->draw($hash)->output();
            $b64 = base64_encode(ob_get_contents());
            ob_end_clean();
            $imageDataUri = 'data:image/png;base64,' . $b64;
            $this->grav['twig']->twig_vars['identicon'] = $imageDataUri;
        }
    }
    /**
     * Convert hexadecimal color code to RGB
     * Source: http://php.net/manual/en/function.hexdec.php#99478
     */
    private function hex2RGB($hexStr, $returnAsString = false, $seperator = ',')
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
