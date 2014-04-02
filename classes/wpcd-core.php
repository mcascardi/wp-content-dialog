<?php
class WPCD_Core {
  public function getCtaCode($opts) { 
    $attrs = self::getLinkAttributes(
				     $opts['main']['url'],
				     $opts['main']['overlay'],
				     $opts['main']['width'],
				     $opts['main']['height']
				     ); 
    
      return <<<HTML
	<a {$attrs}><img src="{$opts['main']['cta']}" alt="{$opts['main']['alt']}"></a>
HTML;

    }

    function getLinkAttributes($url, $type, $width, $height) {
      $attrs = array();
      switch($type) {  	
      case 'thickbox' :
	$q = "TB_ifame=true&width={$width}&height={$height}";
	$attrs[]  = "href='{$url}?{$q}'";
	$attrs[]  = "class='thickbox content_dialog'";
	break;
      case 'fancybox' :
	$attrs[] = "href='{$url}'";
	$attrs[] = "class='fancybox fancybox.iframe content_dialog'";
	break;
      case 'prettyphoto' :
	$q = "iframe=true&width={$width}&height={$height}";
	$attrs[] = "href='{$url}?{$q}'";
	$attrs[] = "rel='prettyPhoto[iframe]'";
	$attrs[]  = "class='content_dialog'";
	break;
      }
      
      $attrs[] = "id='content_dialog'";
      return implode(' ', $attrs);

  }
}