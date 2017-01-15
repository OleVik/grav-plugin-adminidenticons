# [Grav](http://getgrav.org/) Admin Identicons Plugin

Use identicons for avatars in the Admin-plugin, through customizable, yet [classical identicons](https://github.com/Hedronium/Avity) and [classical patterns](https://github.com/ranvis/identicon). The default configuration yields an avatar like this:

![Default Identicon](http://i.imgur.com/7oEcgEE.png)

Every user avatar is unique, as users full names are hashed into virtually random strings used for generating the identicons.

## Installation and Configuration

1. Download the zip version of [this repository](https://github.com/OleVik/grav-plugin-adminidenticons) and unzip it under `/your/site/grav/user/plugins`.
2. Rename the folder to `adminidenticons`.

You should now have all the plugin files under

    /your/site/grav/user/plugins/adminidenticons

The plugin is enabled by default, and can be disabled by copying `user/plugins/adminidenticons/adminidenticons.yaml` into `user/config/plugins/adminidenticons.yaml` and setting `enabled: false`.


### Settings

All settings can be set through `adminidenticons.yaml`, or using the Admin-plugin interface. Overview:

| Variable | Default | Options | Note |
|---|---|---|---|
| `enabled` | `true` | `true` or `false` |  |
| `type` | `identicon` | `identicon` or `pattern` |  |
| `border_radius` | `100` | `0` to `100` |  |
| `background` | `'#f8f8f8'` | Hex color code | Encapsulated in quotes. |
| `foreground` | `'#64F0FF'` | Hex color code | Encapsulated in quotes. |
| `varied` | `true` | `true` or `false` | Sets varied-color mode for `identicon`-type. |
| `padding` | `28` | `4` to `60` | Padding around `identicon`-type. |
| `spacing` | `0` | `0` to `30` | Space between tiles in`identicon`-type. |
| `rows` | `0` | `0` to `20` | Rows in`identicon`-type. |
| `columns` | `0` | `0` to `20` | Columns in`identicon`-type. |
| `tiles` | `6` | `1` to `25` | Number of patterns in`pattern`-type. |
| `colors` | `2` | `1` to `25` | Number of colors in`pattern`-type. |

**Note:** Values higher than 12 for `tiles` or `colors` cause higher levels of memory- and processing power-usage by PHP.

## Example configuration outputs

### Identicons

From the [Avity](https://github.com/Hedronium/Avity)-repository.

Type|Example
---|---
Red Background and Foreground|![](https://camo.githubusercontent.com/43c9be4056f85e89f96280c5d9217e1af8be4ab2/687474703a2f2f686564726f6e69756d2e6769746875622e696f2f41766974792f696d616765732f7661726965645f312e6a706567)
Blue Background and Foreground|![](https://camo.githubusercontent.com/23d68b8fab86c7eeb2cdb7e3779da9f1271fbbb3/687474703a2f2f686564726f6e69756d2e6769746875622e696f2f41766974792f696d616765732f6461726b2e6a706567)
Rows and Columns|![](https://camo.githubusercontent.com/674135ec47075fea613c8fed549dad927abff150/687474703a2f2f686564726f6e69756d2e6769746875622e696f2f41766974792f696d616765732f335f62795f332e6a706567)
Padding|![](https://camo.githubusercontent.com/8b666ced7c6b67cb1c010717c79879659adcdd65/687474703a2f2f686564726f6e69756d2e6769746875622e696f2f41766974792f696d616765732f7061646465642e6a706567)
Varied Blue|![](https://camo.githubusercontent.com/4cd81746316e5fd90aecab2beeee496d61859dbe/687474703a2f2f686564726f6e69756d2e6769746875622e696f2f41766974792f696d616765732f7661726965645f322e6a706567)
Varied Red|![](https://camo.githubusercontent.com/43c9be4056f85e89f96280c5d9217e1af8be4ab2/687474703a2f2f686564726f6e69756d2e6769746875622e696f2f41766974792f696d616765732f7661726965645f312e6a706567)
Spacing|![](https://camo.githubusercontent.com/dab5d5e75adbb1eab85185680b37a030dfa7186c/687474703a2f2f686564726f6e69756d2e6769746875622e696f2f41766974792f696d616765732f7370616365642e6a706567)

### Patterns

From the [ranvis/identicon](https://github.com/ranvis/identicon/wiki/Samples)-wiki.

#### Random

![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/i01.png) ![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/i02.png) ![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/i03.png) ![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/i04.png) ![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/i05.png)

![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/i06.png) ![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/i07.png) ![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/i08.png) ![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/i09.png) ![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/i10.png)

#### Tiles parameter

1|2|3|4|5|6|7|8|9|10
---|---|---|---|---|---|---|---|---|---
![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/t01.png)|![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/t02.png)|![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/t03.png)|![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/t04.png)|![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/t05.png)|![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/t06.png)|![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/t07.png)|![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/t08.png)|![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/t09.png)|![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/t10.png)

#### Colors parameter

1|2|3|4|5|6|7|8|9|10
---|---|---|---|---|---|---|---|---|---
![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/c01.png)|![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/c02.png)|![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/c03.png)|![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/c04.png)|![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/c05.png)|![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/c06.png)|![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/c07.png)|![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/c08.png)|![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/c09.png)|![](https://raw.githubusercontent.com/wiki/ranvis/identicon/img/c10.png)

## Notes

Generating avatars with this plugin takes processing power and uses memory, which - depending on your configuration - will be negligible compared to requesting an external image. Also, when the site is cached the extra load is not noticeable. All images are embedded as Base64 data-URIs, thus reducing internal HTTP requests and the dependency on the external request from Gravatar. All users will also automatically get their own avatar, which is not the case with Gravatar.

The plugin overrides the image source for Gravatar in the navigation-bar and on the userinfo-page, as well as CSS that overrides the border-radius for these images. The range-field, used by forms to provide a slider to select a value, is overridden to show what value is selected.

MIT License 2017 by [Ole Vik](http://github.com/olevik).
