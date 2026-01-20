<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function rostest_theme_ensure_translations(): void {
	$locale = function_exists( 'determine_locale' ) ? determine_locale() : get_locale();
	if ( $locale !== 'ru_RU' ) {
		return;
	}

	rostest_theme_register_gettext_overrides();

	$dir = get_template_directory() . '/languages';
	if ( ! is_dir( $dir ) ) {
		wp_mkdir_p( $dir );
	}

	$mo_file = $dir . '/rostest-ru_RU.mo';

	$headers = array(
		'Project-Id-Version' => 'Rostest Theme',
		'POT-Creation-Date'  => gmdate( 'Y-m-d H:iO' ),
		'PO-Revision-Date'   => gmdate( 'Y-m-d H:iO' ),
		'Last-Translator'    => 'Rostest',
		'Language-Team'      => 'Rostest',
		'Language'           => 'ru_RU',
		'MIME-Version'       => '1.0',
		'Content-Type'       => 'text/plain; charset=UTF-8',
		'Content-Transfer-Encoding' => '8bit',
	);

	$translations = rostest_theme_get_ru_translations();
	$mo          = rostest_theme_build_mo( $translations, $headers );
	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
	@file_put_contents( $mo_file, $mo );
}

function rostest_theme_register_gettext_overrides(): void {
	static $added = false;
	if ( $added ) {
		return;
	}
	$added = true;

	$map = rostest_theme_get_ru_translations();

	add_filter(
		'gettext',
		static function ( $translation, $text, $domain ) use ( $map ) {
			if ( $domain !== 'rostest' ) {
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
			if ( $domain !== 'rostest' ) {
				return $translation;
			}
			return array_key_exists( $text, $map ) ? $map[ $text ] : $translation;
		},
		10,
		4
	);
}

function rostest_theme_get_ru_translations(): array {
	return array(
		'Skip to content'  => 'Перейти к содержимому',
		'Primary'          => 'Основное меню',
		'Primary Menu'     => 'Основное меню',
		'Doctors'          => 'Врачи',
		'Doctors archive'  => 'Архив врачей',
		'No posts found.'  => 'Записей не найдено.',
		'Doctor profile'   => 'Профиль врача',
		'Experience'       => 'Стаж',
		'years'            => 'лет',
		'Price from'       => 'Цена от',
		'Price'            => 'Цена',
		'from %s ₽'         => 'от %s ₽',
		'—'                => '—',
		'Rating'           => 'Рейтинг',
		'Tags'             => 'Теги',
		'Find your doctor in minutes' => 'Найдите врача за пару минут',
		'Browse specialists, compare prices and ratings, and book the right fit.' => 'Выбирайте специалистов, сравнивайте цены и рейтинги и находите подходящего.',
		'Open doctors archive' => 'Открыть список врачей',
		'Latest doctors'   => 'Новые врачи',
		'Rating:'          => 'Рейтинг:',
		'View profile'     => 'Открыть профиль',
		'No doctors yet. Add a few to see them here.' => 'Пока нет врачей. Добавьте несколько, и они появятся здесь.',
		'Quick links'      => 'Быстрые ссылки',
		'All doctors'      => 'Все врачи',
		'Top rated'        => 'С высоким рейтингом',
		'Lowest price'     => 'С низкой ценой',
		'Browse doctors and compare experience, price and rating.' => 'Список врачей: сравнивайте стаж, цену и рейтинг.',
		'Prev'             => 'Назад',
		'Next'             => 'Вперёд',
		'No doctors found.' => 'Врачи не найдены.',
		'Specialization'   => 'Специализация',
		'Any'              => 'Любая',
		'City'             => 'Город',
		'Sort'             => 'Сортировка',
		'Default'          => 'По умолчанию',
		'By rating (desc)' => 'По рейтингу (убыв.)',
		'By price (asc)'   => 'По цене (возр.)',
		'By experience (desc)' => 'По стажу (убыв.)',
		'Apply'            => 'Применить',
		'Reset'            => 'Сбросить',
	);
}

function rostest_theme_build_mo( array $translations, array $headers ): string {
	$header_string = '';
	foreach ( $headers as $key => $value ) {
		$header_string .= $key . ': ' . $value . "\n";
	}

	$translations = array_merge( array( '' => $header_string ), $translations );
	ksort( $translations, SORT_STRING );

	$originals = array_keys( $translations );
	$strings   = array_values( $translations );
	$count     = count( $originals );

	$header_size         = 7 * 4;
	$orig_table_offset   = $header_size;
	$trans_table_offset  = $orig_table_offset + ( $count * 8 );
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
