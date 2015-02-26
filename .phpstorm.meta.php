<?php

namespace PHPSTORM_META {

	/** @noinspection PhpUnusedLocalVariableInspection */
	/** @noinspection PhpIllegalArrayKeyTypeInspection */
	/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
	$STATIC_METHOD_TYPES = [
		\AbleCore::helper('') => [
			'HTML' instanceof \Drupal\ablecore\Helpers\HTML,
			'Inflector' instanceof \Drupal\ablecore\Helpers\Inflector,
		],
		\AbleCore::hook('') => [
			'menu' instanceof \Drupal\ablecore\HookHelpers\Menu,
			'Menu' instanceof \Drupal\ablecore\HookHelpers\Menu,
			'block_info' instanceof \Drupal\ablecore\HookHelpers\BlockInfo,
			'BlockInfo' instanceof \Drupal\ablecore\HookHelpers\BlockInfo,
			'image_default_styles' instanceof \Drupal\ablecore\HookHelpers\ImageDefaultStyles,
			'ImageDefaultStyles' instanceof \Drupal\ablecore\HookHelpers\ImageDefaultStyles,
			'theme' instanceof \Drupal\ablecore\HookHelpers\Theme,
			'Theme' instanceof \Drupal\ablecore\HookHelpers\Theme,
		]
	];

}
