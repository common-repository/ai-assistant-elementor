=== AI Assistant for Elementor - Auto Content Writer, OpenAI, ChatGPT ===
Author URI: https://livemeshwp.com
Plugin URI: https://livemeshwp.com/elementor-ai-assistant
Donate link: https://livemeshwp.com/
Contributors: livemesh
Tags: elementor, elementor addons, chatgpt, openai, ai, livemesh
Requires at least: 5.8
Tested up to: 6.6
Requires PHP: 5.6
Stable Tag: 1.8
License: GPLv3
License URI: https://opensource.org/licenses/GPL-3.0

An AI powered content writer and generator for Elementor utilizing the OpenAI API that powers Chat GPT.

== Description ==

An AI-powered content generator addon for Elementor that utilizes the OpenAI API to create high-quality, unique content. **This tool utilizes the same technology that powers Chat GPT to generate written content that is tailored to your specific needs and goals.** With this tool, you can easily create compelling and engaging content for your website, blog, or other digital platforms. Whether you are a blogger, marketer, or business owner, this content generator can help you save time and effort while creating high-quality content that resonates with your target audience.

Upon activating the plugin, please navigate to the settings panel located at **Settings -> Elementor AI Assistant** in WP Admin and input the Open AI secret key. Be sure to also provide all necessary default values for fields such as max tokens, temperature, presence penalty, frequency penalty, and language before saving the changes.

Once the required API settings are provided, proceed to create a page and edit it using Elementor.

If the plugin has been properly activated, **you should find that the native Elementor addons - Text Editor and Heading enhanced with AI content generation features**.

These AI enhanced widget come with options to provide prompt as well as keywords for content generation or rewrite. Once the prompt and keywords are provided, hit the Generate button to create or rephrase content.

**The <a href="https://codecanyon.net/item/ai-assistant-for-elementor/43387314" title="AI Assistant for Elementor by Livemesh Premium Version" target="_blank">premium version of the plugin</a>** can do much more -
* You can choose **writing style, writing tone** for text generation.
* **You can generate AI images on the fly as the content is generated**. You can choose the image style as well as the size.
* **You can also generate images within the native Elementor Image widget**. The plugin enhances the native Image widget to provide prompt, image style and image size parameters for generation of AI images by connecting to OpenAI DALL-E AI system. **The image generated is stored in the WP media library automatically**.
* The premium version also **enhances the HTML widget of Elementor to generate HTML code** for sections of your website
* The **Code Highlight addon of Elementor PRO is AI enabled to help generate or fix source code** in a programming language of your choice based on your prompt. Very useful for programming blogs, tutorial websites etc.

== Installation ==

1. Install and activate the Elementor plugin.
2. Unzip the downloaded ai-assistant-elementor.zip file and upload to the `/wp-content/plugins/` directory or install the AI Assistant for Elementor by Livemesh plugin from WordPress repository. Activate the plugin through the 'Plugins' menu in WordPress.

== Support ==

We provide support to the free version of the plugin in the <a href="https://wordpress.org/support/plugin/ai-assistant-elementor/" title="AI Assistant for Elementor by Livemesh Support Forum" target="_blank">dedicated support forum</a>.

== Frequently Asked Questions ==

= What type of support can I expect for the plugin? =

We provide support to the free version of the plugin in the <a href="https://wordpress.org/support/plugin/ai-assistant-elementor/" title="AI Assistant for Elementor by Livemesh Support Forum" target="_blank">dedicated support forum</a>.

= Does AI Assistant for Elementor work with latest versions of Elementor? =

Our plugin is frequently tested for compatibility with latest versions and features of Elementor.

= Can I use AI Assistant for Elementor  on a wordpress.com site? =

Since the plugin is hosted on wordpress.org, it is available on wordpress.com as well. However, you may need to opt for a business plan to install/activate third-party plugins on wordpress.com.

= We have a query not addressed here, how can we contact you? =

Email us at support[at]livemeshthemes.com and we will be happy to assist you.

== Screenshots ==
1. The OpenAI API settings to be provided in Settings->Elementor AI Assistant page
2. The Text Editor widget enhanced with AI generation features involving prompt and Generate button.
3. The Heading widget enhanced with AI generation features.
4. The OpenAI settings in the Settings tab of Text Editor addon

== Changelog ==

== Changelog ==


= 1.8 =
* Added - Support for new OpenAI models GPT-4o and GPT-4-Turbo. Removed deprecated models.
* Added - Compatibility with Elementor 3.22 version

= 1.7 =
* Added - Support for languages - Vietnamese, Bulgarian, Croatian, Czech, Urdu, Tamil and Kannada
* Added - Support for the updated language models from OpenAI including gpt-4-32k and gpt-3.5-turbo-16k

= 1.6.1 =
 * Added - Hebrew language option for content and headline generation

= 1.6 =
 * Added - Support for GPT-4 models (currently on waitlist) for content and headline generation
 * Deprecated - The codex models which are being replaced by gpt-3.5-turbo and GPT-4 models
 * Tweak - Prompts for content and headline generation

= 1.5.5 =
 * Added - The new chat model gpt-3.5-turbo for content generation. This model comes at 1/10th the cost of the default model text-davinci-003 but equally capable.

= 1.5.4 =
 * Added - Ukrainian, Swedish, Hungarian and Greek language option for content and headline generation

= 1.5.2 =
 * Added - Persian language option for content and headline generation

 = 1.5.1 =
  * Added - Compatibility with Elementor 3.11
  * Updated - Translation files

= 1.5 =
 * Fixed - The plugin script raises a JS error on the frontend as reported by one of our customers.
 * Tweak - Preventing plugin from loading scripts on the frontend page.

= 1.4 =
* Added - The native Heading addon of Elementor now generates one or more AI powered headlines.

= 1.3 =
* Added - Deprecated the AI Advanced Writer addon, now replaced by native Text Editor addon of Elementor plugin. All advanced AI features of deprecated widget is now part of Text Editor widget.

= 1.2 =
* Fixed - Timeout errors
* Tweak - For better control over content generation, the OpenAI settings - max tokens, temperature, presence penalty, frequency penalty now moved to the addon settings from the plugin settings.

= 1.1 =
* Fixed - Missing sanitization checks for fields and translatable strings
* Added - Strict mode for JS files

= 1.0 =
* Initial release.
