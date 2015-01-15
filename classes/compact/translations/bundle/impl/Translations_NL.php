<?php
namespace compact\translations\bundle\impl;

use compact\translations\bundle\ITranslationBundle;

class Translations_NL implements ITranslationBundle
{
	const LANG = "nl";
	
	public function getLanguage()
	{
		return self::LANG;
	}
	
	public function getTranslations()
	{
		return array( /* */
			ITranslationBundle::ERR_VAL_FIELD_NOT_EMPTY => "Veld <b>{field}</b> is verplicht.", /* */
			ITranslationBundle::ERR_VAL_EMAIL => "Veld <b>{field}</b> moet een geldig email adres bevatten.", /* */
			ITranslationBundle::ERR_VAL_MAX_CHARS => "Het minimaal aantal karakters voor veld <b>{field}</b> is {nrofchars}",/* */
			ITranslationBundle::ERR_VAL_MIN_CHARS => "Het Maximaal aantal karakters voor veld <b>{field}</b> is {nrofchars}",/* */
			ITranslationBundle::ERR_VAL_TELNR => "Veld <b>{field}</b> moet een geldig telefoonnummer bevatten. Alleen nummers, spaties en streepjes (-) zijn toegestaan."
		);
	}
}