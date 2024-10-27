<?php
/**
 * Plugin Name: AI Assistant for Elementor - OpenAI GPT
 * Plugin URI: https://livemeshwp.com/elementor-ai-assistant
 * Description: An AI powered content writer and generator for Elementor utilizing the OpenAI API that powers Chat GPT.
 * Author: Livemesh
 * Author URI: https://livemeshwp.com
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Version: 1.8
 * Text Domain: livemesh-el-assistant
 * Domain Path: languages
 * Elementor tested up to: 3.23.0
 * Elementor Pro tested up to: 3.23.0
 *
 * AI Assistant for Elementor is distributed under the terms of the GNU
 * General Public License as published by the Free Software Foundation,
 * either version 2 of the License, or any later version.
 *
 * AI Assistant for Elementor is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with AI Assistant for Elementor. If not, see <http://www.gnu.org/licenses/>.
 *
 *
 */

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;


// Plugin version
define('LELA_VERSION', '1.8');

// Plugin Root File
define('LELA_PLUGIN_FILE', __FILE__);

// Plugin Folder Path
define('LELA_PLUGIN_DIR', plugin_dir_path(__FILE__));

define('LELA_PLUGIN_SLUG', dirname(plugin_basename(__FILE__)));

// Plugin Folder URL
define('LELA_PLUGIN_URL', plugins_url('/', __FILE__));

// Plugin Addons Folder Path
define('LELA_ADDONS_DIR', plugin_dir_path(__FILE__) . 'includes/widgets/');

// Plugin Folder URL
define('LELA_ADDONS_URL', plugin_dir_url(__FILE__) . 'includes/widgets/');

// Plugin Help Page URL
define('LELA_PLUGIN_HELP_URL', admin_url() . 'admin.php?page=livemesh_el_assistant_documentation');

require_once(dirname(__FILE__) . '/plugin.php');
