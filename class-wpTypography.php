<?php

// TO DO
// internationalize
// test for compatiblity

/**
 * Main wp-Typography plugin class. All WordPress specific code goes here.
 * 
 * @author mundschenk-at
 */
class wpTypography {
	
	private $plugin_name = 'wp-Typography';
	private $install_requirements = array(
											'PHP Version' 		=> '5.3.0',
											'WordPress Version'	=> '4.0',
											'Multibyte' 		=> true,
											'UTF-8'				=> true,
										 );
	private $local_plugin_path;             // relative from plugins folder (assigned in __construct)
	private $plugin_path;                   // we will assign WP_PLUGIN_DIR base in __construct
	private $option_group = 'typo_options'; // used to register options for option page
	private $settings;  
	private $php_typo;                      // this will be a class within a class
	
	/**
	 * Links to add the settings page. 
	 * @var array $adminResourceLinks An array in the form of 'anchor text' => 'URL'.
	 */
	private $admin_resource_links;
	
	/**
	 * Section IDs and headings for the settings page.
	 * 
	 * Sections will be displayed in the order included.
	 * 
	 * @var array $adminFormSections An array in the form of 'id' => 'heading'.
	 */
	private $admin_form_sections;
	
	/**
	 * Fieldsets in the admin settings.
	 * 
	 * The fieldsets will be displayed in the order of inclusion.
	 * 
	 * @var array $admin_form_section_fieldsets {
	 *     @type array $id {
	 *         @type string $heading Fieldset name.
	 *         @type string $sectionID Parent section ID.
	 *     }
	 * }
	 */
	private $admin_form_section_fieldsets;
				
	/**
	 * The form controls on the settings page.
	 * 
	 * @var array $admin_form_controls {
	 * 		@type array $id {
	 *          Contents and meta data for the control $id.
	 *          
	 *          @type string $section Section ID. Required.
	 *          @type string $fieldset Fieldset ID. Optional.
	 *          @type string $labelBefore Label content. Optional.
	 *          @type string $labelAfter Only for controls of type "select", where the 
	 *                       control is in the middle of a label. Optional.
	 *          @type string $helpText Help text. Optional.
	 *          @type string $control The HTML control element. Required.
	 *          @type string $inputType The input type for 'input' controls. Optional.
	 *          @type array  $optionValues Array in the form ($value => $text). Optional (i.e. only for 'select' controls).
	 *          @type string $default The default value. Required, but may be an empty string.
	 * 		}
	 * }
	 */
	private $admin_form_controls;
	
