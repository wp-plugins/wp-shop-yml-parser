<?php
/**
 * 
 * @package Import Yml
 * @subpackage Offer Class
 * @author Igor Bobko
 */
/**
 * 
 * @todo Здесь нужно сделать удобный интерфес для работы с отдельный офером
 * @todo Есть вариант просто передавать XML конструктору
 */

class ImportYml_Offer_Element
{
	public $name;
	public $value;
	public $attributes;
	public $children = NULL;
}

class ImportYml_Offer
{
	private $id;
	private $xml;
	public $data;
	
	/**
	 * Создает объект, но основе строки из таблицы 
	 *
	 * @param stdClass $data строка из таблицы importyml_offer
	 */
	public function __construct($data)
	{
		$this->data = $data;
		
		if (isset($data->offer_xml))
		{
			$this->xml = simplexml_load_string($data->offer_xml);
			$this->id = $data->id;
		}
	}
	
	/**
	 * Возвращает значение параметра офера.
	 * 
	 * @param string $element Запрос определенной атрибута. Если идет element.subelement, то возвращается подэлемент
	 * @param string $attribute Если указан этот параметр, то будет возвращен атрибут элемента
	 * @return значение, либо null если элемент отсутсвует
	 */
	public function get($element,$attribute = null)
	{
		foreach($this->xml as $e)
		{
			if ($e->getName() == $element)
			{
				return (string)$e;
			}
		}
		return null;
	}
	
	/**
	 * Возвращает все элементы и атрибуты товара
	 * 
	 * Недоработанная функция
	 * 
	 * @return ImportYml_Offer_Element
	 */
	public function getElements()
	{
		echo $this->xml->count();
		
		
		$return = new ImportYml_Offer_Element();
		$return->name = $this->xml->getName();
		
		foreach($this->xml->attributes() as $attr)
		{
			$return->attributes[$attr->getName()] = (string)$attr;
		}
		
		
		foreach($this->xml as $element)
		{
			$tmp = &$return->children[];
			$tmp = new ImportYml_Offer_Element();
			$tmp->name = $element->getName();
			$tmp->value = (string)$element;
			foreach($element->attributes() as $attr)
			{
				echo 1;
				$tmp->attributes[$attr->getName()] = (string)$attr;
			}
			
			
			
		}
		return $return;
	}
	
	/**
	 * Возвращает в строковом виде все доступные элементы
	 * 
	 * @return string 
	 */
	public function getAvalaibleElements()
	{
		$return = $this->strElement($this->xml);
		
		$return .= "\n";
		
		foreach($this->xml as $e)
		{
			$return .= "\t" . $this->strElement($e);
			$return .= "\n";
			if ($e->count())
			{
				$return .= "\t\t" . $this->strElement($e);
				$return .= "\n";
			}
		}
		
		return $return;
	}
	
	public function strElement(SimpleXMLElement $e)
	{
		$return = $e->getName();
		if ($e->attributes()->count())
		{
		
			$attrs = array();
			
			foreach($e->attributes() as $attr)
			{
				$attrs[] = $attr->getName();
			}
			
			$return .= " (" . join(',',$attrs) . ")";

		}
		return $return;
	}
}