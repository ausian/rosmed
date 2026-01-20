<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function rostest_doctors_ensure_translations(): void {
	$locale = function_exists( 'determine_locale' ) ? determine_locale() : get_locale();
	if ( $locale !== 'ru_RU' ) {
		return;
	}

	rostest_doctors_register_gettext_overrides();

	$dir = ROSTEST_DOCTORS_PLUGIN_DIR . '/languages';
	if ( ! is_dir( $dir ) ) {
		wp_mkdir_p( $dir );
	}

	$mo_file = $dir . '/rostest-doctors-ru_RU.mo';

	$headers = array(
		'Project-Id-Version' => 'Rostest Doctors',
		'POT-Creation-Date'  => gmdate( 'Y-m-d H:iO' ),
		'PO-Revision-Date'   => gmdate( 'Y-m-d H:iO' ),
		'Last-Translator'    => 'Rostest',
		'Language-Team'      => 'Rostest',
		'Language'           => 'ru_RU',
		'MIME-Version'       => '1.0',
		'Content-Type'       => 'text/plain; charset=UTF-8',
		'Content-Transfer-Encoding' => '8bit',
	);

	$translations = rostest_doctors_get_ru_translations();
	$mo          = rostest_doctors_build_mo( $translations, $headers );
	@file_put_contents( $mo_file, $mo );
}

function rostest_doctors_register_gettext_overrides(): void {
	static $added = false;
	if ( $added ) {
		return;
	}
	$added = true;

	$map = rostest_doctors_get_ru_translations();

	add_filter(
		'gettext',
		static function ( $translation, $text, $domain ) use ( $map ) {
			if ( $domain !== 'rostest-doctors' ) {
				return $translation;
			}
			return array_key_exists( $text, $map ) ? $map[ $text ] : $translation;
		},
		10,
		3
	);

	add_filter(
		'gettext_with_context',
		static function ( $translation, $text, $context, $domain ) use ( $map ) {
			if ( $domain !== 'rostest-doctors' ) {
				return $translation;
			}
			return array_key_exists( $text, $map ) ? $map[ $text ] : $translation;
		},
		10,
		4
	);
}

function rostest_doctors_get_ru_translations(): array {
	return array(
		'Doctors'                 => 'Доктора',
		'Doctor'                  => 'Доктор',
		'Add New'                 => 'Добавить',
		'Add New Doctor'          => 'Добавить врача',
		'New Doctor'              => 'Новый врач',
		'Edit Doctor'             => 'Редактировать врача',
		'View Doctor'             => 'Просмотр врача',
		'All Doctors'             => 'Все врачи',
		'Search Doctors'          => 'Найти врачей',
		'No doctors found.'       => 'Врачи не найдены.',
		'No doctors found in Trash.' => 'В корзине врачей нет.',

		'Specializations'         => 'Специализации',
		'Specialization'          => 'Специализация',
		'Search Specializations'  => 'Найти специализации',
		'All Specializations'     => 'Все специализации',
		'Parent Specialization'   => 'Родительская специализация',
		'Parent Specialization:'  => 'Родительская специализация:',
		'Edit Specialization'     => 'Редактировать специализацию',
		'Update Specialization'   => 'Обновить специализацию',
		'Add New Specialization'  => 'Добавить специализацию',
		'New Specialization Name' => 'Название новой специализации',

		'Cities'                       => 'Города',
		'City'                         => 'Город',
		'Search Cities'                => 'Найти города',
		'Popular Cities'               => 'Популярные города',
		'All Cities'                   => 'Все города',
		'Edit City'                    => 'Редактировать город',
		'Update City'                  => 'Обновить город',
		'Add New City'                 => 'Добавить город',
		'New City Name'                => 'Название нового города',
		'Separate cities with commas'  => 'Разделяйте города запятыми',
		'Add or remove cities'         => 'Добавить или удалить города',
		'Choose from the most used cities' => 'Выбрать из часто используемых городов',
		'No cities found.'             => 'Города не найдены.',
	);
}

function rostest_doctors_build_mo( array $translations, array $headers ): string {
	$header_string = '';
	foreach ( $headers as $key => $value ) {
		$header_string .= $key . ': ' . $value . "\n";
	}

	$translations = array_merge( array( '' => $header_string ), $translations );
	ksort( $translations, SORT_STRING );

	$originals = array_keys( $translations );
	$strings   = array_values( $translations );
	$count     = count( $originals );

	$header_size      = 7 * 4;
	$orig_table_offset = $header_size;
	$trans_table_offset = $orig_table_offset + ( $count * 8 );
	$orig_strings_offset = $trans_table_offset + ( $count * 8 );

	$orig_pool = '';
	$trans_pool = '';

	$orig_table = '';
	$orig_cursor = 0;
	$trans_lengths = array();

	for ( $i = 0; $i < $count; $i++ ) {
		$orig = (string) $originals[ $i ];
		$tran = (string) $strings[ $i ];

		$orig_len = strlen( $orig );
		$tran_len = strlen( $tran );

		$orig_table .= pack( 'VV', $orig_len, $orig_strings_offset + $orig_cursor );
		$trans_lengths[] = $tran_len;

		$orig_pool .= $orig . "\0";
		$orig_cursor += $orig_len + 1;

		$trans_pool .= $tran . "\0";
	}

	$trans_strings_offset = $orig_strings_offset + strlen( $orig_pool );

	$trans_table = '';
	$trans_cursor = 0;
	for ( $i = 0; $i < $count; $i++ ) {
		$tran_len = (int) $trans_lengths[ $i ];
		$trans_table .= pack( 'VV', $tran_len, $trans_strings_offset + $trans_cursor );
		$trans_cursor += $tran_len + 1;
	}

	$mo_header = pack(
		'V7',
		0x950412de,
		0,
		$count,
		$orig_table_offset,
		$trans_table_offset,
		0,
		0
	);

	return $mo_header . $orig_table . $trans_table . $orig_pool . $trans_pool;
}
