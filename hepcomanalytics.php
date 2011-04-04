<?php
/**
 *	Hepcom Analytics
 *	Copyright 2010 by Hep Communications
 *	@author		Chris Pappas
 *	@email		cpappas@hepcom.ca
 *	@version	1.1
 *	@license	http://www.gnu.org/copyleft/gpl.html GNU/GPLv3
 *
 *	This plugin is based on the BIGSHOT Google Analytics plugin
 *	http://www.thinkbigshot.com/blog/technical/152-bigshot-google-analytics-plugin-for-joomla-15.html
 *
 *	Changes: v1.1 - Sept 30 2010
 *		Moved the location from the bottom of the <body> to the bottom of the <head> element
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin');

class plgSystemHepcomanalytics extends JPlugin
{
	function plgSystemHepcomanalytics(&$subject, $config)
	{
		parent::__construct($subject, $config);
		
		$this->_plugin = JPluginHelper::getPlugin( 'system', 'hepcomanalytics' );
		$this->_params = new JParameter( $this->_plugin->params );
	}
	
	function onAfterRender()
	{
		global $mainframe;
		
		$web_property_id = $this->params->get('web_property_id', '');
		
		if($web_property_id == '' || $mainframe->isAdmin() || strpos($_SERVER["PHP_SELF"], "index.php") === false)
		{
			return;
		}

		$buffer = JResponse::getBody();

		$google_analytics_javascript = <<<EOJS
		
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '{$web_property_id}']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>

EOJS;
		
		$buffer = str_replace ("</head>", $google_analytics_javascript."</head>", $buffer);
		JResponse::setBody($buffer);
		
		return true;
	}
}
?>
