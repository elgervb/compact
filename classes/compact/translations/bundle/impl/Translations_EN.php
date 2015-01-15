<?php
namespace compact\translations\bundle\impl;

use compact\translations\bundle\ITranslationBundle;

class Translations_EN implements ITranslationBundle
{
	const LANG = "en";
	
	public function getLanguage()
	{
		return self::LANG;
	}
	
	public function getTranslations()
	{
		return array( /* */
			ITranslationBundle::ERR_VAL_FIELD_NOT_EMPTY => "Field <b>{field}</b> is manditory", /* */
			ITranslationBundle::ERR_VAL_EMAIL => "Field <b>{field}</b> must contain a valid email address", /* */
			ITranslationBundle::ERR_VAL_MAX_CHARS => "Field <b>{field}</b> can only contain {nrofchars} chars",/* */
			ITranslationBundle::ERR_VAL_MIN_CHARS => "Field <b>{field}</b> must contain at least {nrofchars} chars",/* */
			ITranslationBundle::ERR_VAL_TELNR => "Field <b>{field}</b> must contain a valid telephone number. Only numbers, spaces and dashes (-) are allowed", /* */
	        "field.guid" => "guid"
		);
	}
}