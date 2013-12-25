<?php
/**
 * Universal POT file generator based on WordPress I18n Tools to generate a single POT file from complex plugin structures
 * @author Andi Dittrich
 * @version 0.2
 * @license MIT Style X11 License
 */

// use wordpress I18n tools
require('wptools/extract/extract.php');
require('wptools/pomo/po.php');

class A3nonPOTGenerator{

	// default wordpress extractor rules
	// @see makepot.php
	private $_extractorRules = array(
			'_' => array('string'),
			'__' => array('string'),
			'_e' => array('string'),
			'_c' => array('string'),
			'_n' => array('singular', 'plural'),
			'_n_noop' => array('singular', 'plural'),
			'_nc' => array('singular', 'plural'),
			'__ngettext' => array('singular', 'plural'),
			'__ngettext_noop' => array('singular', 'plural'),
			'_x' => array('string', 'context'),
			'_ex' => array('string', 'context'),
			'_nx' => array('singular', 'plural', null, 'context'),
			'_nx_noop' => array('singular', 'plural', 'context'),
			'_n_js' => array('singular', 'plural'),
			'_nx_js' => array('singular', 'plural', 'context'),
			'esc_attr__' => array('string'),
			'esc_html__' => array('string'),
			'esc_attr_e' => array('string'),
			'esc_html_e' => array('string'),
			'esc_attr_x' => array('string', 'context'),
			'esc_html_x' => array('string', 'context'),
			'comments_number_link' => array('string', 'singular', 'plural'),
	);

	// default source pathes (used by Cryptex and Enligher)
	private $_sourcePaths = array(
			'class',
			'views',
			'views/admin'
	);

	// pot output location
	private $_outputDir;

	// String extractor instance
	private $_stringExtractor;

	// plugin specific metadata
	private $_metadata = array(
			'package-name' => 'PLUGIN_NAME',
			'package-version' => 'PLUGIN_VERSION',
			'msgid-bugs-address' => 'BUG_EMAIL_ADDR',
			'translator-name' => 'YOUR_NAME'
	);

	public function __construct($outputDir, $sourcePaths = null, $metadata = null){
		// user defined paths available ?
		if ($sourcePaths && is_array($sourcePaths)){
			$this->_sourcePaths = $sourcePaths;
		}

		// metadata available ?
		if ($metadata && is_array($metadata)){
			$this->_metadata = $metadata;
		}

		// location of generated pot file
		$this->_outputDir = $outputDir;

		// create new string extractor instance
		$this->_stringExtractor = new StringExtractor($this->_extractorRules);
	}

	public function generate(){
		// buffer of all found messages
		$messageEntries = array();

		// get all files on source path
		$files = $this->getSourceFiles();

		// extract entries from each sourcefile
		foreach ($files as $file){
			echo 'Extracting entries from <'.$file.'>', "\r\n";
			$originals = $this->_stringExtractor->extract_from_file($file, '');
			
			// display entries
			foreach ($originals->entries as $name=>$entry){
				echo '- found entry "', trim($name), '"', "\r\n";
			}
				
			// merge entries
			$messageEntries = array_merge($messageEntries, $originals->entries);
		}

		// create new po instance
		$pot = new PO();

		// assign message entries
		$pot->entries = $messageEntries;

		// set metadata
		$pot->set_header('Content-Type', 'text/plain; charset=UTF-8');
		$pot->set_header('Content-Transfer-Encoding', '8bit');
		$pot->set_header('POT-Creation-Date', gmdate( 'Y-m-d H:i:s+00:00' ) );
		$pot->set_header('PO-Revision-Date', date( 'Y').'-MO-DA HO:MI+ZONE');
		$pot->set_header('MIME-Version', '1.0' );
		$pot->set_header('Project-Id-Version', $this->_metadata['package-name'].' '.$this->_metadata['package-version'] );
		$pot->set_header('Report-Msgid-Bugs-To', $this->_metadata['msgid-bugs-address'] );
		$pot->set_header('Last-Translator', $this->_metadata['translator-name']);

		// store file into output directory; use given plugin-name as filename
		$filename = $this->_outputDir.'/'.$this->_metadata['package-name'].'.pot';
		echo 'Exporting POT file to <'.$filename.'>', "\r\n";
		$pot->export_to_file($filename, true);
	}


	// scan given directories for source files
	public function getSourceFiles(){
		$files = array();

		// get all files on given paths
		foreach ($this->_sourcePaths as $path){
			$files = array_merge($files, self::getSourceFilesFromDirectory($path));
		}
		return $files;
	}

	// get file extension
	public static function getExtension($filename){
		return strtolower(substr(strrchr($filename, '.'), 1));
	}

	// scan directory for php source files
	public static function getSourceFilesFromDirectory($dir){
		$files = scandir($dir);
		$output = array();
		foreach ($files as $file){
			if ($file != '..' && $file != '.' && (self::getExtension($file)=='php' || self::getExtension($file) == 'phtml')){
				$output[] = $dir.'/'.$file;
			}
		}
		return $output;
	}
}
?>