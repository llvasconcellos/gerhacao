<?php
/**
 * ImageManager Class.
 * @author $Author: Ryan Demmer $
 * @version $Id: manager.class.php 27 2005-09-14 17:51:00 Ryan Demmer $
 */
class Browser extends Manager{
	/* 
	* @var string
	*/
	var $_ext = 'xml=xml;html=htm,html;word=doc,docx;powerpoint=ppt;excel=xls;text=txt,rtf;image=gif,jpeg,jpg,png;acrobat=pdf;archive=zip,tar,gz;flash=swf;winrar=rar;quicktime=mov,mp4,qt;windowsmedia=wmv,asx,asf,avi;audio=wav,mp3,aiff;openoffice=odt,odg,odp,ods,odf';	
	
	/**
	* @access	protected
	*/
	function __construct(){		
		// Call parent
		parent::__construct();
		if( JRequest::getVar( 'type', 'file' ) == 'file'){
			$this->setFileTypes( $this->getPluginParam( 'browser_extensions', $this->_ext ) );
		}else{
			$this->setFileTypes( 'image=jpg,jpeg,png,gif' );
		} 
		$this->init();
	}
	/**
	 * Returns a reference to a editor object
	 *
	 * This method must be invoked as:
	 * 		<pre>  $browser = &Browser::getInstance();</pre>
	 *
	 * @access	public
	 * @return	JCE  The editor object.
	 * @since	1.5
	 */
	function &getInstance(){
		static $instance;
	
		if ( !is_object( $instance ) ){
			$instance = new Browser();
		}
		return $instance;
	}
	function getFileDetails( $file ){
		clearstatcache();
		
		$path 	= Utils::makePath( $this->getBaseDir(), rawurldecode( $file ) );
		$url 	= Utils::makePath( $this->getBaseUrl(), rawurldecode( $file ) );
		
		$date 	= Utils::formatDate( @filemtime( $path ) );
		$size 	= Utils::formatSize( @filesize( $path ) );
		
		$h = array(
			'size'		=>	$size, 
			'modified'	=>	$date,
		);
		
		if( preg_match('/\.(jpeg|jpg|gif|png)/', $file) ){
			$dim = @getimagesize( $path );
	
			$width 	= $dim[0];
			$height = $dim[1];
			
			$pw 	= ( $width >= 100 ) ? 100 : $width;
			$ph 	= ( $pw / $width ) * $height;
		
			if( $ph > 80 ){
				$ph = 80;
				$pw = ( $ph / $height ) * $width;
			}
			
			$h = array(
				'dimensions'	=>	$width. ' x ' .$height,
				'size'			=>	$size, 
				'modified'		=>	$date,
				'preview'		=>	array(
					'src'		=>	$url,
					'width'		=>	round( $pw ),
					'height'	=>	round( $ph )
				)
			);
		}
		return $h;
	}
	function getViewable(){
		return $this->getPluginParam( 'browser_extensions_viewable', 'html,htm,doc,docx,ppt,rtf,xls,txt,gif,jpeg,jpg,png,pdf,swf,mov,mpeg,mpg,avi,asf,asx,dcr,flv,wmv,wav,mp3' );
	}
}

?>
