<?php

/**
 * Subclass of PHP_Typography for setting custom CSS classes.
 */
class PHP_Typography_CSS_Classes extends \PHP_Typography\PHP_Typography {

	function __construct( $set_defaults = true, $init = 'now', $css_classes = array() )	{
		parent::__construct( $set_defaults, $init );

		$this->css_classes = array_merge( $this->css_classes, $css_classes );
	}
}

/**
 *
 * @coversDefaultClass \PHP_Typography\PHP_Typography
 * @usesDefaultClass \PHP_Typography\PHP_Typography
 *
 * @uses PHP_Typography\PHP_Typography
 * @uses PHP_Typography\get_ancestors
 * @uses PHP_Typography\has_class
 * @uses PHP_Typography\nodelist_to_array
 * @uses PHP_Typography\uchr
 * @uses PHP_Typography\arrays_intersect
 * @uses PHP_Typography\is_odd
 * @uses PHP_Typography\mb_str_split
 */
class PHP_Typography_Test extends PHPUnit_Framework_TestCase
{
    /**
     * @var PHP_Typography
     */
    protected $typo;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->typo = new \PHP_Typography\PHP_Typography( false );
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * Helper function to generate a valid token list from strings.
     *
     * @param string $value
     * @param string $type Optional. Default 'word'.
     *
     * @return array
     */
    protected function tokenize( $value, $type = 'word' ) {
    	return array(
    		array(
	    		'type'  => $type,
	    		'value' => $value
    		)
    	);
    }