	/**
	 * Sets up a new wpTypography object.
	 * 
	 * @param string $basename The result of plugin_basename() for the main plugin file.
	 */
	function __construct( $basename = 'wp-typography/wp-typography.php' ) {
		global $wp_version;
		$abortLoad = false;
		if ( version_compare( $wp_version, $this->install_requirements['WordPress Version'], '<' ) ) {
			if ( is_admin() ) {
				add_action( 'admin_notices', array( &$this, 'admin_notices_wp_version_incompatible' ) );
			}
			$abortLoad = true;
		} elseif ( version_compare( PHP_VERSION, $this->install_requirements['PHP Version'], '<' ) ) {
			if( is_admin() ) {
				add_action( 'admin_notices', array( &$this, 'admin_notices_php_version_incompatible' ) );
			}
			$abortLoad = true;
		} elseif ( ! function_exists( 'mb_strlen' ) ||
				   ! function_exists( 'mb_strtolower' ) || 
				   ! function_exists( 'mb_substr') || 
				   ! function_exists( 'mb_detect_encoding' ) ) {
			if( is_admin() ) {
				add_action( 'admin_notices', array( &$this, 'admin_notices_mbstring_incompatible' ) );
			}
			$abortLoad = true;
		} elseif ( get_bloginfo( 'charset' ) != 'UTF-8' && get_bloginfo( 'charset' ) != 'utf-8' ) {
			if ( is_admin() ) {
				add_action( 'admin_notices', array( &$this, 'admin_notices_charset_incompatible' ) );
			}
			$abortLoad = true;
		}

		if ( $abortLoad == true ) return;
		
		// property intialization
		$this->local_plugin_path = $basename;
		$this->plugin_path = plugin_dir_path( __FILE__ ) . basename( $this->local_plugin_path );
		$this->initialize_settings_properties();
		
		// include needed files
		require_once( plugin_dir_path( __FILE__ ) . 'php-typography/php-typography.php' );
		
		$typoRestoreDefaults = false;
		if ( get_option( 'typoRestoreDefaults' ) == true ) {
			$typoRestoreDefaults = true;
		}
		$this->register_plugin($typoRestoreDefaults);

		foreach ( $this->admin_form_controls as $key => $value ) {
			$this->settings[ $key ] = get_option( $key );
		}
	
		// dynamically generate the list of hyphenation language patterns
		$this->php_typo = new phpTypography( false );
		$this->admin_form_controls['typoHyphenateLanguages']['optionValues'] = $this->php_typo->get_languages();
		$this->admin_form_controls['typoDiacriticLanguages']['optionValues'] = $this->php_typo->get_diacritic_languages();

		// load configuration variables into our phpTypography class
		$this->php_typo->set_tags_to_ignore($this->settings['typoIgnoreTags']);
		$this->php_typo->set_classes_to_ignore($this->settings['typoIgnoreClasses']);
		$this->php_typo->set_ids_to_ignore($this->settings['typoIgnoreIDs']);
		if ($this->settings['typoSmartCharacters'])	{
			
			$this->php_typo->set_smart_dashes($this->settings['typoSmartDashes']);
			$this->php_typo->set_smart_ellipses($this->settings['typoSmartEllipses']);
			$this->php_typo->set_smart_math($this->settings['typoSmartMath']);
			$this->php_typo->set_smart_exponents($this->settings['typoSmartMath']); // note smart_exponents was grouped with smart_math for the WordPress plugin, but does not have to be done that way for other ports
			$this->php_typo->set_smart_fractions($this->settings['typoSmartFractions']);
			$this->php_typo->set_smart_ordinal_suffix($this->settings['typoSmartOrdinals']);
			$this->php_typo->set_smart_marks($this->settings['typoSmartMarks']);
			$this->php_typo->set_smart_quotes($this->settings['typoSmartQuotes']);
			
			$this->php_typo->set_smart_diacritics($this->settings['typoSmartDiacritics']);
			$this->php_typo->set_diacritic_language($this->settings['typoDiacriticLanguages']);
			$this->php_typo->set_diacritic_custom_replacements($this->settings['typoDiacriticCustomReplacements']);

			$this->php_typo->set_smart_quotes_primary($this->settings['typoSmartQuotesPrimary']);
			$this->php_typo->set_smart_quotes_secondary($this->settings['typoSmartQuotesSecondary']);

		} else {
			$this->php_typo->set_smart_dashes(false);
			$this->php_typo->set_smart_ellipses(false);
			$this->php_typo->set_smart_math(false);
			$this->php_typo->set_smart_exponents(false);
			$this->php_typo->set_smart_fractions(false);
			$this->php_typo->set_smart_ordinal_suffix(false);
			$this->php_typo->set_smart_marks(false);
			$this->php_typo->set_smart_quotes(false);
			$this->php_typo->set_smart_diacritics(false);
		}
		$this->php_typo->set_single_character_word_spacing($this->settings['typoSingleCharacterWordSpacing']);
		$this->php_typo->set_dash_spacing($this->settings['typoDashSpacing']);
		$this->php_typo->set_fraction_spacing($this->settings['typoFractionSpacing']);
		$this->php_typo->set_unit_spacing($this->settings['typoUnitSpacing']);
		$this->php_typo->set_units($this->settings['typoUnits']);
		$this->php_typo->set_space_collapse($this->settings['typoSpaceCollapse']);
		$this->php_typo->set_dewidow($this->settings['typoPreventWidows']);
		$this->php_typo->set_max_dewidow_length($this->settings['typoWidowMinLength']);
		$this->php_typo->set_max_dewidow_pull($this->settings['typoWidowMaxPull']);
		$this->php_typo->set_wrap_hard_hyphens($this->settings['typoWrapHyphens']);
		$this->php_typo->set_email_wrap($this->settings['typoWrapEmails']);
		$this->php_typo->set_url_wrap($this->settings['typoWrapURLs']);
		$this->php_typo->set_min_after_url_wrap($this->settings['typoWrapMinAfter']);
		$this->php_typo->set_style_ampersands($this->settings['typoStyleAmps']);
		$this->php_typo->set_style_caps($this->settings['typoStyleCaps']);
		$this->php_typo->set_style_numbers($this->settings['typoStyleNumbers']);
		$this->php_typo->set_style_initial_quotes($this->settings['typoStyleInitialQuotes']);
		$this->php_typo->set_initial_quote_tags($this->settings['typoInitialQuoteTags']);
		if ($this->settings['typoEnableHyphenation']) {
			
			$this->php_typo->set_hyphenation($this->settings['typoEnableHyphenation']);
			$this->php_typo->set_hyphenate_headings($this->settings['typoHyphenateHeadings']);
			$this->php_typo->set_hyphenate_all_caps($this->settings['typoHyphenateCaps']);
			$this->php_typo->set_hyphenate_title_case($this->settings['typoHyphenateTitleCase']);
			$this->php_typo->set_hyphenation_language($this->settings['typoHyphenateLanguages']);
			$this->php_typo->set_min_length_hyphenation($this->settings['typoHyphenateMinLength']);
			$this->php_typo->set_min_before_hyphenation($this->settings['typoHyphenateMinBefore']);
			$this->php_typo->set_min_after_hyphenation($this->settings['typoHyphenateMinAfter']);
			$this->php_typo->set_hyphenation_exceptions($this->settings['typoHyphenateExceptions']);
			
		} else { // save some cycles
			$this->php_typo->set_hyphenation($this->settings['typoEnableHyphenation']);
		}
		

		// set up the plugin options page
		register_activation_hook($this->plugin_path, array(&$this, 'register_plugin'));
		add_action('admin_menu', array(&$this, 'add_options_page'));
		add_action('admin_init', array(&$this, 'register_the_settings'));
		add_filter( 'plugin_action_links_'.$this->local_plugin_path, array(&$this, 'add_filter_plugin_action_links'));

		// Remove default Texturize filter if it conflicts.
		if($this->settings['typoSmartCharacters'] && ! is_admin() ) {
			remove_filter('category_description', 'wptexturize');
			remove_filter('comment_author', 'wptexturize');
			remove_filter('comment_text', 'wptexturize');
			remove_filter('the_content', 'wptexturize');
			remove_filter('single_post_title', 'wptexturize');
			remove_filter('the_title', 'wptexturize');
			remove_filter('the_excerpt', 'wptexturize');
			remove_filter('widget_text', 'wptexturize');
			remove_filter('widget_title', 'wptexturize');
		}

		// apply filters

/*	// removed because it caused issues for feeds
		add_filter('bloginfo', array(&$this, 'processBloginfo'), 9999);
		add_filter('wp_title', 'strip_tags', 9999);
		add_filter('single_post_title', 'strip_tags', 9999);
*/
		if ( ! is_admin() ) {
			add_filter('comment_author', array(&$this, 'process'), 9999);
			add_filter('comment_text', array(&$this, 'process'), 9999);
			add_filter('the_title', array(&$this, 'process_title'), 9999);
			add_filter('the_content', array(&$this, 'process'), 9999);
			add_filter('the_excerpt', array(&$this, 'process'), 9999);
			add_filter('widget_text', array(&$this, 'process'), 9999);
			add_filter('widget_title', array(&$this, 'process_title'), 9999);
		}
		
		// add IE6 zero-width-space removal CSS Hook styling
		add_action('wp_head', array(&$this, 'add_wp_head'));
	}
	

