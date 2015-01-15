<?php
namespace compact\translations\bundle;

interface ITranslationBundle
{
	/*
	 * KEYS
	 */
	const ERR_VAL_FIELD_NOT_EMPTY = "errValFieldNotEmpty";
	const ERR_VAL_EMAIL = "errValEmail";
	const ERR_VAL_MIN_CHARS = "errValMinChars";
	const ERR_VAL_MAX_CHARS = "errValMaxChars";
	const ERR_VAL_TELNR = "errValTelNr";
	
	/**
	 * Returns the language code for which these translations apply
	 *
	 * @return string
	 */
	public function getLanguage();
	
	/**
	 * Get all the translation of this bundle
	 *
	 * @return array with key => value pairs
	 */
	public function getTranslations();
}