<?php

class ImportYml_ParseTemplate
{
	private $offer;
	private $template;
	private $content;
	private $postmeta;
	
	public function __construct($template,ImportYml_Offer $offer)
	{
		$this->template = $template;
		$this->offer = $offer;
		
		/** Start */
		$this->content = $this->template;
		
		$this->content = preg_replace_callback("/{(\S+)}/sU",array(&$this,'replaceElements'),$this->content);
		
		$temp = array();
		preg_match("/(.*)<yml_postmeta>(.*)<\/yml_postmeta>/s",$this->content,$temp);
		
		if (count($temp) > 1)
		{
			$this->content = $temp[1];
			// Читаем postMeta
			$postMeta = $temp[2];
			preg_match_all("/<#\S+#>(.*)<\/#\S+#>/sU",$postMeta,$tmp);
			foreach($tmp[1] as $value)
			{
				$t = array();
				preg_match("/name=(\S+);value=\|(.+)\|/s",$value,$t);
				$this->postmeta[$t[1]] = $t[2];
			}
		
			preg_match_all("/\[#\S+#\](.*)\[\/#\S+#\]/sU",$postMeta,$tmp);
			foreach($tmp[1] as $value)
			{
				$t = array();
				preg_match("/name=(.+);value=\|(.+)\|/s",$value,$t);
				$this->postmeta[$t[1]] = $t[2];
			}
		
			preg_match_all("/\[(\S+)\](.+)\[\/.+\]/U",$postMeta,$tmp);
			foreach($tmp[1] as $value)
			{
				$this->postmeta[$value] = $this->offer->get($value);
			}
		
		
			preg_match_all("/\[#(\S+)#\](.+)\[#\/.+\#]/U",$postMeta,$tmp);
		
			foreach($tmp[2] as $value)
			{
				$t = array();
				preg_match("/name=(.+);value=\|(.+)/s",$value,$t);
				$this->postmeta[$t[1]] = $t[2];
			}
		}
		
		// Производим необходимую замену в post meta
		foreach($this->postmeta as $key => $value)
		{
			$value = str_replace('$[picture]$', $offer->data->picture, $value);
			$this->postmeta[$key] = $value;
		}
	}
	
	public function replaceElements($matches)
	{
		return $this->offer->get($matches[1]);
	}
	
	public function getContent()
	{
		return $this->content;
	}
	
	public function getPostMeta()
	{
		return $this->postmeta;
	}
}