	/**
	 * Initialize displayable strings for the plugin settings page.
	 */
	function initialize_settings_properties() {
		$this->admin_resource_links = array(
			/*
			  'anchor text' => 'URL', 		// REQUIRED 
			 */
			__( 'Plugin Home', 'wp-typography' ) => 'https://code.mundschenk.at/wp-typography/',
			__( 'FAQs',        'wp-typography' ) => 'https://code.mundschenk.at/wp-typography/frequently-asked-questions/',
			__( 'Changelog',   'wp-typography' ) => 'https://code.mundschenk.at/wp-typography/changes/',
			__( 'License',     'wp-typography' ) => 'https://code.mundschenk.at/wp-typography/license/',
		);
		
		// sections will be displayed in the order included
		$this->admin_form_sections = array( 
			/*
			'id' 					=> string heading,		// REQUIRED
			*/
			'general-scope' 		=> __( 'General Scope', 'wp-typography' ),
			'hyphenation' 			=> __( 'Hyphenation', 'wp-typography' ),
			'character-replacement'	=> __( 'Intelligent Character Replacement', 'wp-typography' ),
			'space-control' 		=> __( 'Space Control', 'wp-typography' ),
			'css-hooks' 			=> __( 'Add CSS Hooks', 'wp-typography' ),
		);

		// fieldsets will be displayed in the order included
		$this->admin_form_section_fieldsets = array( 
			/*
			'id' => array(
				'heading' 	=> string Fieldset Name,	  // REQUIRED
				'sectionID' => string Parent Section ID,  // REQUIRED
			),
			*/
			'smart-quotes' => array(
				'heading' 	=> __( 'Quotemarks', 'wp-typography' ),
				'sectionID' => 'character-replacement',
			),
			'diacritics' => array(
				'heading' 	=> __( 'Diacritics', 'wp-typography' ),
				'sectionID'	=> 'diacritics',
			),
			'values-and-units' => array(
				'heading' 	=> __( 'Values &amp; Units', 'wp-typography' ),
				'sectionID' => 'space-control',
			),
			'enable-wrapping' => array(
				'heading' 	=> __( 'Enable Wrapping', 'wp-typography' ),
				'sectionID' => 'space-control',
			),
			'widows' => array(
				'heading' 	=> __( 'Widows', 'wp-typography' ),
				'sectionID' => 'space-control',
			),
		);
		
		$this->admin_form_controls = array(
			/*
			 "id" => array(
			 	"section" 		=> string Section ID, 		// REQUIRED
			 	"fieldset" 		=> string Fieldset ID,		// OPTIONAL
			 	"labelBefore" 	=> string Label Content,	// OPTIONAL
			 	"labelAfter"	=> string Label Content,	// OPTIONAL, only for controls of type "select", where the control is in the middle of a label
			 	"helpText" 		=> string Help Text,		// OPTIONAL
			 	"control" 		=> string Control,			// REQUIRED
			 	"inputType" 	=> string Control Type,		// OPTIONAL
			 	"optionValues"	=> array(value=>text, ... )	// OPTIONAL, only for controls of type "select"
			 	"default" 		=> string Default Value,	// REQUIRED (although it may be an empty string)
			 ),
			*/
			"typoIgnoreTags" => array(
				"section"		=> "general-scope",
				"labelBefore" 	=> __( "Do not process the content of these <strong>HTML elements</strong>:", 'wp-typography' ),
				"helpText" 		=> __( "Separate tag names with spaces; do not include the <samp>&lt;</samp> or <samp>&gt;</samp>.", 'wp-typography' ),
				"control" 		=> "textarea",
				"default" 		=> "code head kbd object option pre samp script style textarea title var math",
			),
			"typoIgnoreClasses" => array(
				"section" 		=> "general-scope",
				"labelBefore" 	=> __( "Do not process elements of <strong>class</strong>:", 'wp-typography' ),
				"helpText" 		=> __( "Separate class names with spaces.", 'wp-typography' ),
				"control" 		=> "textarea",
				"default" 		=> "vcard noTypo",
			),
			"typoIgnoreIDs" => array(
				"section" 		=> "general-scope",
				"labelBefore" 	=> __( "Do not process elements of <strong>ID</strong>:", 'wp-typography' ),
				"helpText" 		=> __( "Separate ID names with spaces.", 'wp-typography' ),
				"control" 		=> "textarea",
				"default" 		=> "",
			),
			"typoEnableHyphenation" => array(
				"section" 		=> "hyphenation",
				"labelAfter" 	=> __( "Enable hyphenation.", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 1,
			),
			"typoHyphenateLanguages" => array(
				"section"		=> "hyphenation",
				"labelBefore" 	=> __( "Language for hyphenation rules:", 'wp-typography' ),
				"control" 		=> "select",
				"optionValues"	=> array(), // automatically detected and listed in __construct
				"default" 		=> "en-US",
			),
			"typoHyphenateHeadings" => array(
				"section" 		=> "hyphenation",
				"labelAfter" 	=> __( "Hyphenate headings.", 'wp-typography' ),
				"helpText" 		=> __( "Unchecking will disallow hyphenation of headings, even if allowed in the general scope.", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 0,
			),
			"typoHyphenateTitleCase" => array(
				"section" 		=> "hyphenation",
				"labelAfter" 	=> __( "Allow hyphenation of words that begin with a capital letter.", 'wp-typography' ),
				"helpText" 		=> __( "Uncheck to avoid hyphenation of proper nouns.", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 1,
			),
			"typoHyphenateCaps" => array(
				"section" 		=> "hyphenation",
				"labelAfter" 	=> __( "Hyphenate words in ALL CAPS.", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 0,
			),
			"typoHyphenateMinLength" => array(
				"section"		=> "hyphenation",
				"labelBefore" 	=> __( "Do not hyphenate words with less than", 'wp-typography' ),
				"labelAfter"	=> __( "letters.", 'wp-typography' ),
				"control" 		=> "select",
				"optionValues"	=> array(4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10),
				"default" 		=> 5,
			),
			"typoHyphenateMinBefore" => array(
				"section"		=> "hyphenation",
				"labelBefore" 	=> __( "Keep at least", 'wp-typography' ),
				"labelAfter"	=> __( "letters before hyphenation.", 'wp-typography' ),
				"control" 		=> "select",
				"optionValues"	=> array(2=>2,3=>3,4=>4,5=>5),
				"default" 		=> 3,
			),
			"typoHyphenateMinAfter" => array(
				"section"		=> "hyphenation",
				"labelBefore" 	=> __( "Keep at least", 'wp-typography' ),
				"labelAfter"	=> __( "letters after hyphenation.", 'wp-typography' ),
				"control" 		=> "select",
				"optionValues"	=> array(2=>2,3=>3,4=>4,5=>5),
				"default" 		=> 2,
			),
			"typoHyphenateExceptions" => array(
				"section" 		=> "hyphenation",
				"labelBefore" 	=> __( "Exception List:", 'wp-typography' ),
				"helpText" 		=> __( "Mark allowed hyphenations with \"-\"; separate words with spaces.", 'wp-typography' ),
				"control" 		=> "textarea",
				"default" 		=> "Mund-schenk",
			),
			"typoSmartCharacters" => array(
				"section"		=> "character-replacement",
				"labelAfter" 	=> __( "Override WordPress' automatic character handling with your preferences here.", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 1,
			),
			"typoSmartQuotes" => array(
				"section"		=> "character-replacement",
				"fieldset" 		=> "smart-quotes",
				"labelAfter" 	=> __( "Transform straight quotes [ <samp>'</samp> <samp>\"</samp> ] to typographically correct characters as detailed below.", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 1,
			),
		
			"typoSmartQuotesPrimary" => array(
				"section"		=> "character-replacement",
				"fieldset" 		=> "smart-quotes",
				"labelBefore" 	=> __( "Convert <samp>\"foo\"</samp> to:", 'wp-typography' ),
				"helpText" 		=> __( "Primary quotation style.", 'wp-typography' ),
				"control" 		=> "select",
				"optionValues"	=> array(
					"doubleCurled" => "&ldquo;foo&rdquo;",
					"doubleCurledReversed" => "&rdquo;foo&rdquo;",
					"doubleLow9" => "&bdquo;foo&rdquo;",
					"doubleLow9Reversed" => "&bdquo;foo&ldquo;",
					"singleCurled" => "&lsquo;foo&rsquo;",
					"singleCurledReversed" => "&rsquo;foo&rsquo;",
					"singleLow9" => "&sbquo;foo&rsquo;",
					"singleLow9Reversed" => "&sbquo;foo&lsquo;",
					"doubleGuillemetsFrench" => "&laquo;&nbsp;foo&nbsp;&raquo;",
					"doubleGuillemets" => "&laquo;foo&raquo;",
					"doubleGuillemetsReversed" => "&raquo;foo&laquo;",
					"singleGuillemets" => "&lsaquo;foo&rsaquo;",
					"singleGuillemetsReversed" => "&rsaquo;foo&lsaquo;",
					"cornerBrackets" => "&#x300c;foo&#x300d;",
					"whiteCornerBracket" => "&#x300e;foo&#x300f;",
				),
				"default" 		=> "doubleCurled",
			),
			"typoSmartQuotesSecondary" => array(
				"section"		=> "character-replacement",
				"fieldset" 		=> "smart-quotes",
				"labelBefore" 	=> __( "Convert <samp>'foo'</samp> to:", 'wp-typography' ),
				"helpText" 		=> __( "Secondary quotation style.", 'wp-typography' ),
				"control" 		=> "select",
				"optionValues"	=> array(
					"doubleCurled" => "&ldquo;foo&rdquo;",
					"doubleCurledReversed" => "&rdquo;foo&rdquo;",
					"doubleLow9" => "&bdquo;foo&rdquo;",
					"doubleLow9Reversed" => "&bdquo;foo&ldquo;",
					"singleCurled" => "&lsquo;foo&rsquo;",
					"singleCurledReversed" => "&rsquo;foo&rsquo;",
					"singleLow9" => "&sbquo;foo&rsquo;",
					"singleLow9Reversed" => "&sbquo;foo&lsquo;",
					"doubleGuillemetsFrench" => "&laquo;&nbsp;foo&nbsp;&raquo;",
					"doubleGuillemets" => "&laquo;foo&raquo;",
					"doubleGuillemetsReversed" => "&raquo;foo&laquo;",
					"singleGuillemets" => "&lsaquo;foo&rsaquo;",
					"singleGuillemetsReversed" => "&rsaquo;foo&lsaquo;",
					"cornerBrackets" => "&#x300c;foo&#x300d;",
					"whiteCornerBracket" => "&#x300e;foo&#x300f;",
				),
				"default" 		=> "singleCurled",
			),
		
			"typoSmartDashes" => array(
				"section"			=> "character-replacement",
				"labelAfter" 		=> __( "Transform minus-hyphens [ <samp>-</samp> <samp>--</samp> ] to contextually appropriate dashes, minus signs, and hyphens [ <samp>&ndash;</samp> <samp>&mdash;</samp> <samp>&#8722;</samp> <samp>&#8208;</samp> ].", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 1,
			),
			"typoSmartEllipses" => array(
				"section"		=> "character-replacement",
				"labelAfter" 	=> __( "Transform three periods [ <samp>...</samp> ] to  ellipses [ <samp>&hellip;</samp> ].", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 1,
			),
				
				
			"typoSmartDiacritics" => array(
				"section"		=> "character-replacement",
				"fieldset" 		=> "diacritics",
				"labelAfter" 	=> __( "Force diacritics where appropriate.", 'wp-typography' ),
				"helpText" 		=> __( "i.e. <samp>creme brulee</samp> becomes <samp>crème brûlée</samp>", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 0,
			),
			"typoDiacriticLanguages" => array(
				"section"		=> "character-replacement",
				"fieldset" 		=> "diacritics",
				"labelBefore" 	=> __( "Language for diacritic replacements:", 'wp-typography' ),
				"helpText" 		=> __( "Language definitions will purposefully <strong>not</strong> process words that have alternate meaning without diacritics like <samp>resume &amp; résumé</samp>, <samp>divorce &amp; divorcé</samp>, and <samp>expose &amp; exposé</samp>.", 'wp-typography' ),
				"control" 		=> "select",
				"optionValues"	=> array(), // automatically detected and listed in __construct
				"default" 		=> "en-US",
			),
			"typoDiacriticCustomReplacements" => array(
				"section" 		=> "character-replacement",
				"fieldset" 		=> "diacritics",
				"labelBefore" 	=> __( "Custom diacritic word replacements:", 'wp-typography' ),
				"helpText" 		=> __( "Must be formatted <samp>\"word to replace\"=>\"replacement word\",</samp>; This is case-sensitive.", 'wp-typography' ),
				"control" 		=> "textarea",
				"default" 		=> '"cooperate"=>"coöperate", "Cooperate"=>"Coöperate", "cooperation"=>"coöperation", "Cooperation"=>"Coöperation", "cooperative"=>"coöperative", "Cooperative"=>"Coöperative", "coordinate"=>"coördinate", "Coordinate"=>"Coördinate", "coordinated"=>"coördinated", "Coordinated"=>"Coördinated", "coordinating"=>"coördinating", "Coordinating"=>"Coördinating", "coordination"=>"coördination", "Coordination"=>"Coördination", "coordinator"=>"coördinator", "Coordinator"=>"Coördinator", "coordinators"=>"coördinators", "Coordinators"=>"Coördinators", "continuum"=>"continuüm", "Continuum"=>"Continuüm", "debacle"=>"débâcle", "Debacle"=>"Débâcle", "elite"=>"élite", "Elite"=>"Élite",',
			),
				
				
			"typoSmartMarks" => array(
				"section"		=> "character-replacement",
				"labelAfter" 	=> __( "Transform registration marks [ <samp>(c)</samp> <samp>(r)</samp> <samp>(tm)</samp> <samp>(sm)</samp> <samp>(p)</samp> ] to  proper characters [ <samp>©</samp> <samp>®</samp> <samp>™</samp> <samp>℠</samp> <samp>℗</samp> ].", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 1,
			),
			"typoSmartMath" => array(
				"section"		=> "character-replacement",
				"labelAfter" 	=> __( "Transform exponents [ <samp>3^2</samp> ] to pretty exponents [ <samp>3<sup>2</sup></samp> ] and math symbols [ <samp>(2x6)/3=4</samp> ] to correct symbols [ <samp>(2&#215;6)&#247;3=4</samp> ].", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 0,
			),
			"typoSmartFractions" => array(
				"section"		=> "character-replacement",
				"labelAfter" 	=> __( "Transform fractions [ <samp>1/2</samp> ] to  pretty fractions [ <samp><sup>1</sup>&#8260;<sub>2</sub></samp> ].<br>WARNING: If you use a font (like Lucida Grande) that does not have a fraction-slash character, this may cause a missing line between the numerator and denominator.", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 0,
			),
			"typoSmartOrdinals" => array(
				"section"		=> "character-replacement",
				"labelAfter" 	=> __( "Transform ordinal suffixes [ <samp>1st</samp> ] to  pretty ordinals [ <samp>1<sup>st</sup></samp> ].", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 0,
			),
			"typoSingleCharacterWordSpacing" => array(
				"section"		=> "space-control",
				"labelAfter" 	=> __( "Prevent single character words from residing at the end of a line of text (unless it is a widow).", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 0,
			),
			"typoDashSpacing" => array(
				"section"		=> "space-control",
				"labelAfter" 	=> __( "Force thin spaces between em &amp; en dashes and adjoining words.  This will display poorly in IE6 with some fonts (like Tahoma) and in rare instances in WebKit browsers (Safari and Chrome).", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 0,
			),
			"typoFractionSpacing" => array(
				"section"		=> "space-control",
				"labelAfter" 	=> __( "Keep integers with adjoining fractions.", 'wp-typography' ),
				"helpText" 		=> __( "i.e. <samp>1 1/2</samp> or <samp>1 <sup>1</sup>&#8260;<sub>2</sub></samp>", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 0,
			),
			"typoSpaceCollapse" => array(
				"section"		=> "space-control",
				"labelAfter" 	=> __( "Collapse adjacent spacing to a single character.", 'wp-typography' ),
				"helpText" 		=> __( "Normal HTML processing collapses basic spaces.  This option will additionally collapse no-break spaces, zero-width spaces, figure spaces, etc.", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 0,
			),
			"typoUnitSpacing" => array(
				"section"		=> "space-control",
				"fieldset" 		=> "values-and-units",
				"labelAfter" 	=> __( "Keep values and units together.", 'wp-typography' ),
				"helpText" 		=> __( "i.e. <samp>1 in.</samp> or <samp>10 m<sup>2</sup></samp>", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 0,
			),
			"typoUnits" => array(
				"section"		=> "space-control",
				"fieldset" 		=> "values-and-units",
				"labelBefore" 	=> __( "Unit names:", 'wp-typography' ),
				"helpText" 		=> __( "Separate unit names with spaces. We already look for a large list; fill in any holes here.", 'wp-typography' ),
				"control" 		=> "textarea",
				"default" 		=> "hectare fortnight",
			),
			"typoPreventWidows" => array(
				"section"		=> "space-control",
				"fieldset" 		=> "widows",
				"labelAfter" 	=> __( "Prevent widows", 'wp-typography' ),
				"helpText" 		=> __( "Widows are the last word in a block of text that wraps to its own line.", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 1,
			),
			"typoWidowMinLength" => array(
				"section"		=> "space-control",
				"fieldset" 		=> "widows",
				"labelBefore" 	=> __( "Only protect widows with", 'wp-typography' ),
				"labelAfter"	=> __( "or fewer letters.", 'wp-typography' ),
				"control" 		=> "select",
				"optionValues"	=> array(4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10,100=>100),
				"default" 		=> 5,
			),
			"typoWidowMaxPull" => array(
				"section"		=> "space-control",
				"fieldset" 		=> "widows",
				"labelBefore" 	=> __( "Pull at most", 'wp-typography' ),
				"labelAfter"	=> __( "letters from the previous line to keep the widow company.", 'wp-typography' ),
				"control" 		=> "select",
				"optionValues"	=> array(4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10,100=>100),
				"default" 		=> 5,
			),
			"typoWrapHyphens" => array(
				"section"		=> "space-control",
				"fieldset" 		=> "enable-wrapping",
				"labelAfter" 	=> __( "Enable wrapping after hard hyphens.", 'wp-typography' ),
				"helpText" 		=> __( "Adds zero-width spaces after hard hyphens (like in &ldquo;zero-width&rdquo;).", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 0,
			),
			"typoWrapEmails" => array(
				"section"		=> "space-control",
				"fieldset" 		=> "enable-wrapping",
				"labelAfter" 	=> __( "Enable wrapping of long emails.", 'wp-typography' ),
				"helpText" 		=> __( "Adds zero-width spaces throughout the email.", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 0,
			),
			"typoWrapURLs" => array(
				"section"		=> "space-control",
				"fieldset" 		=> "enable-wrapping",
				"labelAfter" 	=> __( "Enable wrapping of long URLs.", 'wp-typography' ),
				"helpText" 		=> __( "Adds zero-width spaces throughout the URL.", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 0,
			),
			"typoWrapMinAfter" => array(
				"section"		=> "space-control",
				"fieldset" 		=> "enable-wrapping",
				"labelBefore" 	=> __( "Keep at least the last", 'wp-typography' ),
				"labelAfter"	=> __( "characters of a URL together.", 'wp-typography' ),
				"control" 		=> "select",
				"optionValues"	=> array(3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10),
				"default" 		=> 3,
			),
			"typoRemoveIE6" => array(
				"section"		=> "space-control",
				"fieldset" 		=> "enable-wrapping",
				"labelAfter" 	=> __( "Remove zero-width spaces from IE6.", 'wp-typography' ),
				"helpText" 		=> __( "IE6 displays mangles zero-width spaces with some fonts like Tahoma (uses JavaScript).", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 0,
			),
			"typoStyleAmps" => array(
				"section" 		=> "css-hooks",
				"labelAfter" 	=> __( "Wrap ampersands [ <samp>&amp;</samp> ] with <samp>&lt;span class=\"amp\"&gt;</samp>.", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 1,
			),
			"typoStyleCaps" => array(
				"section" 		=> "css-hooks",
				"labelAfter" 	=> __( "Wrap acronyms (all capitals) with <samp>&lt;span class=\"caps\"&gt;</samp>.", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 1,
			),
			"typoStyleNumbers" => array(
				"section" 		=> "css-hooks",
				"labelAfter" 	=> __( "Wrap digits [ <samp>0123456789</samp> ] with <samp>&lt;span class=\"numbers\"&gt;</samp>.", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 0,
			),
			"typoStyleInitialQuotes" => array(
				"section" 		=> "css-hooks",
				"labelAfter" 	=> __( "Wrap initial quotes", 'wp-typography' ),
				"helpText" 		=> __( "Note: matches quotemarks at the beginning of blocks of text, <strong>not</strong> all opening quotemarks. <br />Single quotes [ <samp>&lsquo;</samp> <samp>&#8218;</samp> ] are wrapped with <samp>&lt;span class=\"quo\"&gt;</samp>. <br />Double quotes [ <samp>&ldquo;</samp> <samp>&#8222;</samp> ] are wrapped with <samp>&lt;span class=\"dquo\"&gt;</samp>. <br />Guillemets [ <samp>&laquo;</samp> <samp>&raquo;</samp> ] are wrapped with <samp>&lt;span class=\"dquo\"&gt;</samp>.", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 1,
			),
			"typoInitialQuoteTags" => array(
				"section" 		=> "css-hooks",
				"labelBefore" 	=> __( "Limit styling of initial quotes to these <strong>HTML elements</strong>:", 'wp-typography' ),
				"helpText" 		=> __( "Separate tag names with spaces; do not include the <samp>&lt;</samp> or <samp>&gt;</samp>.", 'wp-typography' ),
				"control" 		=> "textarea",
				"default" 		=> "p h1 h2 h3 h4 h5 h6 blockquote li dd dt",
			),
			"typoStyleCSSInclude" => array(
				"section" 		=> "css-hooks",
				"labelAfter" 	=> __( "Include Styling for CSS Hooks", 'wp-typography' ),
				"helpText" 		=> __( "Attempts to inject the CSS specified below.  If you are familiar with CSS, it is recommended you not use this option, and maintain all styles in your main stylesheet.", 'wp-typography' ),
				"control" 		=> "input",
				"inputType" 	=> "checkbox",
				"default" 		=> 1,
			),
			"typoStyleCSS" => array(
				"section"		=> "css-hooks",
				"labelBefore" 	=> __( "Styling for CSS Hooks:", 'wp-typography' ),
				"helpText" 		=> __( "This will only be applied if explicitly selected with the preceding option.", 'wp-typography' ),
				"control" 		=> "textarea",
				"default" 		=> 'sup {
	vertical-align: 60%;
	font-size: 75%;
	line-height: 100%;
}
sub {
	vertical-align: -10%;
	font-size: 75%;
	line-height: 100%;
}
.amp {
	font-family: Baskerville, "Goudy Old Style", "Palatino", "Book Antiqua", "Warnock Pro", serif;
	font-weight: normal;
	font-style: italic;
	font-size: 1.1em;
	line-height: 1em;
}
.caps {
	font-size: 90%;
}
.dquo {
	margin-left:-.40em;
}
.quo {
	margin-left:-.2em;
}
/* because formatting .numbers should consider your current font settings, we will not style it here */
'
			),
		
		);		
	}
	
/*	// removed because it caused issues for feeds
	function processBloginfo($text) {
		if( get_bloginfo( 'name' ) == $text || get_bloginfo( 'description' ) == $text) {
				return $this->process($text, true);
		}
		return $text;
	}
*/
	/**
	 * Process title text fragment.
	 * 
	 * Calls `process( $text, true )`.
	 * 
	 * @param string $text
	 */
	function process_title( $text ) {
		return $this->process($text, true);
	}

	/**
	 * Process text fragment.
	 * 
	 * @param string $text
	 * @param boolean $isTitle Default false.
	 */
	function process( $text, $isTitle = false ) {
		if ( is_feed() ) { //feed readers can be pretty stupid
			return $this->php_typo->process_feed( $text, $isTitle );
		} else {
			return $this->php_typo->process( $text, $isTitle );
		}
	}

	/**
	 * Called on plugin activation.
	 * 
	 * @param string $update Whether the standard settings should be restored. Default false.
	 */
	function register_plugin( $update = false ) {
		// grab configuration variables
		foreach ( $this->admin_form_controls as $key => $value ) {
			if ( $update || ! is_string( get_option( $key ) ) ) {
				update_option( $key, $value['default'] );
			}
		}
		
		update_option( 'typoRestoreDefaults', 0 );

		return;
	}

	/**
	 * Register admin settings.
	 */
	function register_the_settings() {
		foreach ( $this->admin_form_controls as $controlID => $control ) {
			register_setting( $this->option_group, $controlID );
		}
		register_setting( $this->option_group, 'typoRestoreDefaults' );
	}

	/**
	 * Add an options page for the plugin settings.
	 */
	function add_options_page()	{
		add_options_page( $this->plugin_name, $this->plugin_name, 'manage_options', strtolower( $this->plugin_name ), array( $this, 'get_admin_page_content' ) );
	}

	/**
	 * Add a 'Settings' link to the wp-Typography entry in the plugins list.
	 * 
	 * @param array $links An array of links.
	 * @return array An array of links.
	 */
	function add_filter_plugin_action_links( $links ) {
		$adminurl = trailingslashit( admin_url() );
		
		// Add link "Settings" to the plugin in /wp-admin/plugins.php
		$settings_link = '<a href="'.$adminurl.'options-general.php?page='.strtolower( $this->plugin_name ).'">' . __( 'Settings' , 'wp-typography') . '</a>';
		$links[] = $settings_link;
		
		return $links;
	}


	/**
	 * Display the plugin options page.
	 */
	function get_admin_page_content() {
		include_once( 'templates/settings.php' );
	}
	
	/**
	 * Create the markup for a plugin setting.
	 * 
	 * @param string $id Required. The control ID.
	 * @param string $control Required. Accepts: 'input', 'select', or 'textarea'; not implemented: 'button'. Default 'input'.
	 * @param string $input_type Optional. Used when $control is set to 'input'. Accepts: 'text', 'password', 'checkbox', 'submit', 'hidden';
	 *               not implemented: 'radio', 'image', 'reset', 'button', 'file'. Default 'text'.
	 * @param string $label_before Optional. Text displayed before the control. Default empty.
	 * @param string $label_after Optional. Text displayed after the control. Cannot be uses when $control is set to 'textarea'. Default empty.
	 * @param string $help_text Optional. Requires an accompanying label. Default empty.
	 * @param array  $option_values {
	 * 		Optional. Array of values and display strings in the form ($value => $display). Default empty.
	 * }
	 * @return string The markup for the control.
	 */
	function get_admin_form_control( $id, 
								     $control = 'input', 
									 $input_type = 'text', 
									 $label_before = null, 
									 $label_after = null, 
									 $help_text = null,
									 $option_values = null ) {
		$help_text_class = 'helpText';
		$restore_defaults_value = __( 'Restore Defaults', 'wp-typography' );
		$save_changes_value = __( 'Save Changes', 'wp-typography' );
		
		if ($input_type != 'submit') {
			$value = get_option($id);
		} elseif ($id == 'typoRestoreDefaults') {
			$value = $restore_defaults_value;
		} else {
			$value = $save_changes_value;
		}

		if ($input_type == 'checkbox') {
			$checked = '';
			if($value) $checked = 'checked="checked" ';
		}
		
		//make sure $value is in $optionValues if $optionValues is set
		if ($option_values && !isset($option_values[$value])) {
			$value = null;
		}
		
	
		if ($input_type == 'submit') {
			$control_markup = "<div class='publishing-action'>";
		} else {
			$control_markup = "<div class='control'>";
		}
		
		if (($label_before || $label_after) && $input_type != 'hidden' && $input_type != 'submit') {
			$control_markup .= "<label for='$id'>";
			if ($label_before) {
				$control_markup .= "$label_before ";
			}
			if ($control == 'textarea') {
				if ($help_text) {
					$control_markup .= "<span class='$help_text_class'>$help_text</span>";
				}
				$control_markup .= '</label>';
			}
		}
		
		$control_markup .= "<$control ";
		
		if ($control == 'input') {
			$control_markup .= "type='$input_type' ";
		}
		
		if ($input_type=='submit' && $value === $restore_defaults_value ) {
			$control_markup .= "name='$id' class='text-button'"; //to avoid duplicate ids and some pretty stylin'
		} elseif ($input_type=='submit') {
			$control_markup .= "name='$id' class='button-primary'"; //to avoid duplicate ids and some pretty stylin'
		} else {
			$control_markup .= "id='$id' name='$id' ";
		}

		if ($value && $control != 'select' && $control != 'textarea' && $input_type != 'checkbox') {
			$control_markup .= "value='$value' ";
		} elseif ($input_type == 'checkbox') {
			$control_markup .= "value='1' $checked";
		}
		
		if ($control != 'select' && $control != 'textarea') {
			$control_markup .= ' />';
		} elseif ($control == 'textarea') {
			$control_markup .= ' >';
			if ($value) {
				$control_markup .= $value;
			}
			$control_markup .= "</$control>";
		} elseif ($control == 'select') {
			$control_markup .= ' >';
			foreach ($option_values as $option_valuesalue => &$display) {
				$selected = '';
				if ($value == $$option_valuesalue) $selected = "selected='selected'";
				$control_markup .= "<option value='$$option_valuesalue' $selected>$display</option>";
			}
			$control_markup .= "</$control>";
		}
		
		if (($label_before || $label_after) && $control != 'textarea') {
			if ($label_after) {
				$control_markup .= " $label_after";
			}
			if ($help_text) {
				$control_markup .= "<span class='$help_text_class'>$help_text</span>";
			}
			$control_markup .= '</label>';
		}
		
		$control_markup .= "</div>\r\n";

		return $control_markup;
	}

	/**
	 * Print 'WordPress version incompatible' admin notice
	 */
	function admin_notices_wp_version_incompatible() { 
		global $wp_version;
		
		$this->_display_error_notice( __( 'The activated plugin %1$s requires WordPress version %2$s or later. You are running WordPress version %3$s. Please deactivate this plugin, or upgrade your installation of WordPress.', 'wp-typography' ), 
									  "<strong>{$this->plugin_name}</strong>", 
									  $this->install_requirements['WordPress Version'], 
									  $wp_version );
	}

	/**
	 * Print 'PHP version incompatible' admin notice
	 */
	function admin_notices_php_version_incompatible() { 
		$this->_display_error_notice( __( 'The activated plugin %1$s requires PHP %2$s or later. Your server is running PHP %3$s. Please deactivate this plugin, or upgrade your server\'s installation of PHP.', 'wp-typography' ),
								  	  "<strong>{$this->plugin_name}</strong>",
									  $this->install_requirements['PHP Version'],
									  phpversion() );
	}
	
	/**
	 * Print 'mbstring extension missing' admin notice
	 */
	function admin_notices_mbstring_incompatible() { 
		$this->_display_error_notice( __( 'The activated plugin %1$s requires the mbstring PHP extension to be enabled on your server. Please deactivate this plugin, or <a href="%2$s">enable the extension</a>.', 'wp-typography' ),
			"<strong>{$this->plugin_name}</strong>",
			'http://www.php.net/manual/en/mbstring.installation.php' );
	}

	/**
	 * Print 'Charset incompatible' admin notice
	 */
	function admin_notices_charset_incompatible() { 
		$this->_display_error_notice( __( 'The activated plugin %1$s requires your blog use the UTF-8 character encoding. You have set your blogs encoding to %2$s. Please deactivate this plugin, or <a href="%3$s">change your character encoding to UTF-8</a>.', 'wp-typography' ),
			"<strong>{$this->plugin_name}</strong>",
			get_bloginfo( 'charset' ),
			'/wp-admin/options-reading.php' );
	}

	/**
	 * Show an error message in the admin area.
	 * 
	 * @param string $format    An `sprintf` format string.
	 * @param mixed  $param1... An optional number of parameters for sprintf.
	 * @access private
	 */
	private function _display_error_notice($format) {
		if ( func_num_args() < 1 ) {
			return; // abort
		}
		
		$args = func_get_args();
		$format = array_shift( $args );
		
		echo '<div class="error"><p>' . vsprintf( $format, $args ) . '</p></div>'; 
	}
	
	/**
	 * Print CSS and JS depending on plugin options.
	 */
	function add_wp_head() {
		if ( $this->settings['typoStyleCSSInclude'] && trim( $this->settings['typoStyleCSS'] ) != '' ) {
			echo '<style type="text/css">'."\r\n";
			echo $this->settings['typoStyleCSS']."\r\n";
			echo "</style>\r\n";
		}
		
		if ( $this->settings['typoRemoveIE6'] ) {
			echo "<!--[if lt IE 7]>\r\n";
			echo "<script type='text/javascript'>";
			echo "function stripZWS() { document.body.innerHTML = document.body.innerHTML.replace(/\u200b/gi,''); }";
			echo "window.onload = stripZWS;";
			echo "</script>\r\n";
			echo "<![endif]-->\r\n";
		}
	}
}

