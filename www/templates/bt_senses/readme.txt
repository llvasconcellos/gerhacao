/**
 * Senses Theme for Joomla
 *
 * @package bt_senses
 * @version 1.0.0.20070601
 * @copyright BonusThemes.gr 2007
 * @link http://www.bonusthemes.gr/goto/bt_senses
 */


Template Settings
=================


Sections: 

[1] Installation
[2] Compatibility
[3] Layout information
[4] Logo
[5] Header menu
[6] Quick menu
[7] Footer menu
[8] Position: left
[9] Positions: user1, user2, user3
[10] Position: user9
[11] Main body
[12] Positions: user4, user5, user6, user7
[13] Position: footer
[14] TinyMCE styling
[15] Support for split menu
[16] PNG fix


-----------------------------------------------------------------------------


[1] Installation

You may either use the Joomla installation mechanism for site templates:
Menu > Installers > Template - Sites
Alternatively you may unpack the template in the /template directory.

To activate the template go to
Menu > Site > Template Manager > Site Templates
select bt_senses and click the "Default" button.


[2] Compatibility

The template is tested and known to work well on
MS Internet Explorer 6 and 7, FireFox 1 and 2, Safari 2


[3] Layout information

The total width of the template is 925 pixels.
The content is designed to appear in the center of the window.


[4] Logo

There are two images you can use to set the logo. One is the image that
appears on the top left, an the other under the header menu.

The corresponding names and sizes are:
/templates/bt_senses/images/header_a.gif - 280 x 261 pixels
/templates/bt_senses/images/header_b.gif - 645 x 134 pixels

These images contain part of the template background, so use the PSD file
to produce images that integrate well with the template environment.


[5] Header menu

The menu appearing on the top of the page is controlled from the control panel
Menu > mainmenu

The images for the menu items must be positioned in the /images/stories
directory, and then assigned to the corresponding menu item.


[6] Quick menu

The menu appearing on the top-right corner of the page is controlled from the
control panel: Menu > topmenu

The images for the menu items must be positioned in the /images/stories
directory, and then assigned to the corresponding menu item.

If you want to change the image of the menu tag, the file name is
/templates/bt_senses/images/quickmenu_tag.gif


[7] Footer menu

The menu appearing on the bottom-right corner of the page is controlled from
the control panel: Menu > othermenu


[8] Position: left

On position left you can put one or more modules of your choice.
If no modules are visible in position left, the position will collapse.

Module settings:
- Position: left
- Published: Yes


[9] Positions: user1, user2, user3

There are three positions that appear on the top of the page. You are free to
use them as the situation requires it. If none of these positions is used the
assigned space will collapse.

Module settings:
- Position: user1, user2 or user3
- Published: Yes

If there is at least one module in these positions a gray tab will appear on
top. If you want to change the content of the tab the file name is
/templates/bt_senses/images/titlebar_newsflash.gif


[10] Position: user9

Position user9 appears under positions user1, user2 or user3 and above the
main content.
If no modules are assigned to this position, the position will collapse.

Module settings:
- Position: user9
- Published: Yes


[11] Main body

Main body appears under positions user9 with a gray tab on top.
If you want to change the content of the tab the file name is
/templates/bt_senses/images/titlebar_content.gif


[12] Positions: user4, user5, user6, user7

There are four positions that appear on the bottom of the page. You are free
to use them as the situation requires it. If none of these positions is used
the assigned space will collapse.

Module settings:
- Position: user4, user5, user6 or user7
- Published: Yes


[13] Position: footer

Position footer appears on the bottom right corner of the page. It is
recommended that you use it to show a short text like a copyright notice.

Module settings:
- Position: footer
- Published: Yes


[14] TinyMCE styling

TinyMCE is the editor for the custom text modules. The default installation
will use the template background. If you want to have a clear background
while you edit go to
Menu > Mambots > Site Mambots > TinyMCE WYSIWYG Editor,
and set "Template CSS classes" to "No".


[15] Support for split menu

You may use exmenu to show the internal levels of a menu item from the header
menu. You can view this menu from any position, however the recommended one is
the left position. To find general information about exmenu visit it's official
website: http://de.siteof.de/

Menu module type: mod_exmenu
Menu settings:
- Published: Yes
- Begin With Level: 1
- Split Menu: 1
- Menu Count: 1


[16] PNG fix

MS Internet Explorer 5 and 6 have trouble rendering the alpha levels 
(transparencies) of PNG images. The template is equipped with a script that
fixes this problem. To enable transparencies in PNG files for MS Internet
Explorer 5 and 6, use the CSS class "pngfix". In some cases images appear
distorted. To avoid this distortion provide "width" and "height"
attributes in your <img> HTML tags.

For more information about the png fix script visit it's official website:
http://www.twinhelix.com