    /**
     *
     * @param string $expected_value
     * @param array $actual_tokens
     * @param string $message
     */
    protected function assertTokenSame( $expected_value, $actual_tokens, $message = '' ) {
    	foreach ( $actual_tokens as &$actual ) {
    		$actual['value'] = clean_html( $actual['value'] );
    	}

    	return $this->assertSame( $this->tokenize( $expected_value ) , $actual_tokens, $message );
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_defaults
     * @todo   Implement test_set_defaults().
     */
   // public function test_set_defaults()
    //{
//    }


    /**
     * @covers ::set_tags_to_ignore
     *
     * @uses \PHP_Typography\Parse_Text
     */
    public function test_set_tags_to_ignore()
    {
    	$typo = $this->typo;
    	$always_ignore = array( 'iframe', 'textarea', 'button', 'select', 'optgroup', 'option', 'map',
    							'style', 'head', 'title', 'script', 'applet', 'object', 'param' );
    	$self_closing_tags = array('area', 'base', 'basefont', 'br', 'frame', 'hr', 'img', 'input', 'link', 'meta');

    	// default tags
		$typo->set_tags_to_ignore( array( 'code', 'head', 'kbd', 'object', 'option', 'pre',	'samp',
										  'script',	'noscript',	'noembed', 'select', 'style', 'textarea',
										  'title',	'var', 'math' ) );
		$this->assertArraySubset( array( 'code', 'head', 'kbd', 'object', 'option',	'pre', 'samp',
										 'script', 'noscript', 'noembed', 'select', 'style', 'textarea',
 								 		 'title', 'var', 'math' ), $typo->settings['ignoreTags'] );
		foreach ( $always_ignore as $tag ) {
			$this->assertContains( $tag, $typo->settings['ignoreTags'] );
		}
		foreach ( $self_closing_tags as $tag ) {
			$this->assertNotContains( $tag, $typo->settings['ignoreTags'] );
		}

		// auto-close tag and something else
		$typo->set_tags_to_ignore( array( 'img', 'foo' ) );
 		$this->assertContains( 'foo', $typo->settings['ignoreTags'] );
    	foreach ( $self_closing_tags as $tag ) {
			$this->assertNotContains( $tag, $typo->settings['ignoreTags'] );
		}
		foreach ( $always_ignore as $tag ) {
			$this->assertContains( $tag, $typo->settings['ignoreTags'] );
		}

		$typo->set_tags_to_ignore( "img foo  \    " ); // should not result in an error

		$html = '<p><foo>Ignore this "quote",</foo><span class="other"> but not "this" one.</span></p>';
		$expected = '<p><foo>Ignore this "quote",</foo><span class="other"> but not &ldquo;this&rdquo; one.</span></p>';
		$this->typo->set_smart_quotes( true );
		$this->assertSame( $expected, clean_html( $typo->process( $html ) ) );
    }

    /**
     * @covers ::set_classes_to_ignore
     *
     * @uses PHP_Typography\Parse_Text
     */
    public function test_set_classes_to_ignore()
    {
		$typo = $this->typo;

		$typo->set_classes_to_ignore( 'foo bar' );
		$this->assertContains( 'foo', $this->typo->settings['ignoreClasses'] );
		$this->assertContains( 'bar', $this->typo->settings['ignoreClasses'] );

		$html = '<p><span class="foo">Ignore this "quote",</span><span class="other"> but not "this" one.</span></p>
			     <p class="bar">"This" should also be ignored. <span>And "this".</span></p>
				 <p><span>"But" not this.</span></p>';
		$expected = '<p><span class="foo">Ignore this "quote",</span><span class="other"> but not &ldquo;this&rdquo; one.</span></p>
			     <p class="bar">"This" should also be ignored. <span>And "this".</span></p>
				 <p><span>&ldquo;But&rdquo; not this.</span></p>';
		$this->typo->set_smart_quotes( true );
		$this->assertSame( $expected, clean_html( $typo->process( $html ) ) );
    }

    /**
     * @covers ::set_ids_to_ignore
     *
     * @uses PHP_Typography\Parse_Text
     */
    public function test_set_ids_to_ignore()
    {
		$typo = $this->typo;

		$typo->set_ids_to_ignore( 'foobar barfoo' );
		$this->assertContains( 'foobar', $this->typo->settings['ignoreIDs'] );
		$this->assertContains( 'barfoo', $this->typo->settings['ignoreIDs'] );

		$html = '<p><span id="foobar">Ignore this "quote",</span><span class="other"> but not "this" one.</span></p>
			     <p id="barfoo">"This" should also be ignored. <span>And "this".</span></p>
				 <p><span>"But" not this.</span></p>';
		$expected = '<p><span id="foobar">Ignore this "quote",</span><span class="other"> but not &ldquo;this&rdquo; one.</span></p>
			     <p id="barfoo">"This" should also be ignored. <span>And "this".</span></p>
				 <p><span>&ldquo;But&rdquo; not this.</span></p>';
		$this->typo->set_smart_quotes( true );
		$this->assertSame( $expected, clean_html( $typo->process( $html ) ) );
    }

    /**
     * Integrate all three "ignore" variants.
     *
	 * @covers ::set_classes_to_ignore
     * @covers ::set_ids_to_ignore
     * @covers ::set_tags_to_ignore
     * @covers ::query_tags_to_ignore
     *
     * @depends test_set_ids_to_ignore
     * @depends test_set_classes_to_ignore
     * @depends test_set_tags_to_ignore
     *
     * @uses PHP_Typography\Parse_Text
     */
    public function test_complete_ignore() {
    	$typo = $this->typo;

    	$typo->set_ids_to_ignore( 'foobar barfoo' );
    	$typo->set_classes_to_ignore( 'foo bar' );
    	$typo->set_tags_to_ignore( array( 'img', 'foo' ) );

    	$html = '<p><span class="foo">Ignore this "quote",</span><span class="other"> but not "this" one.</span></p>
			     <p class="bar">"This" should also be ignored. <span>And "this".</span></p>
				 <p><span>"But" not this.</span></p>';
    	$expected = '<p><span class="foo">Ignore this "quote",</span><span class="other"> but not &ldquo;this&rdquo; one.</span></p>
			     <p class="bar">"This" should also be ignored. <span>And "this".</span></p>
				 <p><span>&ldquo;But&rdquo; not this.</span></p>';
    	$typo->set_smart_quotes( true );
    	$this->assertSame( $expected, clean_html( $typo->process( $html ) ) );
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_smart_quotes
     */
    public function test_set_smart_quotes()
    {
		$this->typo->set_smart_quotes( true );
		$this->assertTrue( $this->typo->settings['smartQuotes'] );

		$this->typo->set_smart_quotes( false );
		$this->assertFalse( $this->typo->settings['smartQuotes'] );
    }

    /**
     * @covers ::set_smart_quotes_primary
     */
    public function test_set_smart_quotes_primary()
    {
        $typo = $this->typo;
    	$quote_styles = array(
    		'doubleCurled',
    		'doubleCurledReversed',
    		'doubleLow9',
    		'doubleLow9Reversed',
    		'singleCurled',
    		'singleCurledReversed',
    		'singleLow9',
    		'singleLow9Reversed',
    		'doubleGuillemetsFrench',
    		'doubleGuillemets',
    		'doubleGuillemetsReversed',
    		'singleGuillemets',
    		'singleGuillemetsReversed',
    		'cornerBrackets',
    		'whiteCornerBracket'
    	);

    	foreach ( $quote_styles as $style ) {
    		$typo->set_smart_quotes_primary( $style );
    		$this->assertSmartQuotesStyle( $style, $typo->chr['doubleQuoteOpen'], $typo->chr['doubleQuoteClose'] );
    	}
    }

    /**
     * @covers ::set_smart_quotes_primary
     *
     * @expectedException PHPUnit_Framework_Error_Warning
     * @expectedExceptionMessageRegExp /^Invalid quote style \w+\.$/
     */
    public function test_set_smart_quotes_primary_invalid()
    {
    	$typo = $this->typo;

    	$typo->set_smart_quotes_primary( 'invalidStyleName' );
    }

    /**
     * @covers ::set_smart_quotes_secondary
     */
    public function test_set_smart_quotes_secondary()
    {
    	$typo = $this->typo;
    	$quote_styles = array(
    		'doubleCurled',
    		'doubleCurledReversed',
    		'doubleLow9',
    		'doubleLow9Reversed',
    		'singleCurled',
    		'singleCurledReversed',
    		'singleLow9',
    		'singleLow9Reversed',
    		'doubleGuillemetsFrench',
    		'doubleGuillemets',
    		'doubleGuillemetsReversed',
    		'singleGuillemets',
    		'singleGuillemetsReversed',
    		'cornerBrackets',
    		'whiteCornerBracket'
    	);

    	foreach ( $quote_styles as $style ) {
    		$typo->set_smart_quotes_secondary( $style );
    		$this->assertSmartQuotesStyle( $style, $typo->chr['singleQuoteOpen'], $typo->chr['singleQuoteClose'] );
    	}
    }

    /**
     * @covers ::set_smart_quotes_secondary
     *
     * @expectedException PHPUnit_Framework_Error_Warning
     * @expectedExceptionMessageRegExp /^Invalid quote style \w+\.$/
     */
    public function test_set_smart_quotes_secondary_invalid()
    {
    	$typo = $this->typo;

    	$typo->set_smart_quotes_secondary( 'invalidStyleName' );
    }

    /**
     * @covers ::update_smart_quotes_brackets
     *
     * @uses ::set_smart_quotes_primary
     * @uses ::set_smart_quotes_secondary
     * @uses PHP_Typography\mb_str_split
     */
    public function test_update_smart_quotes_brackets()
    {
    	$typo = $this->typo;
    	$quote_styles = array(
    		'doubleCurled',
    		'doubleCurledReversed',
    		'doubleLow9',
    		'doubleLow9Reversed',
    		'singleCurled',
    		'singleCurledReversed',
    		'singleLow9',
    		'singleLow9Reversed',
    		// 'doubleGuillemetsFrench', // test doesn't work for this because it's actually two characters
    		'doubleGuillemets',
    		'doubleGuillemetsReversed',
    		'singleGuillemets',
    		'singleGuillemetsReversed',
    		'cornerBrackets',
    		'whiteCornerBracket'
    	);

    	foreach ( $quote_styles as $primary_style ) {
    		$typo->set_smart_quotes_primary( $primary_style );

    		foreach ( $quote_styles as $secondary_style ) {
    			$typo->set_smart_quotes_secondary( $secondary_style );

    			$comp = PHPUnit_Framework_Assert::readAttribute( $typo, 'components' );

    			$this->assertSmartQuotesStyle( $secondary_style,
    										   \PHP_Typography\mb_str_split( $comp['smartQuotesBrackets']["['"] )[1],
    										   \PHP_Typography\mb_str_split( $comp['smartQuotesBrackets']["']"] )[0] );
    			$this->assertSmartQuotesStyle( $secondary_style,
    				                           \PHP_Typography\mb_str_split( $comp['smartQuotesBrackets']["('"] )[1],
    				                           \PHP_Typography\mb_str_split( $comp['smartQuotesBrackets']["')"] )[0] );
    			$this->assertSmartQuotesStyle( $secondary_style,
     				                           \PHP_Typography\mb_str_split( $comp['smartQuotesBrackets']["{'"] )[1],
    				                           \PHP_Typography\mb_str_split( $comp['smartQuotesBrackets']["'}"] )[0] );
    			$this->assertSmartQuotesStyle( $secondary_style,
    				                           \PHP_Typography\mb_str_split( $comp['smartQuotesBrackets']["\"'"] )[1],
    				                           \PHP_Typography\mb_str_split( $comp['smartQuotesBrackets']["'\""] )[0] );

    			$this->assertSmartQuotesStyle( $primary_style,
    				                           \PHP_Typography\mb_str_split( $comp['smartQuotesBrackets']["[\""] )[1],
    				                           \PHP_Typography\mb_str_split( $comp['smartQuotesBrackets']["\"]"] )[0] );
    			$this->assertSmartQuotesStyle( $primary_style,
    				                           \PHP_Typography\mb_str_split( $comp['smartQuotesBrackets']["(\""] )[1],
    				                           \PHP_Typography\mb_str_split( $comp['smartQuotesBrackets']["\")"] )[0] );
    			$this->assertSmartQuotesStyle( $primary_style,
    				                           \PHP_Typography\mb_str_split( $comp['smartQuotesBrackets']["{\""] )[1],
    				                           \PHP_Typography\mb_str_split( $comp['smartQuotesBrackets']["\"}"] )[0] );
    			$this->assertSmartQuotesStyle( $primary_style,
    				                           \PHP_Typography\mb_str_split( $comp['smartQuotesBrackets']["\"'"] )[0],
    				                           \PHP_Typography\mb_str_split( $comp['smartQuotesBrackets']["'\""] )[1] );
    		}
    	}
    }

    /**
     * Assert that the given quote styles match
     *
     * @param string $style
     * @param string $open
     * @param string $close
     */
	private function assertSmartQuotesStyle( $style, $open, $close ) {
    	switch ( $style ) {
    		case 'doubleCurled':
    			$this->assertSame( \PHP_Typography\uchr(8220), $open, "Opening quote $open did not match quote style $style." );
    			$this->assertSame( \PHP_Typography\uchr(8221), $close, "Closeing quote $close did not match quote style $style." );
    			break;

    		case 'doubleCurledReversed':
    			$this->assertSame( \PHP_Typography\uchr(8221), $open,  "Opening quote $open did not match quote style $style." );
    			$this->assertSame( \PHP_Typography\uchr(8221), $close, "Closeing quote $close did not match quote style $style." );
    			break;

    		case 'doubleLow9':
    			$this->assertSame( \PHP_Typography\uchr(8222), $open, "Opening quote $open did not match quote style $style." );
    			$this->assertSame( \PHP_Typography\uchr(8221), $close, "Closeing quote $close did not match quote style $style." );
    			break;

    		case 'doubleLow9Reversed':
    			$this->assertSame( \PHP_Typography\uchr(8222), $open, "Opening quote $open did not match quote style $style." );
    			$this->assertSame( \PHP_Typography\uchr(8220), $close, "Closeing quote $close did not match quote style $style." );
    			break;

    		case 'singleCurled':
    			$this->assertSame( \PHP_Typography\uchr(8216), $open, "Opening quote $open did not match quote style $style." );
    			$this->assertSame( \PHP_Typography\uchr(8217), $close, "Closeing quote $close did not match quote style $style." );
    			break;

    		case 'singleCurledReversed':
    			$this->assertSame( \PHP_Typography\uchr(8217), $open, "Opening quote $open did not match quote style $style." );
    			$this->assertSame( \PHP_Typography\uchr(8217), $close, "Closeing quote $close did not match quote style $style." );
    			break;

    		case 'singleLow9':
    			$this->assertSame( \PHP_Typography\uchr(8218), $open,  "Opening quote $open did not match quote style $style." );
    			$this->assertSame( \PHP_Typography\uchr(8217), $close, "Closeing quote $close did not match quote style $style." );
    			break;

    		case 'singleLow9Reversed':
    			$this->assertSame( \PHP_Typography\uchr(8218), $open, "Opening quote $open did not match quote style $style." );
    			$this->assertSame( \PHP_Typography\uchr(8216), $close, "Closeing quote $close did not match quote style $style." );
    			break;

    		case 'doubleGuillemetsFrench':
    			$this->assertSame( \PHP_Typography\uchr(171) . \PHP_Typography\uchr(160), $open, "Opening quote $open did not match quote style $style." );
    			$this->assertSame( \PHP_Typography\uchr(160) . \PHP_Typography\uchr(187), $close, "Closeing quote $close did not match quote style $style." );
    			break;

    		case 'doubleGuillemets':
    			$this->assertSame( \PHP_Typography\uchr(171), $open, "Opening quote $open did not match quote style $style." );
    			$this->assertSame( \PHP_Typography\uchr(187), $close, "Closeing quote $close did not match quote style $style." );
    			break;

    		case 'doubleGuillemetsReversed':
    			$this->assertSame( \PHP_Typography\uchr(187), $open, "Opening quote $open did not match quote style $style." );
    			$this->assertSame( \PHP_Typography\uchr(171), $close, "Closeing quote $close did not match quote style $style." );
    			break;

    		case 'singleGuillemets':
    			$this->assertSame( \PHP_Typography\uchr(8249), $open, "Opening quote $open did not match quote style $style." );
    			$this->assertSame( \PHP_Typography\uchr(8250), $close, "Closeing quote $close did not match quote style $style." );
    			break;

   			case 'singleGuillemetsReversed':
   				$this->assertSame( \PHP_Typography\uchr(8250), $open, "Opening quote $open did not match quote style $style." );
   				$this->assertSame( \PHP_Typography\uchr(8249), $close, "Closeing quote $close did not match quote style $style." );
   				break;

   			case 'cornerBrackets':
   				$this->assertSame( \PHP_Typography\uchr(12300), $open, "Opening quote $open did not match quote style $style." );
   				$this->assertSame( \PHP_Typography\uchr(12301), $close, "Closeing quote $close did not match quote style $style." );
   				break;

   			case 'whiteCornerBracket':
   				$this->assertSame( \PHP_Typography\uchr(12302), $open, "Opening quote $open did not match quote style $style." );
   				$this->assertSame( \PHP_Typography\uchr(12303), $close, "Closeing quote $close did not match quote style $style." );
   				break;

    		default:
    			$this->assertTrue( false, "Invalid quote style $style." );
    	}
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_smart_dashes
     */
    public function test_set_smart_dashes()
    {
		$this->typo->set_smart_dashes( true );
		$this->assertTrue( $this->typo->settings['smartDashes'] );

		$this->typo->set_smart_dashes( false );
		$this->assertFalse( $this->typo->settings['smartDashes'] );
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_smart_dashes_style
     */
    public function test_set_smart_dashes_style()
    {
		$typo = $this->typo;

		$typo->set_smart_dashes_style( 'traditionalUS' );
		$this->assertEquals( $typo->chr['emDash'], $typo->chr['parentheticalDash'] );
		$this->assertEquals( $typo->chr['enDash'], $typo->chr['intervalDash'] );
		$this->assertEquals( $typo->chr['thinSpace'], $typo->chr['parentheticalDashSpace'] );
		$this->assertEquals( $typo->chr['thinSpace'], $typo->chr['intervalDashSpace'] );

		$typo->set_smart_dashes_style( 'international' );
		$this->assertEquals( $typo->chr['enDash'], $typo->chr['parentheticalDash'] );
		$this->assertEquals( $typo->chr['enDash'], $typo->chr['intervalDash'] );
		$this->assertEquals( ' ', $typo->chr['parentheticalDashSpace'] );
		$this->assertEquals( $typo->chr['hairSpace'], $typo->chr['intervalDashSpace'] );
    }

    /**
     * @covers ::set_smart_dashes_style
     *
     * @expectedException PHPUnit_Framework_Error_Warning
     * @expectedExceptionMessageRegExp /^Invalid dash style \w+.$/
     */
    public function test_set_smart_dashes_style_invalid()
    {
    	$typo = $this->typo;

    	$typo->set_smart_dashes_style( 'invalidStyleName' );
    }

    /**
     * @covers ::set_smart_ellipses
     */
    public function test_set_smart_ellipses()
    {
		$this->typo->set_smart_ellipses( true );
		$this->assertTrue( $this->typo->settings['smartEllipses'] );

		$this->typo->set_smart_ellipses( false );
		$this->assertFalse( $this->typo->settings['smartEllipses'] );
    }

    /**
     * @covers ::set_smart_diacritics
     */
    public function test_set_smart_diacritics()
    {
		$this->typo->set_smart_diacritics( true );
		$this->assertTrue( $this->typo->settings['smartDiacritics'] );

		$this->typo->set_smart_diacritics( false );
		$this->assertFalse( $this->typo->settings['smartDiacritics'] );
    }

    /**
     * @covers ::set_diacritic_language
	 * @covers ::update_diacritics_replacement_arrays
     */
    public function test_set_diacritic_language()
    {
		$this->typo->set_diacritic_language( 'en-US' );
		$this->assertGreaterThan( 0, count( $this->typo->settings['diacriticWords'] ) );

		$this->typo->set_diacritic_language( 'foobar' );
		$this->assertFalse( isset( $this->typo->settings['diacriticWords'] ) );

		$this->typo->set_diacritic_language( 'de-DE' );
		$this->assertTrue( isset( $this->typo->settings['diacriticWords'] ) );
		$this->assertGreaterThan( 0, count( $this->typo->settings['diacriticWords'] ) );

		// nothing changed since the last call
		$this->typo->set_diacritic_language( 'de-DE' );
		$this->assertTrue( isset( $this->typo->settings['diacriticWords'] ) );
		$this->assertGreaterThan( 0, count( $this->typo->settings['diacriticWords'] ) );
    }

    /**
     * @covers ::set_diacritic_custom_replacements
     * @covers ::update_diacritics_replacement_arrays
     */
    public function test_set_diacritic_custom_replacements()
    {
    	$typo = $this->typo;

    	$typo->set_diacritic_custom_replacements( '"foo" => "fóò", "bar" => "bâr"' . ", 'ha' => 'hä'" );
     	$this->assertArrayHasKey( 'foo', $typo->settings['diacriticCustomReplacements'] );
     	$this->assertArrayHasKey( 'bar', $typo->settings['diacriticCustomReplacements'] );
     	$this->assertArrayHasKey( 'ha', $typo->settings['diacriticCustomReplacements'] );
     	$this->assertContains( 'fóò', $typo->settings['diacriticCustomReplacements'] );
     	$this->assertContains( 'bâr', $typo->settings['diacriticCustomReplacements'] );
     	$this->assertContains( 'hä', $typo->settings['diacriticCustomReplacements'] );

     	$typo->set_diacritic_custom_replacements( array( 'fööbar' => 'fúbar' ) );
     	$this->assertArrayNotHasKey( 'foo', $typo->settings['diacriticCustomReplacements'] );
     	$this->assertArrayNotHasKey( 'bar', $typo->settings['diacriticCustomReplacements'] );
     	$this->assertArrayHasKey( 'fööbar', $typo->settings['diacriticCustomReplacements'] );
     	$this->assertContains( 'fúbar', $typo->settings['diacriticCustomReplacements'] );
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_smart_marks
     */
    public function test_set_smart_marks()
    {
		$this->typo->set_smart_marks( true );
		$this->assertTrue( $this->typo->settings['smartMarks'] );

		$this->typo->set_smart_marks( false );
		$this->assertFalse( $this->typo->settings['smartMarks'] );
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_smart_math
     */
    public function test_set_smart_math()
    {
		$this->typo->set_smart_math( true );
		$this->assertTrue( $this->typo->settings['smartMath'] );

		$this->typo->set_smart_math( false );
		$this->assertFalse( $this->typo->settings['smartMath'] );
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_smart_exponents
     */
    public function test_set_smart_exponents()
    {
		$this->typo->set_smart_exponents( true );
		$this->assertTrue( $this->typo->settings['smartExponents'] );

		$this->typo->set_smart_exponents( false );
		$this->assertFalse( $this->typo->settings['smartExponents'] );
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_smart_fractions
     */
    public function test_set_smart_fractions()
    {
		$this->typo->set_smart_fractions( true );
		$this->assertTrue( $this->typo->settings['smartFractions'] );

		$this->typo->set_smart_fractions( false );
		$this->assertFalse( $this->typo->settings['smartFractions'] );
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_smart_ordinal_suffix
     */
    public function test_set_smart_ordinal_suffix()
    {
		$this->typo->set_smart_ordinal_suffix( true );
		$this->assertTrue( $this->typo->settings['smartOrdinalSuffix'] );

		$this->typo->set_smart_ordinal_suffix( false );
		$this->assertFalse( $this->typo->settings['smartOrdinalSuffix'] );
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_single_character_word_spacing
     */
    public function test_set_single_character_word_spacing()
    {
		$this->typo->set_single_character_word_spacing( true );
		$this->assertTrue( $this->typo->settings['singleCharacterWordSpacing'] );

		$this->typo->set_single_character_word_spacing( false );
		$this->assertFalse( $this->typo->settings['singleCharacterWordSpacing'] );
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_fraction_spacing
     */
    public function test_set_fraction_spacing()
    {
		$this->typo->set_fraction_spacing( true );
		$this->assertTrue( $this->typo->settings['fractionSpacing'] );

		$this->typo->set_fraction_spacing( false );
		$this->assertFalse( $this->typo->settings['fractionSpacing'] );
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_unit_spacing
     */
    public function test_set_unit_spacing()
    {
		$this->typo->set_unit_spacing( true );
		$this->assertTrue( $this->typo->settings['unitSpacing'] );

		$this->typo->set_unit_spacing( false );
		$this->assertFalse( $this->typo->settings['unitSpacing'] );
    }

        /**
     * @covers ::set_french_punctuation_spacing
     */
    public function test_set_french_punctuation_spacing()
    {
    	$this->typo->set_french_punctuation_spacing( true );
    	$this->assertTrue( $this->typo->settings['frenchPunctuationSpacing'] );

    	$this->typo->set_french_punctuation_spacing( false );
    	$this->assertFalse( $this->typo->settings['frenchPunctuationSpacing'] );
    }

    /**
     * @covers ::set_units
     * @covers ::update_unit_pattern
     */
    public function test_set_units()
    {
    	$units_as_array = array( 'foo', 'bar', 'xx/yy');
    	$units_as_string = implode( ', ', $units_as_array );

		$this->typo->set_units( $units_as_array );
		foreach( $units_as_array as $unit ) {
			$this->assertContains( $unit, $this->typo->settings['units'] );
		}

		$this->typo->set_units( array() );
		foreach( $units_as_array as $unit ) {
			$this->assertNotContains( $unit, $this->typo->settings['units'] );
		}

		$this->typo->set_units( $units_as_string );
		foreach( $units_as_array as $unit ) {
			$this->assertContains( $unit, $this->typo->settings['units'] );
		}
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_dash_spacing
     */
    public function test_set_dash_spacing()
    {
		$this->typo->set_dash_spacing( true );
		$this->assertTrue( $this->typo->settings['dashSpacing'] );

		$this->typo->set_dash_spacing( false );
		$this->assertFalse( $this->typo->settings['dashSpacing'] );
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_space_collapse
     */
    public function test_set_space_collapse()
    {
		$this->typo->set_space_collapse( true );
		$this->assertTrue( $this->typo->settings['spaceCollapse'] );

		$this->typo->set_space_collapse( false );
		$this->assertFalse( $this->typo->settings['spaceCollapse'] );
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_dewidow
     */
    public function test_set_dewidow()
    {
		$this->typo->set_dewidow( true );
		$this->assertTrue( $this->typo->settings['dewidow'] );

		$this->typo->set_dewidow( false );
		$this->assertFalse( $this->typo->settings['dewidow'] );
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_max_dewidow_length
     */
    public function test_set_max_dewidow_length()
    {
		$this->typo->set_max_dewidow_length( 10 );
		$this->assertSame( 10, $this->typo->settings['dewidowMaxLength'] );

		$this->typo->set_max_dewidow_length( 1 );
		$this->assertSame( 5, $this->typo->settings['dewidowMaxLength'] );

		$this->typo->set_max_dewidow_length( 2 );
		$this->assertSame( 2, $this->typo->settings['dewidowMaxLength'] );
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_max_dewidow_pull
     */
    public function test_set_max_dewidow_pull()
    {
		$this->typo->set_max_dewidow_pull( 10 );
		$this->assertSame( 10, $this->typo->settings['dewidowMaxPull'] );

		$this->typo->set_max_dewidow_pull( 1 );
		$this->assertSame( 5, $this->typo->settings['dewidowMaxPull'] );

		$this->typo->set_max_dewidow_pull( 2 );
		$this->assertSame( 2, $this->typo->settings['dewidowMaxPull'] );
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_wrap_hard_hyphens
     */
    public function test_set_wrap_hard_hyphens()
    {
		$this->typo->set_wrap_hard_hyphens( true );
		$this->assertTrue( $this->typo->settings['hyphenHardWrap'] );

		$this->typo->set_wrap_hard_hyphens( false );
		$this->assertFalse( $this->typo->settings['hyphenHardWrap'] );
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_url_wrap
     */
    public function test_set_url_wrap()
    {
		$this->typo->set_url_wrap( true );
		$this->assertTrue( $this->typo->settings['urlWrap'] );

		$this->typo->set_url_wrap( false );
		$this->assertFalse( $this->typo->settings['urlWrap'] );
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_email_wrap
     */
    public function test_set_email_wrap()
    {
		$this->typo->set_email_wrap( true );
		$this->assertTrue( $this->typo->settings['emailWrap'] );

		$this->typo->set_email_wrap( false );
		$this->assertFalse( $this->typo->settings['emailWrap'] );
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_min_after_url_wrap
     */
    public function test_set_min_after_url_wrap()
    {
		$this->typo->set_min_after_url_wrap( 10 );
		$this->assertSame( 10, $this->typo->settings['urlMinAfterWrap'] );

		$this->typo->set_min_after_url_wrap( 0 );
		$this->assertSame( 5, $this->typo->settings['urlMinAfterWrap'] );

		$this->typo->set_min_after_url_wrap( 1 );
		$this->assertSame( 1, $this->typo->settings['urlMinAfterWrap'] );
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_style_ampersands
     */
    public function test_set_style_ampersands()
    {
		$this->typo->set_style_ampersands( true );
		$this->assertTrue( $this->typo->settings['styleAmpersands'] );

		$this->typo->set_style_ampersands( false );
		$this->assertFalse( $this->typo->settings['styleAmpersands'] );
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_style_caps
     */
    public function test_set_style_caps()
    {
		$this->typo->set_style_caps( true );
		$this->assertTrue( $this->typo->settings['styleCaps'] );

		$this->typo->set_style_caps( false );
		$this->assertFalse( $this->typo->settings['styleCaps'] );
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_style_initial_quotes
     */
    public function test_set_style_initial_quotes()
    {
		$this->typo->set_style_initial_quotes( true );
		$this->assertTrue( $this->typo->settings['styleInitialQuotes'] );

		$this->typo->set_style_initial_quotes( false );
		$this->assertFalse( $this->typo->settings['styleInitialQuotes'] );
    }

    /**
     * @covers ::set_style_numbers
     */
    public function test_set_style_numbers()
    {
		$this->typo->set_style_numbers( true );
		$this->assertTrue( $this->typo->settings['styleNumbers'] );

		$this->typo->set_style_numbers( false );
		$this->assertFalse( $this->typo->settings['styleNumbers'] );
    }

    /**
     * @covers ::set_style_hanging_punctuation
     */
    public function test_set_style_hanging_punctuation()
    {
    	$this->typo->set_style_hanging_punctuation( true );
    	$this->assertTrue( $this->typo->settings['styleHangingPunctuation'] );

    	$this->typo->set_style_hanging_punctuation( false );
    	$this->assertFalse( $this->typo->settings['styleHangingPunctuation'] );
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_initial_quote_tags
     */
    public function test_set_initial_quote_tags()
    {
       	$tags_as_array = array( 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'blockquote', 'div' );
    	$tags_as_string = implode( ', ', $tags_as_array );

		$this->typo->set_initial_quote_tags( $tags_as_array );
		foreach( $tags_as_array as $tag ) {
			$this->assertArrayHasKey( $tag, $this->typo->settings['initialQuoteTags'] );
		}

		$this->typo->set_initial_quote_tags( array() );
		foreach( $tags_as_array as $tag ) {
			$this->assertArrayNotHasKey( $tag, $this->typo->settings['initialQuoteTags'] );
		}

		$this->typo->set_initial_quote_tags( $tags_as_string );
		foreach( $tags_as_array as $tag ) {
			$this->assertArrayHasKey( $tag, $this->typo->settings['initialQuoteTags'] );
		}
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::set_hyphenation
     */
    public function test_set_hyphenation()
    {
		$this->typo->set_hyphenation( true );
		$this->assertTrue( $this->typo->settings['hyphenation'] );

		$this->typo->set_hyphenation( false );
		$this->assertFalse( $this->typo->settings['hyphenation'] );
    }

    public function provide_hyphenation_language_data() {
    	return array(
    		array( 'en-US',  true ),
    		array( 'foobar', false ),
    		array( 'no',     true ),
    		array( 'de',     true ),
    	);
    }

    /**
     * @covers ::set_hyphenation_language
     *
     * @uses PHP_Typography\Hyphenator::__construct
     * @uses PHP_Typography\Hyphenator::set_language
     *
     * @dataProvider provide_hyphenation_language_data
     */
    public function test_set_hyphenation_language( $lang, $success )
    {
    	$typo = $this->typo;
    	$typo->settings['hyphenationExceptions'] = array(); // necessary for full coverage

		$typo->set_hyphenation_language( $lang );

    	// if the hyphenator object has not instantiated yet, hyphenLanguage will be set nonetheless
    	if ( $success || ! isset( $typo->hyphenator ) ) {
			$this->assertSame( $lang, $typo->settings['hyphenLanguage'] );
		} else {
			$this->assertFalse( isset( $typo->settings['hyphenLanguage'] ) );
		}
    }

    /**
     * @covers ::set_hyphenation_language
     *
     * @uses PHP_Typography\Hyphenator::__construct
     * @uses PHP_Typography\Hyphenator::set_language
     * @uses PHP_Typography\Hyphenator::build_trie
     *
     * @dataProvider provide_hyphenation_language_data
     */
    public function test_set_hyphenation_language_again( $lang, $success )
    {
    	$typo = $this->typo;
    	$typo->settings['hyphenationExceptions'] = array(); // necessary for full coverage

    	for ( $i = 0; $i < 2; ++$i ) {
	    	$typo->set_hyphenation_language( $lang );

	    	// if the hyphenator object has not instantiated yet, hyphenLanguage will be set nonetheless
	    	if ( $success ) {
	    		$this->assertSame( $lang, $typo->settings['hyphenLanguage'], "Round $i, success" );
	    	} elseif ( ! isset( $typo->hyphenator ) ) {
	    		$this->assertSame( $lang, $typo->settings['hyphenLanguage'], "Round $i, no hyphenator" );
	    		// Clear hyphenation language if there was no hypehnator object.
	    		unset( $typo->settings['hyphenLanguage'] );
 	    	} else {
 	    		$this->assertFalse( isset( $typo->settings['hyphenLanguage'] ), "Round $i, unsuccessful" );
 	    	}

 	    	$typo->get_hyphenator(); // Provide the second iteration with an instantiated hyphenator object.
    	}
    }


    /**
     * @covers ::set_min_length_hyphenation
     *
     * @uses PHP_Typography\Hyphenator::__construct
     * @uses PHP_Typography\Hyphenator::set_min_length
     */
    public function test_set_min_length_hyphenation()
    {
		$this->typo->set_min_length_hyphenation( 1 ); // too low, resets to default 5
		$this->assertSame( 5, $this->typo->settings['hyphenMinLength'] );

		$this->typo->set_min_length_hyphenation( 2 );
		$this->assertSame( 2, $this->typo->settings['hyphenMinLength'] );

		$this->typo->get_hyphenator();
		$this->typo->set_min_length_hyphenation( 66 );
		$this->assertSame( 66, $this->typo->settings['hyphenMinLength'] );
    }

    /**
     * @covers ::set_min_before_hyphenation
     *
     * @uses PHP_Typography\Hyphenator::__construct
     * @uses PHP_Typography\Hyphenator::set_min_before
     */
    public function test_set_min_before_hyphenation()
    {
		$this->typo->set_min_before_hyphenation( 0 ); // too low, resets to default 3
		$this->assertSame( 3, $this->typo->settings['hyphenMinBefore'] );

		$this->typo->set_min_before_hyphenation( 1 );
		$this->assertSame( 1, $this->typo->settings['hyphenMinBefore'] );

		$this->typo->get_hyphenator();
		$this->typo->set_min_before_hyphenation( 66 );
		$this->assertSame( 66, $this->typo->settings['hyphenMinBefore'] );

    }

    /**
     * @covers ::set_min_after_hyphenation
     *
     * @uses PHP_Typography\Hyphenator::__construct
     * @uses PHP_Typography\Hyphenator::set_min_after
     */
    public function test_set_min_after_hyphenation()
    {
		$this->typo->set_min_after_hyphenation( 0 ); // too low, resets to default 2
		$this->assertSame( 2, $this->typo->settings['hyphenMinAfter'] );

		$this->typo->set_min_after_hyphenation( 1 );
		$this->assertSame( 1, $this->typo->settings['hyphenMinAfter'] );

		$this->typo->get_hyphenator();
		$this->typo->set_min_after_hyphenation( 66 );
		$this->assertSame( 66, $this->typo->settings['hyphenMinAfter'] );
    }

    /**
     * @covers ::set_hyphenate_headings
     */
    public function test_set_hyphenate_headings()
    {
    	$this->typo->set_hyphenate_headings( true );
		$this->assertTrue( $this->typo->settings['hyphenateTitle'] );

		$this->typo->set_hyphenate_headings( false );
		$this->assertFalse( $this->typo->settings['hyphenateTitle'] );
    }

    /**
     * @covers ::set_hyphenate_all_caps
     */
    public function test_set_hyphenate_all_caps()
    {
    	$this->typo->set_hyphenate_all_caps( true );
		$this->assertTrue( $this->typo->settings['hyphenateAllCaps'] );

		$this->typo->set_hyphenate_all_caps( false );
		$this->assertFalse( $this->typo->settings['hyphenateAllCaps'] );
    }

    /**
     * @covers ::set_hyphenate_title_case
     */
    public function test_set_hyphenate_title_case()
    {
    	$this->typo->set_hyphenate_title_case( true );
		$this->assertTrue( $this->typo->settings['hyphenateTitleCase'] );

		$this->typo->set_hyphenate_title_case( false );
		$this->assertFalse( $this->typo->settings['hyphenateTitleCase'] );
    }

    /**
     * @covers ::set_hyphenate_compounds
     */
    public function test_set_hyphenate_compounds()
    {
    	$this->typo->set_hyphenate_compounds( true );
    	$this->assertTrue( $this->typo->settings['hyphenateCompounds'] );

    	$this->typo->set_hyphenate_compounds( false );
    	$this->assertFalse( $this->typo->settings['hyphenateCompounds'] );
    }

    /**
     * @covers ::set_hyphenation_exceptions
     *
     * @uses PHP_Typography\Hyphenator::__construct
     * @uses PHP_Typography\Hyphenator::set_custom_exceptions
     */
    public function test_set_hyphenation_exceptions_array()
    {
 		$typo = $this->typo;

 		$exceptions = array( "Hu-go", "Fö-ba-ß" );
 		$typo->set_hyphenation_exceptions( $exceptions );
 		$this->assertContainsOnly( 'string', $typo->settings['hyphenationCustomExceptions'] );
 		$this->assertCount( 2, $typo->settings['hyphenationCustomExceptions'] );

 		$this->typo->get_hyphenator();
 		$exceptions = array( "bar-foo" );
 		$typo->set_hyphenation_exceptions( $exceptions );
 		$this->assertContainsOnly( 'string', $typo->settings['hyphenationCustomExceptions'] );
 		$this->assertCount( 1, $typo->settings['hyphenationCustomExceptions'] );
    }

    /**
     * @covers ::set_hyphenation_exceptions
     *
     * @uses PHP_Typography\Hyphenator::__construct
     * @uses PHP_Typography\Hyphenator::set_custom_exceptions
     */
    public function test_set_hyphenation_exceptions_string()
    {
    	$typo = $this->typo;
    	$exceptions = "Hu-go, Fö-ba-ß";

    	$typo->set_hyphenation_exceptions( $exceptions );
    	$this->assertContainsOnly( 'string', $typo->settings['hyphenationCustomExceptions'] );
    	$this->assertCount( 2, $typo->settings['hyphenationCustomExceptions'] );
    }

    /**
     * @covers ::get_hyphenation_languages
     *
     * @uses PHP_Typography\get_language_plugin_list
     */
    public function test_get_hyphenation_languages()
    {
    	$expected = array( 'bg', 'ca', 'cs', 'cy', 'da', 'de', 'el-Mono', 'el-Poly', 'en-GB', 'en-US',
    					   'es', 'et', 'eu', 'fi', 'fr', 'ga', 'gl', 'grc', 'hr', 'hu', 'ia', 'id', 'is',
    					   'it', 'la', 'lt', 'mn-Cyrl', 'no', 'pl', 'pt', 'ro', 'ru', 'sa', 'sh-Cyrl', 'sh-Latn',
    					   'sk', 'sl', 'sr-Cyrl', 'sv', 'tr', 'uk', 'zh-Latn' );
    	$not_expected = array( 'klingon', 'de-DE' );

    	$actual = $this->typo->get_hyphenation_languages();
		foreach( $expected as $lang_code ) {
			$this->assertArrayHasKey( $lang_code, $actual );
		}
		foreach( $not_expected as $lang_code ) {
			$this->assertArrayNotHasKey( $lang_code, $actual );
		}
    }

    /**
     * @covers ::get_diacritic_languages
     *
     * @uses PHP_Typography\get_language_plugin_list
     */
    public function test_get_diacritic_languages()
    {
       	$expected = array( 'de-DE', 'en-US' );
       	$not_expected = array( 'es', 'et', 'eu', 'fi', 'fr', 'ga', 'gl', 'grc', 'hr', 'hu', 'ia', 'id', 'is',
    					       'it', 'la', 'lt', 'mn-Cyrl', 'no', 'pl', 'pt', 'ro', 'ru', 'sa', 'sh-Cyrl', 'sh-Latn',
    					   	   'sk', 'sl', 'sr-Cyrl', 'sv', 'tr', 'uk', 'zh-Latn' );

       	$actual = $this->typo->get_diacritic_languages();
		foreach( $expected as $lang_code ) {
			$this->assertArrayHasKey( $lang_code, $actual );
		}
		foreach( $not_expected as $lang_code ) {
			$this->assertArrayNotHasKey( $lang_code, $actual );
		}
    }

    public function provide_process_data() {
    	return array(
    		array( '3*3=3^2', '<span class="numbers">3</span>&times;<span class="numbers">3</span>=<span class="numbers">3</span><sup><span class="numbers">2</span></sup>', false  ), // smart math
    		array( '"Hey there!"', '<span class="pull-double">&ldquo;</span>Hey there!&rdquo;', '&ldquo;Hey there!&rdquo;' ), // smart quotes
    		array( 'Hey - there', 'Hey&thinsp;&mdash;&thinsp;there', 'Hey &mdash; there' ), // smart dashes
    		array( 'Hey...', 'Hey&hellip;', true ), // smart ellipses
    		array( '(c)', '&copy;', true ), // smart marks
    		array( 'creme', 'cr&egrave;me', false ), // diacritics
    		array( 'a a a', 'a a&nbsp;a', false ), // single characgter word spacing
    		array( '3 cm', '<span class="numbers">3</span>&nbsp;cm', false ), // unit spacing without true no-break narrow space
    		array( 'a/b', 'a/&#8203;b', false ), // dash spacing
    		array( '<span class="numbers">5</span>', '<span class="numbers">5</span>', true ), // class present, no change
    		array( '1st', '<span class="numbers">1</span><sup class="ordinal">st</sup>', false ), // smart ordinal suffixes
    		array( '1^1', '<span class="numbers">1</span><sup><span class="numbers">1</span></sup>', false ), // smart exponents
    		array( 'a &amp; b', 'a <span class="amp">&amp;</span>&nbsp;b', false ), // wrap amps
    		array( 'a  b', 'a b', false ), // space collapse
    		array( 'NATO', '<span class="caps">NATO</span>', false ), // style caps
    		array( 'superfluous', 'super&shy;flu&shy;ous', false ), // hyphenate
    		array( 'http://example.org', 'http://&#8203;exam&#8203;ple&#8203;.org', false ), // wrap URLs
    		array( 'foo@example.org', 'foo@&#8203;example.&#8203;org', false ), // wrap emails
    		array( '<span> </span>', '<span> </span>', true ), // whitespace is ignored
    		array( '<span class="noTypo">123</span>', '<span class="noTypo">123</span>', true ), // skipped class
    	);
    }

    /**
     * @covers ::process
     *
     * @uses PHP_Typography\Hyphenator
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_process_data
     */
    public function test_process( $html, $result, $feed )
    {
    	$typo = $this->typo;
    	$typo->set_defaults( true );

    	$this->assertSame( $result, clean_html( $typo->process( $html ) ) );
    }

    /**
     * @covers ::process_feed
     *
     * @uses PHP_Typography\Hyphenator
     * @uses PHP_Typography\Parse_Text
	 *
     * @dataProvider provide_process_data
     */
    public function test_process_feed( $html, $result, $feed )
    {
    	$typo = $this->typo;
    	$typo->set_defaults( true );

		if ( is_string( $feed ) ) {
   			$this->assertSame( $feed, clean_html( $typo->process_feed( $html ) ) );
		} elseif ( $feed ) {
			$this->assertSame( $result, clean_html( $typo->process_feed( $html ) ) );
		} else {
			$this->assertSame( $html, $typo->process_feed( $html ) );
		}
    }

    public function provide_process_words_data() {
    	return array(
    		array( 'superfluous', 'super&shy;flu&shy;ous', false ), // hyphenate
    		array( 'super-policemen', 'super-police&shy;men', false ), // hyphenate compounds
    		array( 'http://example.org', 'http://&#8203;exam&#8203;ple&#8203;.org', false ), // wrap URLs
    		array( 'foo@example.org', 'foo@&#8203;example.&#8203;org', false ), // wrap emails
    	);
    }

    /**
     * @covers ::process_words
     *
     * @uses PHP_Typography\Hyphenator
     * @uses PHP_Typography\Parse_Text
	 *
     * @dataProvider provide_process_words_data
     */
    public function test_process_words( $text, $result, $is_title )
    {
    	$typo = $this->typo;
    	$typo->set_defaults( true );

   		$node = new \DOMText( $text );
   		$typo->process_words( $node, $is_title );

    	$this->assertSame( $result, clean_html( $node->data ) );
    }

    public function provide_process_with_title_data() {
    	return array(
    		array( 'Really...', 'Really&hellip;', true, '' ), // processed
    		array( 'Really...', 'Really...', true, array( 'h1' ) ), // skipped
    	);
    }

    /**
     * @covers ::process
     *
     * @uses PHP_Typography\Hyphenator
     * @uses PHP_Typography\Parse_Text
	 *
     * @dataProvider provide_process_with_title_data
     */
    public function test_process_with_title( $html, $result, $feed, $skip_tags )
    {
    	$typo = $this->typo;
    	$typo->set_defaults( true );
    	$typo->set_tags_to_ignore( $skip_tags );

    	$this->assertSame( $result, clean_html( $typo->process( $html, true ) ) );
    }

    /**
     * @covers ::process_feed
     *
     * @uses PHP_Typography\Hyphenator
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_process_with_title_data
     */
    public function test_process_feed_with_title( $html, $result, $feed, $skip_tags )
    {
    	$typo = $this->typo;
    	$typo->set_defaults( true );
    	$typo->set_tags_to_ignore( $skip_tags );

    	if ( is_string( $feed ) ) {
    		$this->assertSame( $feed, clean_html( $typo->process_feed( $html, true ) ) );
    	} elseif ( $feed ) {
    		$this->assertSame( $result, clean_html( $typo->process_feed( $html, true ) ) );
    	} else {
    		$this->assertSame( $html, $typo->process_feed( $html, true ) );
    	}
    }

    /**
     * @return array( $errno, $errstr, $errfile, $errline, $errcontext, $result )
     */
    public function provide_handle_parsing_errors() {
    	return array(
    		array( E_USER_WARNING, "Fake error message", "/some/path/DOMTreeBuilder.php", '666', array(), true ),
    		array( E_USER_ERROR,   "Fake error message", "/some/path/DOMTreeBuilder.php", '666', array(), false ),
    		array( E_USER_WARNING, "Fake error message", "/some/path/SomeFile.php",       '666', array(), false ),
    		array( E_USER_NOTICE,  "Fake error message", "/some/path/DOMTreeBuilder.php", '666', array(), false ),
    	);
    }

    /**
     * @covers ::handle_parsing_errors
     *
     * @dataProvider provide_handle_parsing_errors
     */
    public function test_handle_parsing_errors( $errno, $errstr, $errfile, $errline, $errcontext, $result ) {
    	if ( $result ) {
    		$this->assertTrue( $this->typo->handle_parsing_errors( $errno, $errstr, $errfile, $errline, $errcontext ) );
    	} else {
    		$this->assertFalse( $this->typo->handle_parsing_errors( $errno, $errstr, $errfile, $errline, $errcontext ) );
    	}

    	// try again when we are not interested
    	$old_level = error_reporting( 0 );
    	$this->assertTrue( $this->typo->handle_parsing_errors( $errno, $errstr, $errfile, $errline, $errcontext ) );
    	error_reporting( $old_level );
    }


    /**
     * @covers ::get_prev_chr
     * @covers ::get_previous_textnode
     */
    public function test_get_prev_chr()
    {
    	$typo = $this->typo;

    	$html = '<p><span>A</span><span id="foo">new hope.</span></p><p><span id="bar">The empire</span> strikes back.</p<';
    	$doc = $typo->get_html5_parser()->loadHTML( $html );
    	$xpath = new DOMXPath( $doc );

    	$textnodes = $xpath->query( "//*[@id='foo']/text()" ); // really only one
		$prev_char = $typo->get_prev_chr( $textnodes->item( 0 ) );
		$this->assertSame( 'A', $prev_char );

		$textnodes = $xpath->query( "//*[@id='bar']/text()" ); // really only one
		$prev_char = $typo->get_prev_chr( $textnodes->item( 0 ) );
		$this->assertSame( '', $prev_char );
    }

    /**
     * @covers ::get_previous_textnode
     */
    public function test_get_previous_textnode_null() {
    	$typo = $this->typo;
    	$typo->process('');

    	$node = $typo->get_previous_textnode( null );
    	$this->assertNull( $node );
    }

    /**
     * @covers ::get_next_chr
     * @covers ::get_next_textnode
     */
    public function test_get_next_chr()
    {
    	$typo = $this->typo;

    	$html = '<p><span id="foo">A</span><span id="bar">new hope.</span></p><p><span>The empire</span> strikes back.</p<';
    	$doc = $typo->get_html5_parser()->loadHTML( $html );
    	$xpath = new DOMXPath( $doc );

    	$textnodes = $xpath->query( "//*[@id='foo']/text()" ); // really only one
    	$prev_char = $typo->get_next_chr( $textnodes->item( 0 ) );
    	$this->assertSame( 'n', $prev_char );

    	$textnodes = $xpath->query( "//*[@id='bar']/text()" ); // really only one
    	$prev_char = $typo->get_next_chr( $textnodes->item( 0 ) );
    	$this->assertSame( '', $prev_char );
    }

    /**
     * @covers ::get_next_textnode
     */
    public function test_get_next_textnode_null() {
    	$typo = $this->typo;
    	$typo->process('');

    	$node = $typo->get_next_textnode( null );
    	$this->assertNull( $node );
    }

    /**
     * @covers ::get_first_textnode
     */
    public function test_get_first_textnode()
    {
    	$typo = $this->typo;

    	$html = '<p><span id="foo">A</span><span id="bar">new hope.</span></p>';
    	$doc = $typo->get_html5_parser()->loadHTML( $html );
    	$xpath = new DOMXPath( $doc );

    	$textnodes = $xpath->query( "//*[@id='foo']/text()" ); // really only one
    	$node = $typo->get_first_textnode( $textnodes->item( 0 ) );
    	$this->assertSame( 'A', $node->nodeValue );

    	$textnodes = $xpath->query( "//*[@id='foo']" ); // really only one
    	$node = $typo->get_first_textnode( $textnodes->item( 0 ) );
    	$this->assertSame( 'A', $node->nodeValue );

    	$textnodes = $xpath->query( "//*[@id='bar']" ); // really only one
    	$node = $typo->get_first_textnode( $textnodes->item( 0 ) );
    	$this->assertSame( 'new hope.', $node->nodeValue );

    	$textnodes = $xpath->query( "//p" ); // really only one
		$node = $typo->get_first_textnode( $textnodes->item( 0 ) );
    	$this->assertSame( 'A', $node->nodeValue );
    }

    /**
     * @covers ::get_first_textnode
     */
    public function test_get_first_textnode_null()
    {
    	$typo = $this->typo;
    	$typo->process('');

    	// passing null returns null
		$this->assertNull( $typo->get_first_textnode( null ) );

		// passing a DOMNode that is not a DOMElement or a DOMText returns null as well
		$this->assertNull( $typo->get_first_textnode( new DOMDocument() ) );
    }

    /**
     * @covers ::get_first_textnode
     */
    public function test_get_first_textnode_only_block_level()
    {
       	$typo = $this->typo;

    	$html = '<div><div id="foo">No</div><div id="bar">hope</div></div>';
    	$doc = $typo->get_html5_parser()->loadHTML( $html );
    	$xpath = new DOMXPath( $doc );

    	$textnodes = $xpath->query( "//div" ); // really only one
    	$node = $typo->get_first_textnode( $textnodes->item( 0 ) );
    	$this->assertNull( $node );
    }


    /**
     * @covers ::get_last_textnode
     */
    public function test_get_last_textnode()
    {
    	$typo = $this->typo;

    	$html = '<p><span id="foo">A</span><span id="bar">new hope.</span> Really.</p>';
    	$doc = $typo->get_html5_parser()->loadHTML( $html );
    	$xpath = new DOMXPath( $doc );

    	$textnodes = $xpath->query( "//*[@id='foo']/text()" ); // really only one
    	$node = $typo->get_last_textnode( $textnodes->item( 0 ) );
    	$this->assertSame( 'A', $node->nodeValue );

    	$textnodes = $xpath->query( "//*[@id='foo']" ); // really only one
    	$node = $typo->get_last_textnode( $textnodes->item( 0 ) );
    	$this->assertSame( 'A', $node->nodeValue );

    	$textnodes = $xpath->query( "//*[@id='bar']" ); // really only one
    	$node = $typo->get_first_textnode( $textnodes->item( 0 ) );
    	$this->assertSame( 'new hope.', $node->nodeValue );

    	$textnodes = $xpath->query( "//p" ); // really only one
		$node = $typo->get_last_textnode( $textnodes->item( 0 ) );
    	$this->assertSame( ' Really.', $node->nodeValue );
    }

    /**
     * @covers ::get_last_textnode
     */
    public function test_get_last_textnode_null()
    {
    	$typo = $this->typo;
    	$typo->process('');

    	// passing null returns null
    	$this->assertNull( $typo->get_last_textnode( null ) );

    	// passing a DOMNode that is not a DOMElement or a DOMText returns null as well
		$this->assertNull( $typo->get_last_textnode( new DOMDocument() ) );
    }

    /**
     * @covers ::get_last_textnode
     */
    public function test_get_last_textnode_only_block_level()
    {
    	$typo = $this->typo;

    	$html = '<div><div id="foo">No</div><div id="bar">hope</div></div>';
    	$doc = $typo->get_html5_parser()->loadHTML( $html );
    	$xpath = new DOMXPath( $doc );

    	$textnodes = $xpath->query( "//div" ); // really only one
    	$node = $typo->get_last_textnode( $textnodes->item( 0 ) );
    	$this->assertNull( $node );
    }

    public function provide_smart_quotes_data() {
    	return array(
    		array( '<span>"Double", \'single\'</span>', '<span>&ldquo;Double&rdquo;, &lsquo;single&rsquo;</span>' ),
    		array( '<p>"<em>This is nuts.</em>"</p>',   '<p>&ldquo;<em>This is nuts.</em>&rdquo;</p>' ),
    		array( '"This is so 1996", he said.',       '&ldquo;This is so 1996&rdquo;, he said.' ),
    		array( '6\'5"',                             '6&prime;5&Prime;' ),
    		array( '6\' 5"',                            '6&prime; 5&Prime;' ),
    		array( '6\'&nbsp;5"',                       '6&prime;&nbsp;5&Prime;' ),
    		array( " 6'' ",                             ' 6&Prime; ' ), // nobody uses this for quotes, so it should be OK to keep the primes here
     		array( 'ein 32"-Fernseher',                 'ein 32&Prime;-Fernseher' ),
    		array( "der 8'-Ölbohrer",                   "der 8&prime;-&Ouml;lbohrer" ),
    		array( "der 1/4'-Bohrer",                   "der 1/4&prime;-Bohrer" ),
    		array( "2/4'",                              "2/4&prime;" ),
    		array( '3/44"',                             '3/44&Prime;' ),
    		array( '("Some" word',					    '(&ldquo;Some&rdquo; word' ),
    	);
    }

    /**
     * @covers ::smart_quotes
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_smart_quotes_data
     */
    public function test_smart_quotes( $html, $result )
    {
    	$typo = $this->typo;
    	$typo->set_smart_quotes( true );

    	$this->assertSame( $result, clean_html( $typo->process( $html ) ) );
    }

    /**
     * @covers ::smart_quotes
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_smart_quotes_data
     */
    public function test_smart_quotes_off( $html, $result )
    {
     	$typo = $this->typo;
    	$typo->set_smart_quotes( false );

    	$this->assertSame( clean_html( $html ), clean_html( $typo->process( $html ) ) );
    }

    public function provide_smart_quotes_special_data() {
    	return array(
    		array( '("Some" word', '(&raquo;Some&laquo; word', 'doubleGuillemetsReversed', 'singleGuillemetsReversed' ),
    	);
    }

    /**
     * @covers ::smart_quotes
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_smart_quotes_special_data
     */
    public function test_smart_quotes_special( $html, $result, $primary, $secondary )
    {
    	$typo = $this->typo;
    	$typo->set_smart_quotes( true );
    	$typo->set_smart_quotes_primary( $primary );
    	$typo->set_smart_quotes_secondary( $secondary );

    	$this->assertSame( $result, clean_html( $typo->process( $html ) ) );
    }

    /**
     * @covers ::smart_dashes
     * @covers ::dash_spacing
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_smart_dashes_data
     */
    public function test_smart_dashes_with_dash_spacing_off( $input, $result_us, $result_int )
    {
		$typo = $this->typo;
		$typo->set_smart_dashes( true );
		$typo->set_dash_spacing( false );

		$typo->set_smart_dashes_style( 'traditionalUS' );
		$this->assertSame( $result_us, clean_html( $typo->process( $input ) ) );

		$typo->set_smart_dashes_style( 'international' );
		$this->assertSame( $result_int, clean_html( $typo->process( $input ) ) );
    }

    /**
     * @covers ::smart_dashes
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_smart_dashes_data
     */
    public function test_smart_dashes_off( $input, $result_us, $result_int )
    {
    	$typo = $this->typo;
    	$typo->set_smart_dashes( false );
    	$typo->set_dash_spacing( false );

    	$typo->set_smart_dashes_style( 'traditionalUS' );
    	$this->assertSame( $input, clean_html( $typo->process( $input ) ) );

    	$typo->set_smart_dashes_style( 'international' );
    	$this->assertSame( $input, clean_html( $typo->process( $input ) ) );
    }

    public function provide_smart_ellipses_data() {
    	return array(
    		array( 'Where are we going... Really....?', 'Where are we going&hellip; Really.&hellip;?' ),
    	);
    }

    /**
     * @covers ::smart_ellipses
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_smart_ellipses_data
     */
    public function test_smart_ellipses( $input, $result )
    {
    	$typo = $this->typo;
		$typo->set_smart_ellipses( true );

		$this->assertSame( $result, clean_html( $this->typo->process( $input ) ) );
    }

    /**
     * @covers ::smart_ellipses
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_smart_ellipses_data
     */
    public function test_smart_ellipses_off( $input, $result )
    {
    	$typo = $this->typo;
    	$typo->set_smart_ellipses( false );

    	$this->assertSame( $input, clean_html( $this->typo->process( $input ) ) );
    }

    public function provide_smart_diacritics_data() {
    	return array(
    		array( '<p>creme brulee</p>', '<p>crème brûlée</p>', 'en-US' ),
    		array( 'no diacritics to replace, except creme', 'no diacritics to replace, except crème', 'en-US')
    	);
    }

    /**
     * @covers ::smart_diacritics
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_smart_diacritics_data
     */
    public function test_smart_diacritics( $html, $result, $lang )
    {
    	$typo = $this->typo;
    	$typo->set_smart_diacritics( true );
		$typo->set_diacritic_language( $lang );

		$this->assertSame( clean_html( $result ), clean_html( $typo->process( $html ) ) );
    }

    /**
     * @covers ::smart_diacritics
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_smart_diacritics_data
     */
    public function test_smart_diacritics_off( $html, $result, $lang )
    {
    	$typo = $this->typo;
    	$typo->set_smart_diacritics( false );
    	$typo->set_diacritic_language( $lang );

		$this->assertSame( clean_html( $html ), clean_html( $typo->process( $html ) ) );
    }

    public function provide_smart_diacritics_error_in_pattern_data() {
    	return array(
    		array( 'no diacritics to replace, except creme', 'en-US', 'creme' )
    	);
    }

    /**
     * @covers ::smart_diacritics
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_smart_diacritics_error_in_pattern_data
     */
    public function test_smart_diacritics_error_in_pattern( $html, $lang, $unset )
    {
    	$typo = $this->typo;

    	$typo->set_smart_diacritics( true );
    	$typo->set_diacritic_language( $lang );
		unset( $typo->settings['diacriticReplacement']['replacements'][ $unset ] );

    	$this->assertSame( clean_html( $html ), clean_html( $typo->process( $html ) ) );
    }

    public function provide_smart_marks_data() {
    	return array(
    		array( '(c)',  '&copy;' ),
    		array( '(C)',  '&copy;' ),
    		array( '(r)',  '&reg;' ),
    		array( '(R)',  '&reg;' ),
    		array( '(p)',  '&#8471;' ),
    		array( '(P)',  '&#8471;' ),
    		array( '(sm)', '&#8480;' ),
    		array( '(SM)', '&#8480;' ),
    		array( '(tm)', '&trade;' ),
    		array( '(TM)', '&trade;' ),
    		array( '501(c)(1)', '501(c)(1)' ),      // protected
    		array( '501(c)(29)', '501(c)(29)' ),    // protected
    		array( '501(c)(30)', '501&copy;(30)' ), // not protected
    	);
    }

    /**
     * @covers ::smart_marks
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_smart_marks_data
     */
    public function test_smart_marks( $input, $result )
    {
    	$typo = $this->typo;
		$typo->set_smart_marks( true );

		$this->assertSame( $result, clean_html( $this->typo->process( $input ) ) );
    }

    /**
     * @covers ::smart_marks
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_smart_marks_data
     */
    public function test_smart_marks_off( $input, $result )
    {
    	$typo = $this->typo;
    	$typo->set_smart_marks( false );

    	$this->assertSame( $input, clean_html( $this->typo->process( $input ) ) );
    }


    /**
     * Data provider for smarth_math test.
     */
    public function provide_smart_math_data() {
    	return array(
    		array( 'xx 7&minus;3=4 xx',              'xx 7-3=4 xx',      true ),
    		array( 'xx 3&times;3=5&divide;2 xx',     'xx 3*3=5/2 xx',    true ),
    		array( 'xx 0815-4711 xx',                'xx 0815-4711 xx',  true ),
    		array( 'xx 1/2 xx',                      'xx 1/2 xx',        true ),
    		array( 'xx 2001-13-12 xx',               'xx 2001-13-12 xx', false ), // not a valid date
    		array( 'xx 2001-12-13 xx',               'xx 2001-12-13 xx', true ),
    		array( 'xx 2001-13-13 xx',               'xx 2001-13-13 xx', false ), // not a valid date
    		array( 'xx 13-12-2002 xx',               'xx 13-12-2002 xx', true ),
    		array( 'xx 12-13-2002 xx',               'xx 12-13-2002 xx', true ),
    		array( 'xx 13-13-2002 xx',               'xx 13-13-2002 xx', false ), // not a valid date
    		array( 'xx 2001-12 xx',                  'xx 2001-12 xx',    true ),
    		array( 'xx 2001-13 xx',                  'xx 2001-13 xx',    true ), // apparently a valid day count
    		array( 'xx 2001-100 xx',                 'xx 2001-100 xx',   true ),
    		array( 'xx 12/13/2010 xx',               'xx 12/13/2010 xx', true ),
    		array( 'xx 13/12/2010 xx',               'xx 13/12/2010 xx', true ),
    		array( 'xx 13&divide;13&divide;2010 xx', 'xx 13/13/2010 xx', true ), // not a valid date

    	);
    }

    /**
     * @covers ::smart_math
     * @covers ::_smart_math_callback
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_smart_math_data
     */
    public function test_smart_math( $result, $input, $same )
    {
    	$typo = $this->typo;
		$typo->set_smart_math( true );

		if ( $same ) {
			$this->assertSame( $result, clean_html( $typo->process( $input ) ) );
		} else {
			$this->assertNotSame( $result, clean_html( $typo->process( $input ) ) );
		}
    }

    /**
     * @covers ::smart_math
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_smart_math_data
     */
    public function test_smart_math_off( $result, $input, $same )
    {
    	$typo = $this->typo;
    	$typo->set_smart_math( false );

   		$this->assertSame( $input, $typo->process( $input ) );
    }

    /**
     * @covers ::smart_exponents
     *
     * @uses PHP_Typography\Parse_Text
     */
    public function test_smart_exponents()
    {
    	$typo = $this->typo;
    	$typo->set_smart_exponents( true );

   		$this->assertSame( '10<sup>12</sup>', $typo->process( '10^12' ) );
    }

    /**
     * @covers ::smart_exponents
     *
     * @uses PHP_Typography\Parse_Text
     */
    public function test_smart_exponents_off()
    {
    	$typo = $this->typo;
    	$typo->set_smart_exponents( false );

   		$this->assertSame( '10^12', $typo->process( '10^12' ) );
    }

    public function provide_smart_fractions_data() {
    	return array(
    		array(
    			'1/2 3/300 999/1000',
    			'<sup>1</sup>&frasl;<sub>2</sub> <sup>3</sup>&frasl;<sub>300</sub> <sup>999</sup>&frasl;<sub>1000</sub>',
    			'<sup>1</sup>&frasl;<sub>2</sub>&#8239;<sup>3</sup>&frasl;<sub>300</sub> <sup>999</sup>&frasl;<sub>1000</sub>',
    			'',
    			''
    		),
    		array(
    			'1/2 4/2015 1999/2000 999/1000',
    			'<sup>1</sup>&frasl;<sub>2</sub> 4/2015 1999/2000 <sup>999</sup>&frasl;<sub>1000</sub>',
    			'<sup>1</sup>&frasl;<sub>2</sub>&#8239;4/2015 1999/2000&#8239;<sup>999</sup>&frasl;<sub>1000</sub>',
    			'',
    			''
    		),
    		array(
    			'1/2 3/300 999/1000',
    			'<sup class="num">1</sup>&frasl;<sub class="denom">2</sub> <sup class="num">3</sup>&frasl;<sub class="denom">300</sub> <sup class="num">999</sup>&frasl;<sub class="denom">1000</sub>',
    			'<sup class="num">1</sup>&frasl;<sub class="denom">2</sub>&#8239;<sup class="num">3</sup>&frasl;<sub class="denom">300</sub> <sup class="num">999</sup>&frasl;<sub class="denom">1000</sub>',
    			'num',
    			'denom'
    		),
    		array(
    			'1/2 4/2015 1999/2000 999/1000',
    			'<sup class="num">1</sup>&frasl;<sub class="denom">2</sub> 4/2015 1999/2000 <sup class="num">999</sup>&frasl;<sub class="denom">1000</sub>',
    			'<sup class="num">1</sup>&frasl;<sub class="denom">2</sub>&#8239;4/2015 1999/2000&#8239;<sup class="num">999</sup>&frasl;<sub class="denom">1000</sub>',
    			'num',
    			'denom'
    		),
    	);
    }

    /**
     * @covers ::smart_fractions
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_smart_fractions_data
     */
    public function test_smart_fractions( $input, $result, $result_spacing, $num_css_class, $denom_css_class )
    {
    	$typo = new PHP_Typography_CSS_Classes( false, 'now', array( 'numerator' => $num_css_class, 'denominator' => $denom_css_class) );
    	$typo->set_smart_fractions( true );
		$typo->set_true_no_break_narrow_space( true );

		$typo->set_fraction_spacing( false );
		$this->assertSame( $result, clean_html( $typo->process( $input ) ) );

		$typo->set_fraction_spacing( true );
		$this->assertSame( $result_spacing, clean_html( $typo->process( $input ) ) );
    }

    /**
     * @covers ::smart_fractions
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_smart_fractions_data
     */
    public function test_smart_fractions_off( $input, $result, $result_spacing, $num_css_class, $denom_css_class )
    {
    	$typo = new PHP_Typography_CSS_Classes( false, 'now', array( 'numerator' => $num_css_class, 'denominator' => $denom_css_class ) );
    	$typo->set_smart_fractions( false );
    	$typo->set_fraction_spacing( false );

    	$this->assertSame( clean_html( $input ), clean_html( $typo->process( $input ) ) );
    }

    public function provide_smart_fractions_smart_quotes_data() {
    	return array(
    		array(
    			'1/2" 1/2\' 3/4″',
    			'<sup class="num">1</sup>&frasl;<sub class="denom">2</sub>&Prime; <sup class="num">1</sup>&frasl;<sub class="denom">2</sub>&prime; <sup class="num">3</sup>&frasl;<sub class="denom">4</sub>&Prime;',
    			'num',
    			'denom'
    		),
    	);
    }

    /**
     * @covers ::smart_fractions
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_smart_fractions_smart_quotes_data
     */
    public function test_smart_fractions_with_smart_quotes( $input, $result, $num_css_class, $denom_css_class )
    {
    	$typo = new PHP_Typography_CSS_Classes( false, 'now', array( 'numerator' => $num_css_class, 'denominator' => $denom_css_class) );
    	$typo->set_smart_fractions( true );
    	$typo->set_smart_quotes( true );
    	$typo->set_true_no_break_narrow_space( true );
    	$typo->set_fraction_spacing( false );

    	$this->assertSame( $result, clean_html( $typo->process( $input ) ) );
    }

    public function provide_fraction_spacing_data() {
    	return array(
    		array(
    			'1/2 3/300 999/1000',
    			'1/2&nbsp;3/300 999/1000',
    		),
    		array(
    			'1/2 4/2015 1999/2000 999/1000',
    			'1/2&nbsp;4/2015 1999/2000&nbsp;999/1000',
    		),
    	);
    }

    /**
     * @covers ::smart_fractions
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_fraction_spacing_data
     */
    public function test_smart_fractions_only_spacing( $input, $result )
    {
    	$typo = $this->typo;
    	$typo->set_smart_fractions( false );
    	$typo->set_fraction_spacing( true );

    	$this->assertSame( $result, clean_html( $typo->process( $input ) ) );
    }

	public function provide_smart_ordinal_suffix() {
		return array(
			array( 'in the 1st instance', 'in the 1<sup>st</sup> instance', '' ),
			array( 'in the 2nd degree',   'in the 2<sup>nd</sup> degree', '' ),
			array( 'a 3rd party',         'a 3<sup>rd</sup> party', '' ),
			array( '12th Night',          '12<sup>th</sup> Night', '' ),
			array( 'in the 1st instance, we', 'in the 1<sup class="ordinal">st</sup> instance, we', 'ordinal' ),
			array( 'murder in the 2nd degree',   'murder in the 2<sup class="ordinal">nd</sup> degree', 'ordinal' ),
			array( 'a 3rd party',         'a 3<sup class="ordinal">rd</sup> party', 'ordinal' ),
			array( 'the 12th Night',          'the 12<sup class="ordinal">th</sup> Night', 'ordinal' ),
		);
	}

    /**
     * @covers ::smart_ordinal_suffix
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_smart_ordinal_suffix
     */
    public function test_smart_ordinal_suffix( $input, $result, $css_class )
    {
    	$typo = new PHP_Typography_CSS_Classes( false, 'now', array( 'ordinal' => $css_class ) );
    	$typo->set_smart_ordinal_suffix( true );

		$this->assertSame( $result, clean_html( $typo->process( $input ) ) );
    }

    /**
     * @covers ::smart_ordinal_suffix
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_smart_ordinal_suffix
     */
    public function test_smart_ordinal_suffix_off( $input, $result, $css_class )
    {
    	$typo = new PHP_Typography_CSS_Classes( false, 'now', array( 'ordinal' => $css_class ) );
    	$typo->set_smart_ordinal_suffix( false );

    	$this->assertSame( $input, clean_html( $typo->process( $input ) ) );
    }

    public function provide_single_character_word_spacing_data() {
    	return array(
    		array( 'A cat in a tree', 'A cat in a&nbsp;tree' ),
    		array( 'Works with strange characters like ä too. But not Ä or does it?', 'Works with strange characters like &auml;&nbsp;too. But not &Auml;&nbsp;or does it?' ),
    		array( 'Should work even here: <span>a word</span> does not want to be alone.', 'Should work even here: <span>a&nbsp;word</span> does not want to be alone.' ),
    		array( 'And here:<span> </span>a word does not want to be alone.', 'And here:<span> </span>a&nbsp;word does not want to be alone.' ),
    	);
    }

    /**
     * @covers ::single_character_word_spacing
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_single_character_word_spacing_data
     */
    public function test_single_character_word_spacing( $input, $result )
    {
    	$typo = $this->typo;
    	$typo->set_single_character_word_spacing( true );

    	$this->assertSame( $result, clean_html( $typo->process( $input ) ) );
    }

    /**
     * @covers ::single_character_word_spacing
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_single_character_word_spacing_data
     */
    public function test_single_character_word_spacing_off( $input, $result )
    {
    	$typo = $this->typo;
    	$typo->set_single_character_word_spacing( false );

    	$this->assertSame( $input, $typo->process( $input ) );
    }

    public function provide_dash_spacing_data() {
    	return array(
    		array( 'Ein - mehr oder weniger - guter Gedanke, 1908-2008',
    		   	   'Ein&thinsp;&mdash;&thinsp;mehr oder weniger&thinsp;&mdash;&thinsp;guter Gedanke, 1908&thinsp;&ndash;&thinsp;2008',
    		       'Ein &ndash; mehr oder weniger &ndash; guter Gedanke, 1908&#8202;&ndash;&#8202;2008' ),
    		array( "We just don't know --- really---, but you know, --",
    			   "We just don't know&thinsp;&mdash;&thinsp;really&thinsp;&mdash;&thinsp;, but you know, &ndash;",
    			   "We just don't know&#8202;&mdash;&#8202;really&#8202;&mdash;&#8202;, but you know, &ndash;" ),

    	);
    }

    public function provide_smart_dashes_data() {
    	return array(
    		array(
    			'Ein - mehr oder weniger - guter Gedanke, 1908-2008',
    			'Ein &mdash; mehr oder weniger &mdash; guter Gedanke, 1908&ndash;2008',
    			'Ein &ndash; mehr oder weniger &ndash; guter Gedanke, 1908&ndash;2008',
    		),
   			array(
   				"We just don't know --- really---, but you know, --",
     			"We just don't know &mdash; really&mdash;, but you know, &ndash;",
    			"We just don't know &mdash; really&mdash;, but you know, &ndash;",
   			),
    		array(
    			"что природа жизни - это Блаженство",
    			"что природа жизни &mdash; это Блаженство",
    			"что природа жизни &ndash; это Блаженство",
    		),
    	);
    }

    public function provide_dash_spacing_unchanged_data() {
    	return array(
    		array( 'Vor- und Nachteile, i-Tüpfelchen, 100-jährig, Fritz-Walter-Stadion, 2015-12-03, 01-01-1999, 2012-04' ),
    	);
    }

    /**
     * @covers ::dash_spacing
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_dash_spacing_data
     */
    public function test_dash_spacing( $input, $result_us, $result_int )
    {
    	$typo = $this->typo;
    	$typo->set_smart_dashes( true );
    	$typo->set_dash_spacing( true );

    	$typo->set_smart_dashes_style( 'traditionalUS' );
    	$this->assertSame( $result_us, clean_html( $typo->process( $input ) ) );

    	$typo->set_smart_dashes_style( 'international' );
    	$this->assertSame( $result_int, clean_html( $typo->process( $input ) ) );
    }

    /**
     * @covers ::dash_spacing
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_dash_spacing_unchanged_data
     */
    public function test_dash_spacing_unchanged( $input )
    {
    	$typo = $this->typo;
    	$typo->set_smart_dashes( true );
    	$typo->set_dash_spacing( true );

    	$typo->set_smart_dashes_style( 'traditionalUS' );
    	$this->assertSame( $input, $typo->process( $input ) );

    	$typo->set_smart_dashes_style( 'international' );
    	$this->assertSame( $input, $typo->process( $input ) );
    }

    public function provide_space_collapse_data() {
    	return array(
    		array( 'A  new hope&nbsp;  arises.', 'A new hope&nbsp;arises.' ),
    		array( 'A &thinsp;new hope &nbsp;  arises.', 'A&thinsp;new hope&nbsp;arises.' ),
    		array( '<p>  &nbsp;A  new hope&nbsp;  arises.</p>', '<p>A new hope&nbsp;arises.</p>' ),
    	);
    }

    /**
     * @covers ::space_collapse
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_space_collapse_data
     */
    public function test_space_collapse( $input, $result )
    {
	    $typo = $this->typo;
	    $typo->set_space_collapse( true );

	    $this->assertSame( $result, clean_html( $typo->process( $input ) ) );
    }

    /**
     * @covers ::space_collapse
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_space_collapse_data
     */
    public function test_space_collapse_off( $input, $result )
    {
	    $typo = $this->typo;
	    $typo->set_space_collapse( false );

	    $this->assertSame( $input, clean_html( $typo->process( $input ) ) );
    }

    public function provide_unit_spacing_data() {
    	return array(
    		array( 'It was 2 m from', 'It was 2&#8239;m from' ),
    		array( '3 km/h', '3&#8239;km/h' ),
    		array( '5 sg 44 kg', '5 sg 44&#8239;kg' ),
    		array( '100 &deg;C', '100&#8239;&deg;C' ),
    	);
    }

    /**
     * @covers ::unit_spacing
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_unit_spacing_data
     */
    public function test_unit_spacing( $input, $result )
    {
    	$typo = $this->typo;
    	$typo->set_unit_spacing( true );
    	$typo->set_true_no_break_narrow_space( true );

		$this->assertSame( $result, clean_html( $typo->process( $input ) ) );
    }

    /**
     * @covers ::unit_spacing
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_unit_spacing_data
     */
    public function test_unit_spacing_off( $input, $result )
    {
    	$typo = $this->typo;
    	$typo->set_unit_spacing( false );

    	$this->assertSame( $input, clean_html( $typo->process( $input ) ) );
    }

    public function provide_french_punctuation_spacing_data() {
    	return array(
    		array( "Je t'aime ; m'aimes-tu ?", "Je t'aime&#8239;; m'aimes-tu&#8239;?" ),
    		array( "Je t'aime; m'aimes-tu?", "Je t'aime&#8239;; m'aimes-tu&#8239;?" ),
    		array( 'Au secours !', 'Au secours&#8239;!' ),
    		array( 'Au secours!', 'Au secours&#8239;!' ),
    		array( 'Jean a dit : Foo', 'Jean a dit&nbsp;: Foo' ),
    		array( 'Jean a dit: Foo', 'Jean a dit&nbsp;: Foo' ),
    		array( 'http://example.org', 'http://example.org' ),
    		array( 'foo &Ouml; & ; bar', 'foo &Ouml; &amp; ; bar' ),
    		array( '5 > 3', '5 > 3' ),
    		array( 'Les « courants de bord ouest » du Pacifique ? Eh bien : ils sont "fabuleux".', 'Les &laquo;&#8239;courants de bord ouest&#8239;&raquo; du Pacifique&#8239;? Eh bien&nbsp;: ils sont "fabuleux".' ),

    	);
    }

    /**
     * @covers ::french_punctuation_spacing
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_french_punctuation_spacing_data
     */
    public function test_french_punctuation_spacing( $input, $result )
    {
    	$typo = $this->typo;
    	$typo->set_french_punctuation_spacing( true );
    	$typo->set_true_no_break_narrow_space( true );

    	$this->assertSame( $result, clean_html( $typo->process( $input ) ) );
    }

    /**
     * @covers ::french_punctuation_spacing
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_french_punctuation_spacing_data
     */
    public function test_french_punctuation_spacing_off( $input, $result )
    {
    	$typo = $this->typo;
    	$typo->set_french_punctuation_spacing( false );

    	$this->assertSame( clean_html( $input ), clean_html( $typo->process( $input ) ) );
    }

    public function provide_wrap_hard_hyphens_data() {
    	return array(
			array( 'This-is-a-hyphenated-word', 'This-&#8203;is-&#8203;a-&#8203;hyphenated-&#8203;word' ),
    		array( 'This-is-a-hyphenated-', 'This-&#8203;is-&#8203;a-&#8203;hyphenated-' ),

    	);
    }

    /**
     * @covers ::wrap_hard_hyphens
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_wrap_hard_hyphens_data
     */
    public function test_wrap_hard_hyphens( $input, $result )
    {
		$typo = $this->typo;
		$typo->process( '' );
		$typo->set_wrap_hard_hyphens( true );

		$this->assertTokenSame( $result, $typo->wrap_hard_hyphens( $this->tokenize( $input ) ) );

    }

    /**
     * @covers ::wrap_hard_hyphens
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_wrap_hard_hyphens_data
     */
    public function test_wrap_hard_hyphens_with_smart_dashes( $input, $result )
    {
    	$typo = $this->typo;
    	$typo->process( '' );
    	$typo->set_wrap_hard_hyphens( true );
    	$typo->set_smart_dashes( true );

    	$this->assertTokenSame( $result, $typo->wrap_hard_hyphens( $this->tokenize( $input ) ) );

    }

    /**
     * @covers ::wrap_hard_hyphens
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_wrap_hard_hyphens_data
     */
    public function test_wrap_hard_hyphens_off( $input, $result )
    {
		$typo = $this->typo;
		$typo->process( '' );
		$typo->set_wrap_hard_hyphens( false );

		$this->assertTokenSame( $input, $typo->wrap_hard_hyphens( $this->tokenize( $input ) ) );
    }

    public function provide_dewidow_data() {
    	return array(
    		array( 'bla foo b', 'bla foo&nbsp;b', 3, 2 ),
    		array( 'bla foo&thinsp;b', 'bla foo&thinsp;b', 3, 2 ), // don't replace thin space...
    		array( 'bla foo&#8202;b', 'bla foo&#8202;b', 3, 2 ),   // ... or hair space
    		array( 'bla foo bar', 'bla foo bar', 2, 2 ),
    		array( 'bla foo bär...', 'bla foo&nbsp;b&auml;r...', 3, 3 ),
    		array( 'bla foo&nbsp;bär...', 'bla foo&nbsp;b&auml;r...', 3, 3 ),
    		array( 'bla föö&#8203;bar s', 'bla f&ouml;&ouml;&#8203;bar&nbsp;s', 3, 2 ),
    		array( 'bla foo&#8203;bar s', 'bla foo&#8203;bar s', 2, 2 ),
    		array( 'bla foo&shy;bar', 'bla foo&shy;bar', 3, 3 ), // &shy; not matched
    		array( 'bla foo&shy;bar bar', 'bla foo&shy;bar&nbsp;bar', 3, 3 ), // &shy; not matched, but syllable after is
    		array( 'bla foo&#8203;bar bar', 'bla foo&#8203;bar&nbsp;bar', 3, 3 ),
    		array( 'bla foo&nbsp;bar bar', 'bla foo&nbsp;bar bar', 3, 3 ), // widow not replaced because the &nbsp; would pull too many letters from previous
    	);
    }

    public function provide_dewidow_with_hyphenation_data() {
    	return array(
    		array( 'this is fucking ri...', 'this is fuck&shy;ing&nbsp;ri...', 4, 2 ),
    		array( 'this is fucking fucking', 'this is fuck&shy;ing fuck&shy;ing', 4, 2 ),
    	);
    }

    /**
     * @covers ::dewidow
     * @covers ::_dewidow_callback
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_dewidow_data
     */
    public function test_dewidow( $html, $result, $max_pull, $max_length )
    {
    	$typo = $this->typo;
    	$typo->set_dewidow( true );
    	$typo->set_max_dewidow_pull( $max_pull );
    	$typo->set_max_dewidow_length( $max_length );

    	$this->assertSame( $result, clean_html( $typo->process( $html ) ) );
    }

    /**
     * @covers ::dewidow
     * @covers ::_dewidow_callback
     *
     * @uses PHP_Typography\Parse_Text
     * @uses PHP_Typography\Hyphenator
     *
     * @dataProvider provide_dewidow_with_hyphenation_data
     */
    public function test_dewidow_with_hyphenation( $html, $result, $max_pull, $max_length )
    {
    	$typo = $this->typo;
    	$typo->set_dewidow( true );
    	$typo->set_hyphenation( true );
    	$typo->set_hyphenation_language( 'en-US' );
    	$typo->set_min_length_hyphenation( 2 );
    	$typo->set_min_before_hyphenation( 2 );
    	$typo->set_min_after_hyphenation( 2 );
    	$typo->set_max_dewidow_pull( $max_pull );
    	$typo->set_max_dewidow_length( $max_length );

    	$this->assertSame( $result, clean_html( $typo->process( $html ) ) );
    }


    /**
     * @covers ::dewidow
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_dewidow_data
     */
    public function test_dewidow_off( $html, $result, $max_pull, $max_length )
    {
    	$typo = $this->typo;
    	$typo->set_dewidow( false );
    	$typo->set_max_dewidow_pull( $max_pull );
    	$typo->set_max_dewidow_length( $max_length );

    	$this->assertSame( clean_html( $html ), clean_html( $typo->process( $html ) ) );
    }

    public function provide_wrap_urls_data() {
    	return array(
    		array( 'https://example.org/',          'https://&#8203;example&#8203;.org/',          2 ),
    		array( 'http://example.org/',           'http://&#8203;example&#8203;.org/',           2 ),
    		array( 'https://my-example.org',        'https://&#8203;my&#8203;-example&#8203;.org', 2 ),
    		array( 'https://example.org/some/long/path/', 'https://&#8203;example&#8203;.org/&#8203;s&#8203;o&#8203;m&#8203;e&#8203;/&#8203;l&#8203;o&#8203;n&#8203;g&#8203;/&#8203;path/', 5 ),
    		array( 'https://example.org:8080/',     'https://&#8203;example&#8203;.org:8080/',     2 ),
    	);
    }

    /**
     * @covers ::wrap_urls
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_wrap_urls_data
     */
    public function test_wrap_urls( $html, $result, $min_after )
    {
    	$typo = $this->typo;
    	$typo->set_url_wrap( true );
    	$typo->set_min_after_url_wrap( $min_after );

    	$this->assertTokenSame( $result, $typo->wrap_urls( $this->tokenize( $html ) ) );
    }

    /**
     * @covers ::wrap_urls
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_wrap_urls_data
     */
    public function test_wrap_urls_off( $html, $result, $min_after )
    {
    	$typo = $this->typo;
    	$typo->set_url_wrap( false );
    	$typo->set_min_after_url_wrap( $min_after );

    	$this->assertTokenSame( $html, $typo->wrap_urls( $this->tokenize( $html ) ) );
    }

    public function provide_wrap_emails_data() {
    	return array(
    		array( 'code@example.org', 'code@&#8203;example.&#8203;org' ),
    		array( 'some.name@sub.domain.org', 'some.&#8203;name@&#8203;sub.&#8203;domain.&#8203;org' ),
    		array( 'funny123@summer1.org', 'funny1&#8203;2&#8203;3&#8203;@&#8203;summer1&#8203;.&#8203;org' )
    	);
    }

    /**
     * @covers ::wrap_emails
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_wrap_emails_data
     */
    public function test_wrap_emails( $html, $result )
    {
    	$typo = $this->typo;
    	$typo->set_email_wrap( true );

    	$this->assertTokenSame( $result, $typo->wrap_emails( $this->tokenize( $html ) ) );
    }

    /**
     * @covers ::wrap_emails
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_wrap_emails_data
     */
    public function test_wrap_emails_off( $html, $result )
    {
    	$typo = $this->typo;
    	$typo->set_email_wrap( false );

    	$this->assertTokenSame( $html, $typo->wrap_emails( $this->tokenize( $html ) ) );
    }

    public function provide_style_caps_data() {
    	return array(
    		array( 'foo BAR bar', 'foo <span class="caps">BAR</span> bar' ),
    		array( 'foo BARbaz', 'foo BARbaz' ),
    		array( 'foo BAR123 baz', 'foo <span class="caps">BAR123</span> baz' ),
    		array( 'foo 123BAR baz', 'foo <span class="caps">123BAR</span> baz' ),
    	);
    }

    /**
     * @covers ::style_caps
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_style_caps_data
     */
    public function test_style_caps( $html, $result )
    {
    	$typo = $this->typo;
    	$typo->set_style_caps( true );

    	$this->assertSame( $result, clean_html( $typo->process( $html ) ) );
    }

    /**
     * @covers ::style_caps
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_style_caps_data
     */
    public function test_style_caps_off( $html, $result )
    {
    	$typo = $this->typo;
    	$typo->set_style_caps( false );

    	$this->assertSame( clean_html( $html ), clean_html( $typo->process( $html ) ) );
    }

    /**
     * @covers ::replace_node_with_html
     */
    public function test_replace_node_with_html()
    {
    	$typo = $this->typo;
    	$dom = $typo->parse_html( $typo->get_html5_parser(), '<p>foo</p>' );

    	$this->assertInstanceOf( '\DOMDocument', $dom );
    	$original_node = $dom->getElementsByTagName( 'p' )->item( 0 );
    	$parent = $original_node->parentNode;
    	$new_nodes = $typo->replace_node_with_html( $original_node, '<div><span>bar</span></div>' );

    	$this->assertTrue( is_array( $new_nodes ) );
		$this->assertContainsOnlyInstancesOf( '\DOMNode', $new_nodes );
    	foreach ( $new_nodes as $node ) {
    		$this->assertSame( $parent, $node->parentNode );
    	}
    }

    /**
     * @covers ::replace_node_with_html
     */
    public function test_replace_node_with_html_invalid()
    {
    	$typo = $this->typo;

    	$node = new \DOMText( 'foo' );
    	$new_node = $typo->replace_node_with_html( $node, 'bar' );

    	// without a parent node, it's not possible to replace anything
    	$this->assertSame( $node, $new_node );
    }

    public function provide_style_numbers_data() {
    	return array(
    		array( 'foo 123 bar', 'foo <span class="numbers">123</span> bar' ),
    		array( 'foo 123bar baz', 'foo <span class="numbers">123</span>bar baz' ),
    		array( 'foo bar123 baz', 'foo bar<span class="numbers">123</span> baz' ),
    		array( 'foo 123BAR baz', 'foo <span class="numbers">123</span>BAR baz' ),
    	);
    }

    /**
     * @covers ::style_numbers
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_style_numbers_data
     */
    public function test_style_numbers( $html, $result )
    {
    	$typo = $this->typo;
    	$typo->set_style_numbers( true );

    	$this->assertSame( $result, clean_html( $typo->process( $html ) ) );
    }

    /**
     * @covers ::style_numbers
     *
     * @uses PHP_Typography\Parse_Text
     *
	 * @dataProvider provide_style_numbers_data
     */
    public function test_style_numbers_off( $html, $result )
    {
    	$typo = $this->typo;
    	$typo->set_style_numbers( false );

    	$this->assertSame( clean_html( $html ), clean_html( $typo->process( $html ) ) );
    }

    public function provide_style_caps_and_numbers_data() {
    	return array(
    		array( 'foo 123 BAR', 'foo <span class="numbers">123</span> <span class="caps">BAR</span>' ),
    		array( 'FOO-BAR', '<span class="caps">FOO-BAR</span>' ),
    		array( 'foo 123-BAR baz', 'foo <span class="caps"><span class="numbers">123</span>-BAR</span> baz' ),
    		array( 'foo BAR123 baz', 'foo <span class="caps">BAR<span class="numbers">123</span></span> baz' ),
    		array( 'foo 123BAR baz', 'foo <span class="caps"><span class="numbers">123</span>BAR</span> baz' ),
    	);
    }

    /**
     * @coversNothing
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_style_caps_and_numbers_data
     */
    public function test_style_caps_and_numbers( $html, $result )
    {
    	$typo = $this->typo;
    	$typo->set_style_caps( true );
    	$typo->set_style_numbers( true );

    	$this->assertSame( $result, clean_html( $typo->process( $html ) ) );
    }

    public function provide_style_hanging_punctuation_data() {
    	return array(
    		array( '"First "second "third.', '<span class="pull-double">"</span>First <span class="push-double"></span>&#8203;<span class="pull-double">"</span>second <span class="push-double"></span>&#8203;<span class="pull-double">"</span>third.' ),
    		array( '<span>"only pull"</span><span>"push & pull"</span>', '<span><span class="pull-double">"</span>only pull"</span><span><span class="push-double"></span>&#8203;<span class="pull-double">"</span>push &amp; pull"</span>' ),
    		array( '<p><span>"Pull"</span> <span>\'Single Push\'</span></p>', '<p><span><span class="pull-double">"</span>Pull"</span> <span><span class="push-single"></span>&#8203;<span class="pull-single">\'</span>Single Push\'</span></p>' ),
    	);
    }

    /**
     * @covers ::style_hanging_punctuation
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_style_hanging_punctuation_data
     */
    public function test_style_hanging_punctuation( $html, $result )
    {
    	$typo = $this->typo;
    	$typo->set_style_hanging_punctuation( true );

    	$this->assertSame( $result, clean_html( $typo->process( $html ) ) );
    }

    /**
     * @covers ::style_hanging_punctuation
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_style_hanging_punctuation_data
     */
    public function test_style_hanging_punctuation_off( $html, $result )
    {
    	$typo = $this->typo;
    	$typo->set_style_hanging_punctuation( false );

    	$this->assertSame( clean_html( $html ), clean_html( $typo->process( $html ) ) );
    }

    /**
     * @covers ::style_ampersands
     *
     * @uses PHP_Typography\Parse_Text
     *
     */
    public function test_style_ampersands()
    {
    	$typo = $this->typo;
    	$typo->set_style_ampersands( true );

    	$this->assertSame( 'foo <span class="amp">&amp;</span> bar', clean_html( $typo->process( 'foo & bar' ) ) );
    }

    /**
     * @covers ::style_ampersands
     *
     * @uses PHP_Typography\Parse_Text
     *
     */
    public function test_style_ampersands_off()
    {
    	$typo = $this->typo;
    	$typo->set_style_ampersands( false );

    	$this->assertSame( 'foo &amp; bar', clean_html( $typo->process( 'foo & bar' ) ) );
    }

    public function provide_style_initial_quotes_data() {
    	return array(
    		array( '<p>no quote</p>', '<p>no quote</p>', false ),
    		array( '<p>"double quote"</p>', '<p><span class="dquo">"</span>double quote"</p>', false ),
    		array( "<p>'single quote'</p>", "<p><span class=\"quo\">'</span>single quote'</p>", false ),
    		array( '"no title quote"', '"no title quote"', false ),
    		array( '"title quote"', '<span class="dquo">"</span>title quote"', true ),
    	);
    }

    /**
     * @covers ::style_initial_quotes
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_style_initial_quotes_data
     */
    public function test_style_initial_quotes( $html, $result, $is_title )
    {
    	$typo = $this->typo;
    	$typo->set_style_initial_quotes( true );
		$typo->set_initial_quote_tags();

    	$this->assertSame( $result, clean_html( $typo->process( $html, $is_title ) ) );
    }

    /**
     * @covers ::style_initial_quotes
     *
     * @uses PHP_Typography\Parse_Text
     *
     * @dataProvider provide_style_initial_quotes_data
     */
    public function test_style_initial_quotes_off( $html, $result, $is_title )
    {
    	$typo = $this->typo;
    	$typo->set_style_initial_quotes( false );
    	$typo->set_initial_quote_tags();

    	$this->assertSame( $html, $typo->process( $html, $is_title ) );
    }

    public function provide_hyphenate_data() {
    	return array(
			array( 'A few words to hyphenate, like KINGdesk. Really, there should be more hyphenation here!', 'A few words to hy&shy;phen&shy;ate, like KING&shy;desk. Re&shy;ally, there should be more hy&shy;phen&shy;ation here!', 'en-US', true, true, true, false ),
    		array( 'Sauerstofffeldflasche', 'Sau&shy;er&shy;stoff&shy;feld&shy;fla&shy;sche', 'de', true, true, true, false ),
    		array( 'Sauerstoff-Feldflasche', 'Sau&shy;er&shy;stoff-Feld&shy;fla&shy;sche', 'de', true, true, true, true ),
    		array( 'Sauerstoff-Feldflasche', 'Sauerstoff-Feldflasche', 'de', true, true, true, false ),
    	);
    }

    /**
     * @covers ::hyphenate
     *
     * @uses PHP_Typography\Parse_Text
     * @uses PHP_Typography\Hyphenator
     *
     * @dataProvider provide_hyphenate_data
     */
    public function test_hyphenate_off( $html, $result, $lang, $hyphenate_headings, $hyphenate_all_caps, $hyphenate_title_case, $hyphenate_compunds )
    {
    	$typo = $this->typo;
		$typo->set_hyphenation( false );
		$typo->set_hyphenation_language( $lang );
		$typo->set_min_length_hyphenation( 2 );
		$typo->set_min_before_hyphenation( 2 );
		$typo->set_min_after_hyphenation( 2 );
		$typo->set_hyphenate_headings( $hyphenate_headings );
		$typo->set_hyphenate_all_caps( $hyphenate_all_caps );
		$typo->set_hyphenate_title_case( $hyphenate_title_case );
		$typo->set_hyphenate_compounds( $hyphenate_compunds );
		$typo->set_hyphenation_exceptions( array( 'KING-desk' ) );

		$this->assertSame( $html, $typo->process( $html ) );
    }

    /**
     * @covers ::hyphenate
     * @covers ::do_hyphenate
     * @covers ::hyphenate_compounds
     *
     * @uses PHP_Typography\Parse_Text
     * @uses PHP_Typography\Hyphenator
     *
     * @dataProvider provide_hyphenate_data
     */
    public function test_hyphenate( $html, $result, $lang, $hyphenate_headings, $hyphenate_all_caps, $hyphenate_title_case, $hyphenate_compunds )
    {
    	$typo = $this->typo;
    	$typo->set_hyphenation( true );
    	$typo->set_hyphenation_language( $lang );
    	$typo->set_min_length_hyphenation(2);
    	$typo->set_min_before_hyphenation(2);
    	$typo->set_min_after_hyphenation(2);
		$typo->set_hyphenate_headings( $hyphenate_headings );
		$typo->set_hyphenate_all_caps( $hyphenate_all_caps );
		$typo->set_hyphenate_title_case( $hyphenate_title_case );
		$typo->set_hyphenate_compounds( $hyphenate_compunds );
    	$typo->set_hyphenation_exceptions( array( 'KING-desk' ) );

    	/*	$this->assertSame( "This is a paragraph with no embedded hyphenation hints and no hyphen-related CSS applied. Corporate gibberish follows. Think visionary. If you generate proactively, you may have to e-enable interactively. We apply the proverb \"Grass doesn't grow on a racetrack\" not only to our re-purposing but our power to matrix. If all of this comes off as dumbfounding to you, that's because it is! Our feature set is unparalleled in the industry, but our reality-based systems and simple use is usually considered a remarkable achievement. The power to brand robustly leads to the aptitude to embrace seamlessly. What do we streamline? Anything and everything, regardless of reconditeness",
    	 clean_html( $this->object->process("This is a paragraph with no embedded hyphenation hints and no hyphen-related CSS applied. Corporate gibberish follows. Think visionary. If you generate proactively, you may have to e-enable interactively. We apply the proverb \"Grass doesn't grow on a racetrack\" not only to our re-purposing but our power to matrix. If all of this comes off as dumbfounding to you, that's because it is! Our feature set is unparalleled in the industry, but our reality-based systems and simple use is usually considered a remarkable achievement. The power to brand robustly leads to the aptitude to embrace seamlessly. What do we streamline? Anything and everything, regardless of reconditeness") ) );
    	*/

    	$this->assertSame( $result, clean_html( $typo->process( $html ) ) );
    }

    /**
     * @covers ::hyphenate
     *
     * @uses PHP_Typography\Parse_Text
     * @uses PHP_Typography\Hyphenator
     *
     */
    public function test_hyphenate_headings_disabled()
    {
    	$this->typo->set_hyphenation( true );
    	$this->typo->set_hyphenation_language( 'en-US' );
    	$this->typo->set_min_length_hyphenation(2);
    	$this->typo->set_min_before_hyphenation(2);
    	$this->typo->set_min_after_hyphenation(2);
    	$this->typo->set_hyphenate_headings( false );
    	$this->typo->set_hyphenate_all_caps( true );
    	$this->typo->set_hyphenate_title_case( true ); // added in version 1.5
    	$this->typo->set_hyphenation_exceptions( array( 'KING-desk' ) );

    	$html = '<h2>A few words to hyphenate, like KINGdesk. Really, there should be no hyphenation here!</h2>';
    	$this->assertSame( $html, clean_html( $this->typo->process( $html ) ) );
    }

    /**
     * @covers ::do_hyphenate
     *
     * @uses PHP_Typography\Hyphenator
     */
    public function test_do_hyphenate()
    {
    	$this->typo->set_hyphenation( true );
    	$this->typo->set_hyphenation_language( 'de' );
    	$this->typo->set_min_length_hyphenation(2);
    	$this->typo->set_min_before_hyphenation(2);
    	$this->typo->set_min_after_hyphenation(2);
    	$this->typo->set_hyphenate_headings( false );
    	$this->typo->set_hyphenate_all_caps( true );
    	$this->typo->set_hyphenate_title_case( true ); // added in version 1.5

    	$tokens = $this->tokenize( mb_convert_encoding( 'Änderungsmeldung', 'ISO-8859-2' ) );
    	$hyphenated  = $this->typo->do_hyphenate( $tokens );
	   	$this->assertEquals( $hyphenated, $tokens );

	   	$tokens = $this->tokenize( 'Änderungsmeldung' );
	   	$hyphenated  = $this->typo->do_hyphenate( $tokens );
	   	$this->assertNotEquals( $hyphenated, $tokens );
    }

    /**
     * @covers ::do_hyphenate
     *
     * @uses PHP_Typography\Hyphenator
     */
    public function test_do_hyphenate_no_title_case()
    {
    	$this->typo->set_hyphenation( true );
    	$this->typo->set_hyphenation_language( 'de' );
    	$this->typo->set_min_length_hyphenation(2);
    	$this->typo->set_min_before_hyphenation(2);
    	$this->typo->set_min_after_hyphenation(2);
    	$this->typo->set_hyphenate_headings( false );
    	$this->typo->set_hyphenate_all_caps( true );
    	$this->typo->set_hyphenate_title_case( false ); // added in version 1.5

    	$tokens = $this->tokenize( 'Änderungsmeldung' );
    	$hyphenated  = $this->typo->do_hyphenate( $tokens );
    	$this->assertEquals( $tokens, $hyphenated);
    }

    /**
     * @covers ::do_hyphenate
     *
     * @uses PHP_Typography\Hyphenator
     */
    public function test_do_hyphenate_invalid()
    {
    	$this->typo->set_hyphenation( true );
    	$this->typo->set_hyphenation_language( 'de' );
    	$this->typo->set_min_length_hyphenation(2);
    	$this->typo->set_min_before_hyphenation(2);
    	$this->typo->set_min_after_hyphenation(2);
    	$this->typo->set_hyphenate_headings( false );
    	$this->typo->set_hyphenate_all_caps( true );
    	$this->typo->set_hyphenate_title_case( false ); // added in version 1.5

    	$this->typo->settings['hyphenMinBefore'] = 0; // invalid value

    	$tokens = $this->tokenize( 'Änderungsmeldung' );
    	$hyphenated  = $this->typo->do_hyphenate( $tokens );
    	$this->assertEquals( $tokens, $hyphenated);
    }

    /**
     * @covers ::hyphenate
     * @covers ::do_hyphenate
     *
     * @uses PHP_Typography\Hyphenator
     * @uses PHP_Typography\Parse_Text
     */
    public function test_hyphenate_no_custom_exceptions()
    {
    	$this->typo->set_hyphenation( true );
    	$this->typo->set_hyphenation_language( 'en-US' );
    	$this->typo->set_min_length_hyphenation(2);
    	$this->typo->set_min_before_hyphenation(2);
    	$this->typo->set_min_after_hyphenation(2);
    	$this->typo->set_hyphenate_headings( true );
    	$this->typo->set_hyphenate_all_caps( true );
    	$this->typo->set_hyphenate_title_case( true ); // added in version 1.5

    	$this->assertSame( 'A few words to hy&shy;phen&shy;ate, like KINGdesk. Re&shy;ally, there should be more hy&shy;phen&shy;ation here!',
    					   clean_html( $this->typo->process( 'A few words to hyphenate, like KINGdesk. Really, there should be more hyphenation here!' ) ) );
    }

    /**
     * @covers ::hyphenate
     * @covers ::do_hyphenate
     *
     * @uses PHP_Typography\Hyphenator
     * @uses PHP_Typography\Parse_Text
     */
    public function test_hyphenate_no_exceptions_at_all()
    {
    	$this->typo->set_hyphenation( true );
    	$this->typo->set_hyphenation_language( 'en-US' );
    	$this->typo->set_min_length_hyphenation(2);
    	$this->typo->set_min_before_hyphenation(2);
    	$this->typo->set_min_after_hyphenation(2);
    	$this->typo->set_hyphenate_headings( true );
    	$this->typo->set_hyphenate_all_caps( true );
    	$this->typo->set_hyphenate_title_case( true ); // added in version 1.5
		$this->typo->settings['hyphenationPatternExceptions'] = array();
		unset( $this->typo->settings['hyphenationExceptions'] );

    	$this->assertSame( 'A few words to hy&shy;phen&shy;ate, like KINGdesk. Re&shy;ally, there should be more hy&shy;phen&shy;ation here!',
    					   clean_html( $this->typo->process( 'A few words to hyphenate, like KINGdesk. Really, there should be more hyphenation here!' ) ) );
    }

    /**
     * @covers \PHP_Typography\PHP_Typography::get_settings_hash
     */
    public function test_get_settings_hash()
    {
    	$typo = $this->typo;

    	$typo->set_smart_quotes(true);
    	$hash1 = $typo->get_settings_hash(10);
    	$this->assertEquals( 10, strlen( $hash1 ) );

  		$typo->set_smart_quotes(false);
  		$hash2 = $typo->get_settings_hash(10);
  		$this->assertEquals( 10, strlen( $hash2 ) );

  		$this->assertNotEquals( $hash1, $hash2 );
    }

    /**
     * @covers ::init
     * @covers ::initialize_components
     * @covers ::initialize_patterns
     * @covers ::__construct
     *
     * @uses PHP_Typography\Hyphenator
     */
    public function test_init() {
    	$second_typo = new \PHP_Typography\PHP_Typography( false, 'lazy' );
    	$this->assertAttributeEmpty( 'settings', $second_typo );
    	$this->assertAttributeEmpty( 'chr', $second_typo );
    	$this->assertAttributeEmpty( 'quote_styles', $second_typo );
    	$this->assertAttributeEmpty( 'dash_styles', $second_typo );
    	$this->assertAttributeEmpty( 'regex', $second_typo );
    	$this->assertAttributeEmpty( 'components', $second_typo );

    	$second_typo->init();
    	$this->assertAttributeNotEmpty( 'settings', $second_typo );
    	$this->assertAttributeNotEmpty( 'chr', $second_typo );
    	$this->assertAttributeNotEmpty( 'quote_styles', $second_typo );
    	$this->assertAttributeNotEmpty( 'dash_styles', $second_typo );
    	$this->assertAttributeNotEmpty( 'regex', $second_typo );
    	$this->assertAttributeNotEmpty( 'components', $second_typo );
    }

    /**
     * @covers ::set_defaults
     *
     * @uses PHP_Typography\Hyphenator
     */
    public function test_init_no_default() {
    	$second_typo = new \PHP_Typography\PHP_Typography( false, 'lazy' );
    	$second_typo->init( false );

    	$this->assertFalse( isset( $second_typo->settings['smartQuotes'] ) );
    	$second_typo->set_defaults();
    	$this->assertTrue( $second_typo->settings['smartQuotes'] );
    }

    /**
     * @covers ::get_top_level_domains_from_file
     */
    public function test_get_top_level_domains_from_file()
    {
    	$default = 'ac|ad|aero|ae|af|ag|ai|al|am|an|ao|aq|arpa|ar|asia|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|biz|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|cat|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|com|coop|co|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|info|int|in|io|iq|ir|is|it|je|jm|jobs|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mil|mk|ml|mm|mn|mobi|mo|mp|mq|mr|ms|mt|museum|mu|mv|mw|mx|my|mz|name|na|nc|net|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pro|pr|ps|pt|pw|py|qa|re|ro|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tel|tf|tg|th|tj|tk|tl|tm|tn|to|tp|travel|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw';
    	$invalid_result = $this->typo->get_top_level_domains_from_file( '/some/invalid/path/to_a_non_existent_file.txt' );
		$valid_result = $this->typo->get_top_level_domains_from_file( dirname( __DIR__ ) . '/vendor/IANA/tlds-alpha-by-domain.txt' );

		$this->assertSame( $default, $invalid_result );
		$this->assertNotSame( $valid_result, $invalid_result );
		$this->assertNotEmpty( $valid_result );
    }

    /**
     * @covers ::get_html5_parser
     */
    public function test_get_html5_parser() {
    	$typo = $this->typo;

    	$this->assertAttributeEmpty( 'html5_parser', $typo );

    	$parser1 = $typo->get_html5_parser();
		$this->assertInstanceOf( '\Masterminds\HTML5', $parser1 );

		$parser2 = $typo->get_html5_parser();
		$this->assertInstanceOf( '\Masterminds\HTML5', $parser2 );

    	$this->assertSame( $parser1, $parser2 );
    	$this->assertAttributeInstanceOf( '\Masterminds\HTML5', 'html5_parser', $typo );
    }

    /**
     * @covers ::get_text_parser
     *
     * @uses PHP_Typography\Parse_Text::__construct
     */
    public function test_get_text_parser() {
    	$typo = $this->typo;

    	$this->assertAttributeEmpty( 'text_parser', $typo );

    	$parser1 = $typo->get_text_parser();
    	$this->assertInstanceOf( '\PHP_Typography\Parse_Text', $parser1 );

    	$parser2 = $typo->get_text_parser();
    	$this->assertInstanceOf( '\PHP_Typography\Parse_Text', $parser2 );

    	$this->assertSame( $parser1, $parser2 );
    	$this->assertAttributeInstanceOf( '\PHP_Typography\Parse_Text', 'text_parser', $typo );
    }

    /**
     * @covers ::parse_html
     */
    public function test_parse_html() {
    	$typo = $this->typo;
    	$dom = $typo->parse_html( $typo->get_html5_parser(), '<p>some text</p>' );

    	$this->assertInstanceOf( '\DOMDocument', $dom );
    	$this->assertEquals( 1, $dom->getElementsByTagName( 'p' )->length );
    }

    /**
     * @covers ::get_block_parent
     */
    public function test_get_block_parent() {
    	$typo = $this->typo;

    	$html = '<div id="outer"><p id="para"><span>A</span><span id="foo">new hope.</span></p><span><span id="bar">blabla</span></span></div>';
    	$doc = $typo->get_html5_parser()->loadHTML( $html );
    	$xpath = new DOMXPath( $doc );

    	$outer_div  = $xpath->query( "//*[@id='outer']" )->item( 0 ); // really only one
    	$paragraph  = $xpath->query( "//*[@id='para']" )->item( 0 );  // really only one
		$span_foo   = $xpath->query( "//*[@id='foo']" )->item( 0 );   // really only one
    	$span_bar   = $xpath->query( "//*[@id='bar']" )->item( 0 );   // really only one
    	$textnode_a = $xpath->query( "//*[@id='para']//text()" )->item( 0 ); // we don't care which one
    	$textnode_b = $xpath->query( "//*[@id='bar']//text()" )->item( 0 );  // we don't care which one
    	$textnode_c = $xpath->query( "//*[@id='foo']//text()" )->item( 0 );  // we don't care which one

		$this->assertSame( $paragraph, $typo->get_block_parent( $span_foo ) );
		$this->assertSame( $paragraph, $typo->get_block_parent( $textnode_a ) );
		$this->assertSame( $outer_div, $typo->get_block_parent( $paragraph ) );
		$this->assertSame( $outer_div, $typo->get_block_parent( $span_bar ) );
		$this->assertSame( $outer_div, $typo->get_block_parent( $textnode_b ) );
		$this->assertSame( $paragraph, $typo->get_block_parent( $textnode_c ) );
    }

    /**
     * @covers ::set_true_no_break_narrow_space
     */
    public function test_set_true_no_break_narrow_space() {
    	$typo = $this->typo;

    	$typo->set_true_no_break_narrow_space(); // defaults to false
    	$this->assertSame( $typo->chr['noBreakNarrowSpace'], \PHP_Typography\uchr( 160 ) );
     	$this->assertAttributeContains( array( 'open'  => \PHP_Typography\uchr(171) . \PHP_Typography\uchr( 160 ),
     									       'close' => \PHP_Typography\uchr( 160 ) . \PHP_Typography\uchr(187) ), 'quote_styles', $typo );

    	$typo->set_true_no_break_narrow_space( true ); // defaults to false
    	$this->assertSame( $typo->chr['noBreakNarrowSpace'], \PHP_Typography\uchr( 8239 ) );
    	$this->assertAttributeContains( array( 'open'  => \PHP_Typography\uchr(171) . \PHP_Typography\uchr( 8239 ),
    		                                   'close' => \PHP_Typography\uchr( 8239 ) . \PHP_Typography\uchr(187) ), 'quote_styles', $typo );
    }

    /**
     * @covers ::get_hyphenator()
     *
     * @uses PHP_Typography\Hyphenator::__construct
     * @uses PHP_Typography\Hyphenator::build_trie
     * @uses PHP_Typography\Hyphenator::set_custom_exceptions
     * @uses PHP_Typography\Hyphenator::set_language
     * @uses PHP_Typography\Hyphenator::set_min_after
     * @uses PHP_Typography\Hyphenator::set_min_before
     * @uses PHP_Typography\Hyphenator::set_min_length
     */
    public function test_get_hyphenator() {
		$typo = $this->typo;
		$typo->settings['hyphenMinLength'] = 2;
		$typo->settings['hyphenMinBefore'] = 2;
		$typo->settings['hyphenMinAfter'] = 2;
		$typo->settings['hyphenationCustomExceptions'] = array( 'foo-bar' );
		$typo->settings['hyphenLanguage'] = 'en-US';
		$h = $typo->get_hyphenator();

		$this->assertInstanceOf( \PHP_Typography\Hyphenator::class, $h );
    }
}
